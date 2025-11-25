<?php
include('proteger_cliente.php');
include('../conexao.php');

if (!isset($_SESSION['carrinho']) || !is_array($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idProd'])) {
    $idProd = (int) $_POST['idProd'];
    $quantidade = (int) $_POST['quantidade'];

    if ($quantidade <= 0) {
        header("Location: carrinho.php");
        exit;
    }

    $stmt = $conn->prepare("SELECT quantidade FROM produtos WHERE idProd = ?");
    $stmt->bind_param("i", $idProd);
    $stmt->execute();
    $res = $stmt->get_result();
    $produto = $res->fetch_assoc();

    if (!$produto) {
        header("Location: carrinho.php");
        exit;
    }

    // verifica estoque
    $estoqueDisponivel = (int)$produto['quantidade'];
    $quantidadeAtualNoCarrinho = isset($_SESSION['carrinho'][$idProd]) ? (int)$_SESSION['carrinho'][$idProd] : 0;
    $novaQuantidade = $quantidadeAtualNoCarrinho + $quantidade;

    if ($novaQuantidade > $estoqueDisponivel) {
        $_SESSION['carrinho'][$idProd] = $estoqueDisponivel;
    } else {
        $_SESSION['carrinho'][$idProd] = $novaQuantidade;
    }

    header("Location: carrinho.php");
    exit;
}

if (isset($_GET['remover'])) {
    $idRemover = (int) $_GET['remover'];
    if (isset($_SESSION['carrinho'][$idRemover])) {
        unset($_SESSION['carrinho'][$idRemover]);
    }
    header("Location: carrinho.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Seu Carrinho</title>

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
        margin-bottom: 25px;
    }

    .top-links {
        text-align: center;
        margin-bottom: 20px;
    }

    .btn {
        display: inline-block;
        background: #ffcb45;
        padding: 10px 18px;
        border-radius: 6px;
        color: #333;
        font-weight: bold;
        text-decoration: none;
        margin: 5px;
        transition: 0.2s;
    }

    .btn:hover {
        background: #f8d447;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        margin-top: 20px;
    }

    th {
        background: #ffcb45;
        padding: 14px;
        text-align: left;
        color: #333;
        font-size: 16px;
        border-bottom: 3px solid #f8d447;
    }

    td {
        padding: 12px;
        border-bottom: 1px solid #f2e5b3;
        font-size: 15px;
        color: #333;
    }

    tr:hover td {
        background: #fff4c9;
        transition: 0.2s;
    }

    img {
        border-radius: 8px;
        border: 2px solid #f8d447;
    }

    .total-row td {
        background: #fff3c2;
        font-size: 17px;
        font-weight: bold;
    }

    .empty {
        text-align: center;
        font-size: 18px;
        padding: 25px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        width: 350px;
        margin: 40px auto;
        color: #444;
    }
</style>

</head>
<body>

<div class="top-links">
    <a href="compras.php" class="btn">⬅ Continuar Comprando</a>
    <a href="../index.php" class="btn">🏠 Página Inicial</a>
</div>

<h2>🛍 Seu Carrinho</h2>

<?php
if (empty($_SESSION['carrinho'])) {
    echo "<div class='empty'>Seu carrinho está vazio.</div>";
} else {
    echo "<table>";
    echo "<tr><th>Produto</th><th>Imagem</th><th>Preço</th><th>Qtd</th><th>Total</th><th>Ação</th></tr>";

    $totalGeral = 0;

    $stmt_prod = $conn->prepare("SELECT nome, preco, fotos FROM produtos WHERE idProd = ?");

    foreach ($_SESSION['carrinho'] as $idProd => $qtd) {
        $idProd = (int)$idProd;
        $qtd = (int)$qtd;
        if ($qtd <= 0) continue;

        $stmt_prod->bind_param("i", $idProd);
        $stmt_prod->execute();
        $res = $stmt_prod->get_result();
        $p = $res->fetch_assoc();

        if (!$p) {
            unset($_SESSION['carrinho'][$idProd]);
            continue;
        }

        $nome = htmlspecialchars($p['nome']);
        $preco = (float)$p['preco'];
        $subtotal = $preco * $qtd;
        $totalGeral += $subtotal;

        echo "<tr>";
        echo "<td>{$nome}</td>";
        echo "<td><img src='../{$p['fotos']}' width='80'></td>";
        echo "<td>R$ " . number_format($preco, 2, ',', '.') . "</td>";
        echo "<td>{$qtd}</td>";
        echo "<td>R$ " . number_format($subtotal, 2, ',', '.') . "</td>";
        echo "<td><a href='?remover={$idProd}' class='btn'>Remover</a></td>";
        echo "</tr>";
    }

    echo "<tr class='total-row'>
            <td colspan='4'>Total Geral:</td>
            <td colspan='2'>R$ " . number_format($totalGeral, 2, ',', '.') . "</td>
          </tr>";
    echo "</table>";

    echo "<br><a href='finalizar_pedido.php' class='btn'>Finalizar Pedido</a>";
}
?>

</body>
</html>

</html>
