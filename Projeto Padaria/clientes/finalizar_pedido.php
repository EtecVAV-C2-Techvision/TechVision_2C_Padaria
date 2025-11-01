<?php
session_start();
include('../conexao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['cliente'])) {
    header("Location: login_cliente.php");
    exit;
}

// Verifica se o carrinho não está vazio
if (empty($_SESSION['carrinho'])) {
    echo "<script>alert('Seu carrinho está vazio!');window.location='compras.php';</script>";
    exit;
}

$idCli = $_SESSION['cliente']['idCli'];
$data = date("Y-m-d");

// Cria o pedido
$sql = "INSERT INTO pedidos (idCli, data_pedido) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $idCli, $data);
$stmt->execute();

$id_pedido = $stmt->insert_id;

// Insere os itens do pedido e atualiza o estoque
foreach ($_SESSION['carrinho'] as $idProd => $qtd) {
    // Pega o preço do produto
    $sql_prod = "SELECT preco, quantidade FROM produtos WHERE idProd = ?";
    $stmt_prod = $conn->prepare($sql_prod);
    $stmt_prod->bind_param("i", $idProd);
    $stmt_prod->execute();
    $res = $stmt_prod->get_result();
    $p = $res->fetch_assoc();
    $preco = $p['preco'];
    $estoque_atual = $p['quantidade'];

    // Insere no itens_pedido
    $sql_item = "INSERT INTO itens_pedido (idPedido, idProd, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
    $stmt_item = $conn->prepare($sql_item);
    $stmt_item->bind_param("iiid", $id_pedido, $idProd, $qtd, $preco);
    $stmt_item->execute();

    // Atualiza o estoque do produto
    $novo_estoque = $estoque_atual - $qtd;
    $sql_update = "UPDATE produtos SET quantidade = ? WHERE idProd = ?";
    $stmt_up = $conn->prepare($sql_update);
    $stmt_up->bind_param("ii", $novo_estoque, $idProd);
    $stmt_up->execute();
}

// Limpa o carrinho
unset($_SESSION['carrinho']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pedido Finalizado</title>
</head>
<body>

<h2>✅ Pedido Finalizado com Sucesso!</h2>
<p>Obrigado pela compra, <strong><?php echo $_SESSION['cliente']['nome']; ?></strong>! Seu pedido foi registrado.</p>

<a href="compras.php" class="btn">🛍️ Voltar às Compras</a>
<a href="../index.php" class="btn">🏠 Voltar ao Início</a>

</body>
</html>
