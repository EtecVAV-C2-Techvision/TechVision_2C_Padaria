<?php
include('proteger_cliente.php');
include('../conexao.php');

$idCli = (int) $_SESSION['cliente']['idCli'];
$idPedido = isset($_GET['id']) ? (int) $_GET['id'] : 0;

/*
  Função auxiliar para buscar o pedido (útil caso queira reusar).
*/
function buscarPedido($conn, $idPedido, $idCli) {
    $sql = "SELECT * FROM pedidos WHERE idPedido = ? AND idCli = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $idPedido, $idCli);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// BUSCA INICIAL DO PEDIDO
$pedido = buscarPedido($conn, $idPedido, $idCli);

if (!$pedido) {
    echo "<script>alert('Pedido não encontrado.');window.location='perfil_cliente.php';</script>";
    exit;
}

// Normaliza status (remove espaços e trata NULL)
$status = isset($pedido['status']) ? trim($pedido['status']) : '';
if ($status === '') $status = 'Pendente';

// TRATAMENTO DO POST: PAGAR
if (isset($_POST['pagar'])) {

    // 1) CALCULAR TOTAL REAL DO PEDIDO
    $sqlTotal = $conn->prepare("
        SELECT SUM(quantidade * preco_unitario) AS total
        FROM itens_pedido
        WHERE idPedido = ?
    ");
    $sqlTotal->bind_param("i", $idPedido);
    $sqlTotal->execute();
    $resultado = $sqlTotal->get_result()->fetch_assoc();
    $totalPedido = $resultado['total'] ?? 0;

    // 2) SALVAR TOTAL NA TABELA pedidos
    $sqlSalvar = $conn->prepare("UPDATE pedidos SET total=? WHERE idPedido=?");
    $sqlSalvar->bind_param("di", $totalPedido, $idPedido);
    $sqlSalvar->execute();

    // 3) ALTERAR STATUS NORMALMENTE
    if ($status === 'Pendente') {
        $sql = "UPDATE pedidos SET status='Em processo' WHERE idPedido=? AND idCli=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $idPedido, $idCli);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('✅ Pagamento realizado com sucesso! Pedido em processo.');window.location='ver_pedidos.php?id=$idPedido';</script>";
        } else {
            echo "<script>alert('❌ Não foi possível processar o pagamento.');window.location='ver_pedidos.php?id=$idPedido';</script>";
        }
    } else {
        echo "<script>alert('Este pedido não pode ser pago neste status.');window.location='ver_pedidos.php?id=$idPedido';</script>";
    }
    exit;
}


// TRATAMENTO DO POST: CANCELAR
if (isset($_POST['cancelar'])) {
    // permite cancelar se estiver Pendente ou Em processo
    if ($status === 'Pendente' || $status === 'Em processo') {
        $sql = "UPDATE pedidos SET status='Cancelado' WHERE idPedido=? AND idCli=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $idPedido, $idCli);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('❌ Pedido cancelado com sucesso.');window.location='ver_pedidos.php?id=$idPedido';</script>";
        } else {
            echo "<script>alert('Erro ao cancelar o pedido.');window.location='ver_pedidos.php?id=$idPedido';</script>";
        }
    } else {
        echo "<script>alert('Este pedido não pode mais ser cancelado.');window.location='ver_pedidos.php?id=$idPedido';</script>";
    }
    exit;
}

// Se chegou aqui, pede-se novamente os dados (por segurança) — especialmente útil se alguém atualizou via outra ação
$pedido = buscarPedido($conn, $idPedido, $idCli);
$status = isset($pedido['status']) ? trim($pedido['status']) : '';
if ($status === '') $status = 'Pendente';

// Busca os itens do pedido
$sql_itens = "
    SELECT i.*, p.nome, p.fotos
    FROM itens_pedido i
    JOIN produtos p ON i.idProd = p.idProd
    WHERE i.idPedido = ?
";
$stmt_itens = $conn->prepare($sql_itens);
$stmt_itens->bind_param("i", $idPedido);
$stmt_itens->execute();
$itens = $stmt_itens->get_result();

$total = 0;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Itens do Pedido #<?= htmlspecialchars($idPedido) ?></title>

