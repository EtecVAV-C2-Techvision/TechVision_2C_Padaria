<?php
include('proteger_cliente.php');
include('../conexao.php');

$idCli = (int) $_SESSION['cliente']['idCli'];
$idPedido = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Quando o cliente clicar em "Pagar"
if (isset($_POST['pagar'])) {
    $sql = "UPDATE pedidos SET status='Em processo' WHERE idPedido=? AND idCli=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $idPedido, $idCli);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('✅ Pagamento realizado com sucesso! Pedido em processo.');window.location='ver_pedidos.php?id=$idPedido';</script>";
    } else {
        echo "<script>alert('❌ Não foi possível processar o pagamento.');window.location='ver_pedidos.php?id=$idPedido';</script>";
    }
    exit;
}

// Verifica se o pedido pertence ao cliente
$sql = "SELECT * FROM pedidos WHERE idPedido = ? AND idCli = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $idPedido, $idCli);
$stmt->execute();
$pedido = $stmt->get_result()->fetch_assoc();

if (!$pedido) {
    echo "<script>alert('Pedido não encontrado.');window.location='perfil_cliente.php';</script>";
    exit;
}

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
$status = $pedido['status'] ?: 'Pendente';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Itens do Pedido #<?= $idPedido ?></title>
<style>
  body{font-family:Arial,Helvetica,sans-serif;background:#fff9ef;margin:20px;}
  h2{color:#222;}
  table{width:100%;border-collapse:collapse;background:#fff;margin-top:18px;}
  th,td{padding:10px;border:1px solid #ddd;text-align:center;}
  img{border-radius:6px;}
  a.btn, button.btn{padding:8px 12px;background:#ccc;color:#000;border-radius:6px;text-decoration:none;cursor:pointer;border:none;}
  button.pagar{background:#28a745;color:white;}
  .total{font-weight:bold;font-size:1.1em;text-align:right;margin-top:10px;}
</style>
</head>
<body>

<h2>📦 Pedido #<?= $idPedido ?></h2>
<p><strong>Data:</strong> <?= htmlspecialchars($pedido['data_pedido']) ?>  
 | <strong>Status:</strong> <?= htmlspecialchars($status) ?></p>

<table>
  <tr><th>Produto</th><th>Imagem</th><th>Preço (R$)</th><th>Qtd</th><th>Subtotal (R$)</th></tr>
  <?php while ($item = $itens->fetch_assoc()): 
        $subtotal = $item['preco_unitario'] * $item['quantidade'];
        $total += $subtotal;
  ?>
    <tr>
      <td><?= htmlspecialchars($item['nome']) ?></td>
      <td><img src="../<?= htmlspecialchars($item['fotos']) ?>" width="80"></td>
      <td><?= number_format($item['preco_unitario'], 2, ',', '.') ?></td>
      <td><?= $item['quantidade'] ?></td>
      <td><?= number_format($subtotal, 2, ',', '.') ?></td>
    </tr>
  <?php endwhile; ?>
</table>

<p class="total">💰 <strong>Total do Pedido:</strong> R$ <?= number_format($total, 2, ',', '.') ?></p>

<?php if ($status == 'Pendente'): ?>
<form method="post" style="margin-top:15px;">
  <button type="submit" name="pagar" class="btn pagar">💳 Pagar Pedido</button>
</form>
<?php else: ?>
  <p style="color:green;font-weight:bold;">✅ Pedido já pago e em processo!</p>
<?php endif; ?>

<br>
<a class="btn" href="perfil_cliente.php">⬅ Voltar ao Perfil</a>
<a class="btn" href="compras.php">🛒 Continuar Comprando</a>
<a class="btn" href="../index.php">🏠 Início</a>

</body>
</html>
