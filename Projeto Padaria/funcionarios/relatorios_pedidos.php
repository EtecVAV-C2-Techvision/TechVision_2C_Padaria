<?php

// tenta incluir arquivos de proteção / sessão sem quebrar se caminhos diferirem
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$included = false;
$tryPaths = [
    __DIR__ . '/proteger.php',
    __DIR__ . '/proteger_funcionario.php',
    __DIR__ . '/../proteger.php',
    __DIR__ . '/../proteger_funcionario.php'
];
foreach ($tryPaths as $p) {
    if (file_exists($p)) {
        include_once $p;
        $included = true;
        break;
    }
}

// inclui a conexão (assume que está em ../conexao.php)
if (file_exists(__DIR__ . '/../conexao.php')) {
    include_once __DIR__ . '/../conexao.php';
} else {
    die("Arquivo de conexão não encontrado (../conexao.php).");
}

// --- segurança: detectar função do usuário (compatível com ambos os formatos)
$funcao = '';
if (!empty($_SESSION['funcionario']['funcao'])) {
    $funcao = strtolower($_SESSION['funcionario']['funcao']);
} elseif (!empty($_SESSION['funcao'])) {
    $funcao = strtolower($_SESSION['funcao']);
}

// apenas gerente pode acessar
if ($funcao !== 'gerente') {
    die("<h3 style='color:red;'>Acesso negado! Apenas gerentes podem acessar relatórios.</h3>");
}

// obter mês e ano (padrão o mês atual)
$mesParam = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
$anoParam = isset($_GET['ano']) ? (int)$_GET['ano'] : (int)date('Y');

// validação simples
if ($mesParam < 1 || $mesParam > 12) $mesParam = (int)date('m');
if ($anoParam < 2000 || $anoParam > 2100) $anoParam = (int)date('Y');

// ===========================================
// Atualizar totals na tabela pedidos (a partir de itens_pedido)
// ===========================================
// CORREÇÃO: atualiza SEMPRE, removendo o WHERE
$update_sql = "
    UPDATE pedidos p
    JOIN (
        SELECT idPedido, SUM(quantidade * preco_unitario) AS s
        FROM itens_pedido
        GROUP BY idPedido
    ) t ON t.idPedido = p.idPedido
    SET p.total = t.s
";
$conn->query($update_sql); // executa

// ===========================================
// Busca pedidos entregues do mês/ano selecionado
// ===========================================
$sql = "
    SELECT p.idPedido, p.data_pedido, COALESCE(p.total,0) AS total, c.nome_completo
    FROM pedidos p
    JOIN clientes c ON c.idCli = p.idCli
    WHERE MONTH(p.data_pedido) = ? 
      AND YEAR(p.data_pedido) = ?
      AND p.status = 'Entregue'
    ORDER BY p.data_pedido DESC
";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Erro ao preparar consulta: " . htmlspecialchars($conn->error));
}
$stmt->bind_param("ii", $mesParam, $anoParam);
$stmt->execute();
$result = $stmt->get_result();

// vamos também calcular o total do mês diretamente (mais seguro)
$sqlSoma = "
    SELECT COALESCE(SUM(p.total),0) AS soma
    FROM pedidos p
    WHERE MONTH(p.data_pedido) = ? AND YEAR(p.data_pedido) = ? AND p.status = 'Entregue'
";
$stmt2 = $conn->prepare($sqlSoma);
if ($stmt2 === false) {
    die("Erro ao preparar soma: " . htmlspecialchars($conn->error));
}
$stmt2->bind_param("ii", $mesParam, $anoParam);
$stmt2->execute();
$totalGanho = $stmt2->get_result()->fetch_assoc()['soma'] ?? 0;

// contar pedidos
$totalPedidos = $result->num_rows;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Relatório Mensal de Pedidos Entregues</title>
<style>
    body { font-family: Arial, sans-serif; background:#fff9e0; padding:20px; }
    h2 { text-align:center; color:#333; }
    .filtro { text-align:center; margin-bottom:20px; }
    .card { display:inline-block; background:#fff; padding:18px; border-radius:10px; box-shadow:0 0 8px rgba(0,0,0,.08); margin:8px; width:260px; text-align:center; }
    .card h3 { margin:0 0 6px 0; color:#555; font-weight:600; }
    table { width:100%; border-collapse:collapse; background:#fff; box-shadow:0 0 8px rgba(0,0,0,.06); margin-top:18px; }
    th { background:#ffcb45; padding:10px; color:#333; }
    td { padding:10px; border-bottom:1px solid #f2e1a0; text-align:center; color:#444; }
    tr:hover td { background:#fff3c2; }
    .voltar { display:inline-block; margin-top:18px; padding:10px 14px; background:#ffcb45; border-radius:8px; text-decoration:none; color:#333; font-weight:700; }
</style>
</head>
<body>

<h2>📊 Relatório Mensal de Pedidos Entregues</h2>

<div class="filtro">
    <form method="GET">
        <label><strong>Mês:</strong></label>
        <input type="number" name="mes" min="1" max="12" value="<?php echo $mesParam; ?>">
        <label><strong>Ano:</strong></label>
        <input type="number" name="ano" min="2000" max="2100" value="<?php echo $anoParam; ?>">
        <button type="submit">Filtrar</button>
        &nbsp; &nbsp;
        <label><strong>Ou selecionar:</strong></label>
        <input type="month" name="mesano" value="<?php echo sprintf('%04d-%02d', $anoParam, $mesParam); ?>"
               onchange="(function(e){ var parts=e.target.value.split('-'); if(parts.length===2) { window.location='?mes='+parseInt(parts[1])+'&ano='+parseInt(parts[0]); } })(event)">
    </form>
</div>

<div class="card">
    <h3>Total de Pedidos Entregues</h3>
    <h2><?php echo (int)$totalPedidos; ?></h2>
</div>

<div class="card">
    <h3>Total Ganho no Mês</h3>
    <h2>R$ <?php echo number_format((float)$totalGanho, 2, ',', '.'); ?></h2>
</div>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Cliente</th>
    <th>Data</th>
    <th>Valor Total (R$)</th>
</tr>
</thead>
<tbody>
<?php if ($totalPedidos > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo (int)$row['idPedido']; ?></td>
        <td><?php echo htmlspecialchars($row['nome_completo']); ?></td>
        <td><?php echo date("d/m/Y H:i", strtotime($row['data_pedido'])); ?></td>
        <td><?php echo number_format((float)$row['total'], 2, ',', '.'); ?></td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr><td colspan="4"><em>Nenhum pedido entregue neste mês.</em></td></tr>
<?php endif; ?>
</tbody>
</table>

<a class="voltar" href="dashboard.php">⬅ Voltar</a>

</body>
</html>
