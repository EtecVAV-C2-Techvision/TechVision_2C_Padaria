
<?php
include "proteger.php";
include "../conexao.php";

// Permitir apenas gerente e entregador
if (!in_array($_SESSION['funcao'], ['gerente', 'entregador'])) {
    die("Acesso negado.");
}

$funcao = $_SESSION['funcao'];
$idFunc = $_SESSION['idFunc'];


    // Excluir pedido (somente gerente)
    if (isset($_GET['excluir']) && $funcao === 'gerente') {
        $idPedido = (int)$_GET['excluir'];

        $log = $conn->prepare("INSERT INTO log_pedidos (idPedido, idFunc, acao) VALUES (?, ?, ?)");
        $acao = "Excluiu o pedido #$idPedido";
        $log->bind_param("iis", $idPedido, $idFunc, $acao);
        $log->execute();

        $conn->query("DELETE FROM itens_pedido WHERE idPedido = $idPedido");
        $conn->query("DELETE FROM pedidos WHERE idPedido = $idPedido");

        echo "<p style='color:red;'>Pedido excluído com sucesso!</p>";
    }

    
// Atualizar status (somente se for gerente ou entregador)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idPedido'], $_POST['status'])) {
    $idPedido = (int) $_POST['idPedido'];
    $novoStatus = $_POST['status'];

    // Entregador só pode mudar de "Em processo" para "Entregue"
    if ($funcao === 'entregador') {
        $stmt = $conn->prepare("SELECT status FROM pedidos WHERE idPedido = ?");
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();
        $statusAtual = $stmt->get_result()->fetch_assoc()['status'];

        if ($statusAtual === 'Em processo' && $novoStatus === 'Entregue') {
            $update = $conn->prepare("UPDATE pedidos SET status='Entregue' WHERE idPedido=?");
            $update->bind_param("i", $idPedido);
            $update->execute();
        }
    }

    // Gerente pode mudar livremente
    if ($funcao === 'gerente') {
        $update = $conn->prepare("UPDATE pedidos SET status=? WHERE idPedido=?");
        $update->bind_param("si", $novoStatus, $idPedido);
        $update->execute();
    }


}

// Buscar pedidos
$sql = "
    SELECT p.idPedido, p.data_pedido, p.status, c.nome_completo
    FROM pedidos p
    JOIN clientes c ON p.idCli = c.idCli
    ORDER BY p.data_pedido DESC
";
$pedidos = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Gerenciar Pedidos</title>
<style>
  body {font-family: Arial, sans-serif; background: #fff9ef; margin: 20px;}
  table {width: 100%; border-collapse: collapse; background: #fff;}
  th, td {border: 1px solid #ddd; padding: 10px; text-align: center;}
  button, select {padding: 6px 10px; border-radius: 6px;}
  a.btn {padding: 6px 10px; background: #ccc; color: #000; border-radius: 6px; text-decoration: none;}
</style>
</head>
<body>

<h2>📋 Gerenciar Pedidos</h2>

<table>
<tr>
  <th>ID</th>
  <th>Cliente</th>
  <th>Data</th>
  <th>Status</th>
  <th>Ações</th>
</tr>

<?php while ($p = $pedidos->fetch_assoc()): ?>
<tr>
  <td><?= $p['idPedido'] ?></td>
  <td><?= htmlspecialchars($p['nome_completo']) ?></td>
  <td><?= htmlspecialchars($p['data_pedido']) ?></td>
  <td><?= $p['status'] ?? 'Pendente' ?></td>
  <td>
    <form method="post" style="display:inline;">
      <input type="hidden" name="idPedido" value="<?= $p['idPedido'] ?>">

      <?php if ($funcao === 'entregador'): ?>
        <?php if ($p['status'] === 'Em processo'): ?>
          <input type="hidden" name="status" value="Entregue">
          <button type="submit">Marcar como Entregue</button>
        <?php else: ?>
          <em>Sem ações</em>
        <?php endif; ?>

      <?php elseif ($funcao === 'gerente'): ?>
        <select name="status">
          <option value="Pendente" <?= $p['status'] == 'Pendente' ? 'selected' : '' ?>>Pendente</option>
          <option value="Em processo" <?= $p['status'] == 'Em processo' ? 'selected' : '' ?>>Em processo</option>
          <option value="Entregue" <?= $p['status'] == 'Entregue' ? 'selected' : '' ?>>Entregue</option>
        </select>
        <button type="submit">Atualizar</button>
        <a class="btn" href="editar_pedido.php?id=<?= $p['idPedido'] ?>">Editar Itens</a>
            <a class="btn del" href="?excluir=<?= $p['idPedido'] ?>" onclick="return confirm('Tem certeza que deseja excluir este pedido?')">Excluir</a>
      <?php endif; ?>
    </form>
  </td>
</tr>
<?php endwhile; ?>
</table>

<br>
<a class="btn" href="dashboard.php">⬅ Voltar</a>

</body>
</html>
