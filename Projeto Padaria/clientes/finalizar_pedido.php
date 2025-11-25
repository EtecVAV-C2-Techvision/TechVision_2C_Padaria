<?php
include('proteger_cliente.php');
include('../conexao.php'); // ajuste caminho se necessário


// Verifica se o carrinho não está vazio
if (empty($_SESSION['carrinho'])) {
    echo "<script>alert('Seu carrinho está vazio!');window.location='compras.php';</script>";
    exit;
}

$idCli = $_SESSION['cliente']['idCli'];
$data = date("Y-m-d H:i:s");

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

<style>
    body {
        font-family: "Segoe UI", Arial, sans-serif;
        background: #fff7e0;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 80px auto;
        background: #ffffff;
        padding: 40px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 0 12px rgba(0,0,0,0.1);
        border-top: 10px solid #ffcb45;
    }

    h2 {
        color: #333;
        margin-bottom: 20px;
        font-size: 26px;
    }

    p {
        font-size: 18px;
        color: #444;
    }

    strong {
        color: #333;
    }

    .btn {
        display: inline-block;
        background: #ffcb45;
        color: #333;
        padding: 12px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        margin: 12px 8px;
        border: 2px solid #f8d447;
        transition: .2s;
    }

    .btn:hover {
        background: #f8d447;
    }
</style>

</head>
<body>

<div class="container">
    <h2>✅ Pedido Finalizado com Sucesso!</h2>

    <p>
        Obrigado pela compra, 
        <strong><?= $_SESSION['cliente']['nome']; ?></strong>! <br>
        Seu pedido foi registrado e está sendo processado.
    </p>

    <a href="compras.php" class="btn">🛍️ Voltar às Compras</a>
    <a href="../index.php" class="btn">🏠 Página Inicial</a>
</div>

</body>
</html>

</html>