<style>
    body {
        font-family: "Segoe UI", Arial, sans-serif;
        background: #fff7e0;
        margin: 0;
        padding: 20px;
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 10px;
    }

    p {
        text-align: center;
        color: #444;
        font-size: 16px;
    }

    /* TABELA */
    table {
        width: 100%;
        border-collapse: collapse;
        background: #ffffff;
        margin-top: 20px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }

    th {
        background: #ffcb45;
        padding: 14px;
        color: #333;
        font-weight: bold;
        border-bottom: 3px solid #f8d447;
    }

    td {
        padding: 12px;
        border-bottom: 1px solid #f2e5b3;
        text-align: center;
        color: #444;
        font-size: 15px;
    }

    tr:hover td {
        background: #fff3c2;
        transition: 0.2s;
    }

    img {
        border-radius: 8px;
        border: 2px solid #f8d447;
    }

    /* BOTÕES */
    .btn {
        display: inline-block;
        background: #ffcb45;
        padding: 10px 16px;
        border-radius: 8px;
        color: #333;
        font-weight: bold;
        text-decoration: none;
        transition: 0.2s;
        border: 2px solid #f8d447;
        margin: 5px;
    }

    .btn:hover {
        background: #f8d447;
    }

    /* BOTÃO PAGAR */
    .pagar {
        background: #28a745;
        border: none;
        color: #fff;
        font-weight: bold;
        padding: 12px 20px;
        border-radius: 8px;
        cursor: pointer;
        transition: .2s;
        width: 220px;
        font-size: 16px;
    }

    .pagar:hover {
        background: #218838;
    }

    /* BOTÃO CANCELAR */
    .cancelar {
        background: #dc3545;
        border: none;
        color: #fff;
        font-weight: bold;
        padding: 12px 20px;
        border-radius: 8px;
        cursor: pointer;
        transition: .2s;
        width: 220px;
        font-size: 16px;
        margin-top: 10px;
    }

    .cancelar:hover {
        background: #c82333;
    }

    .total {
        text-align: right;
        font-size: 20px;
        font-weight: bold;
        margin-top: 15px;
        color: #333;
    }

    .status-ok {
        color: green;
        font-weight: bold;
        text-align: center;
        margin-top: 12px;
    }

    .btns-footer {
        text-align: center;
        margin-top: 20px;
    }
</style>

</head>
<body>

<h2>📦 Pedido #<?= htmlspecialchars($idPedido) ?></h2>

<p>
    <strong>Data:</strong> <?= htmlspecialchars($pedido['data_pedido']) ?>  
    | <strong>Status:</strong> <?= htmlspecialchars($status) ?>
</p>

<table>
  <tr>
    <th>Produto</th>
    <th>Imagem</th>
    <th>Preço (R$)</th>
    <th>Qtd</th>
    <th>Subtotal (R$)</th>
  </tr>

  <?php while ($item = $itens->fetch_assoc()): 
        $subtotal = $item['preco_unitario'] * $item['quantidade'];
        $total += $subtotal;
  ?>
    <tr>
      <td><?= htmlspecialchars($item['nome']) ?></td>
      <td><img src="../<?= htmlspecialchars($item['fotos']) ?>" width="80" alt="<?= htmlspecialchars($item['nome']) ?>"></td>
      <td><?= number_format($item['preco_unitario'], 2, ',', '.') ?></td>
      <td><?= (int)$item['quantidade'] ?></td>
      <td><?= number_format($subtotal, 2, ',', '.') ?></td>
    </tr>
  <?php endwhile; ?>
</table>

<p class="total">💰 Total do Pedido: R$ <?= number_format($total, 2, ',', '.') ?></p>

<!-- BOTÕES DE AÇÃO (usa $status normalizado) -->
<?php if ($status === 'Pendente'): ?>
    <form method="post" style="text-align:center; margin-top:20px;">
        <button type="submit" name="pagar" class="pagar">💳 Pagar Pedido</button>
    </form>
<?php endif; ?>

<?php if ($status === 'Pendente' || $status === 'Em processo'): ?>
    <form method="post" style="text-align:center;">
        <button type="submit" name="cancelar" class="cancelar" onclick="return confirm('Tem certeza que deseja cancelar este pedido?');">❌ Cancelar Pedido</button>
    </form>
<?php endif; ?>

<?php if ($status === 'Em processo'): ?>
    <p class="status-ok">⚠ Pedido em processamento.</p>
<?php endif; ?>

<?php if ($status === 'Cancelado'): ?>
    <p class="status-ok" style="color:#dc3545;">❌ Pedido Cancelado.</p>
<?php endif; ?>

<?php if ($status !== 'Pendente' && $status !== 'Em processo' && $status !== 'Cancelado'): ?>
    <p class="status-ok">✅ Pedido concluído!</p>
<?php endif; ?>

<div class="btns-footer">
    <a class="btn" href="perfil_cliente.php">⬅ Voltar ao Perfil</a>
    <a class="btn" href="compras.php">🛒 Continuar Comprando</a>
    <a class="btn" href="../index.php">🏠 Início</a>
</div>

</body>
</html>
