<?php
include "proteger.php";
include "../conexao.php";

// Verifica permissão
if ($_SESSION['funcao'] != 'gerente') {
    die("Acesso negado.");
}

$idFunc = $_SESSION['idFunc'];
$idPedido = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Verifica se pedido existe
$stmt = $conn->prepare("SELECT * FROM pedidos WHERE idPedido=?");
$stmt->bind_param("i", $idPedido);
$stmt->execute();
$pedido = $stmt->get_result()->fetch_assoc();

if (!$pedido) {
    die("Pedido não encontrado.");
}

// Atualizar quantidade de item
if (isset($_POST['atualizar_qtd'])) {
    $idItem = (int)$_POST['idItem'];
    $nova_qtd = (int)$_POST['nova_qtd'];

    $stmt = $conn->prepare("UPDATE itens_pedido SET quantidade=? WHERE idItem=?");
    $stmt->bind_param("ii", $nova_qtd, $idItem);
    $stmt->execute();

    // Log
    $acao = "Alterou a quantidade do item #$idItem para $nova_qtd";
    $log = $conn->prepare("INSERT INTO log_pedidos (idPedido, idFunc, acao) VALUES (?, ?, ?)");
    $log->bind_param("iis", $idPedido, $idFunc, $acao);
    $log->execute();

    echo "<p style='color:green;'>Quantidade atualizada!</p>";
}

// Remover item
if (isset($_GET['remover'])) {
    $idItem = (int)$_GET['remover'];

    // Log
    $acao = "Removeu o item #$idItem do pedido";
    $log = $conn->prepare("INSERT INTO log_pedidos (idPedido, idFunc, acao) VALUES (?, ?, ?)");
    $log->bind_param("iis", $idPedido, $idFunc, $acao);
    $log->execute();

    $stmt = $conn->prepare("DELETE FROM itens_pedido WHERE idItem=?");
    $stmt->bind_param("i", $idItem);
    $stmt->execute();

    echo "<p style='color:red;'>Item removido!</p>";
}

// Adicionar novo item
if (isset($_POST['adicionar_item'])) {
    $idProd = (int)$_POST['idProd'];
    $qtd = (int)$_POST['quantidade'];

    $sql_p = "SELECT preco FROM produtos WHERE idProd=?";
    $stmt_p = $conn->prepare($sql_p);
    $stmt_p->bind_param("i", $idProd);
    $stmt_p->execute();
    $preco = $stmt_p->get_result()->fetch_assoc()['preco'];

    $sql_add = "INSERT INTO itens_pedido (idPedido, idProd, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
    $stmt_add = $conn->prepare($sql_add);
    $stmt_add->bind_param("iiid", $idPedido, $idProd, $qtd, $preco);
    $stmt_add->execute();

    // Log
    $acao = "Adicionou o produto #$idProd (qtd $qtd) ao pedido";
    $log = $conn->prepare("INSERT INTO log_pedidos (idPedido, idFunc, acao) VALUES (?, ?, ?)");
    $log->bind_param("iis", $idPedido, $idFunc, $acao);
    $log->execute();

    echo "<p style='color:green;'>Item adicionado!</p>";
}

// Busca itens do pedido
$sql_itens = "SELECT i.idItem, i.idProd, i.quantidade, i.preco_unitario, p.nome
              FROM itens_pedido i
              JOIN produtos p ON i.idProd = p.idProd
              WHERE i.idPedido=?";
$stmt_i = $conn->prepare($sql_itens);
$stmt_i->bind_param("i", $idPedido);
$stmt_i->execute();
$itens = $stmt_i->get_result();

// Busca produtos disponíveis para adicionar
$produtos = $conn->query("SELECT idProd, nome FROM produtos ORDER BY nome ASC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Editar Pedido #<?= $idPedido ?></title>
<style>
  body{font-family:Arial,Helvetica,sans-serif;background:#fff9ef;margin:20px;}
  h2{color:#222;}
  table{width:100%;border-collapse:collapse;background:#fff;margin-top:18px;}
  th,td{padding:10px;border:1px solid #ddd;text-align:center;}
  form.inline{display:inline;}
  input,select,button{padding:6px 8px;border:1px solid #ccc;border-radius:5px;}
  a.btn{padding:6px 10px;background:#ccc;color:#000;border-radius:6px;text-decoration:none;}
  a.del{background:#ff4d4d;color:#fff;}
</style>
</head>
<body>

<h2>🧾 Editar Pedido #<?= $idPedido ?></h2>

<table>
  <tr><th>ID Item</th><th>Produto</th><th>Qtd</th><th>Preço (R$)</th><th>Total (R$)</th><th>Ações</th></tr>
  <?php 
  $total = 0;
  while ($item = $itens->fetch_assoc()):
    $subtotal = $item['quantidade'] * $item['preco_unitario'];
    $total += $subtotal;
  ?>
    <tr>
      <td><?= $item['idItem'] ?></td>
      <td><?= htmlspecialchars($item['nome']) ?></td>
      <td>
        <form method="post" class="inline">
          <input type="hidden" name="idItem" value="<?= $item['idItem'] ?>">
          <input type="number" name="nova_qtd" value="<?= $item['quantidade'] ?>" min="1" style="width:60px;">
          <button type="submit" name="atualizar_qtd">🔄</button>
        </form>
      </td>
      <td><?= number_format($item['preco_unitario'], 2, ',', '.') ?></td>
      <td><?= number_format($subtotal, 2, ',', '.') ?></td>
      <td><a class="btn del" href="?id=<?= $idPedido ?>&remover=<?= $item['idItem'] ?>" onclick="return confirm('Remover este item?')">❌ Remover</a></td>
    </tr>
  <?php endwhile; ?>
</table>

<p><strong>Total:</strong> R$ <?= number_format($total, 2, ',', '.') ?></p>

<hr>

<h3>➕ Adicionar novo item</h3>
<form method="post">
  <select name="idProd" required>
    <option value="">Selecione o produto</option>
    <?php while ($p = $produtos->fetch_assoc()): ?>
      <option value="<?= $p['idProd'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
    <?php endwhile; ?>
  </select>
  <input type="number" name="quantidade" min="1" value="1" required>
  <button type="submit" name="adicionar_item">Adicionar</button>
</form>

<br>
<a class="btn" href="gerenciar_pedidos.php">⬅ Voltar</a>

</body>
</html>
