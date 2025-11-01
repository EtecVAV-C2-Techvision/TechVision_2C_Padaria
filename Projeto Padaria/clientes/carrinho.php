<?php
session_start();
include('../conexao.php');

// Verifica se o cliente está logado
if (!isset($_SESSION['cliente'])) {
    header("Location: login_cliente.php");
    exit;
}

// Se ainda não existir um carrinho, cria um
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Se o usuário enviou um produto via POST (vindo da página de compras)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idProd'])) {
    $idProd = $_POST['idProd'];
    $quantidade = $_POST['quantidade'];

    // Se o produto já estiver no carrinho, soma a quantidade
    if (isset($_SESSION['carrinho'][$idProd])) {
        $_SESSION['carrinho'][$idProd] += $quantidade;
    } else {
        $_SESSION['carrinho'][$idProd] = $quantidade;
    }

    // Redireciona de volta para evitar reenvio do formulário
    header("Location: carrinho.php");
    exit;
}

// Remover item do carrinho
if (isset($_GET['remover'])) {
    $idRemover = $_GET['remover'];
    unset($_SESSION['carrinho'][$idRemover]);
    header("Location: carrinho.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Seu Carrinho</title>
</head>
<body>

<div class="top-links">
    <a href="compras.php" class="btn">⬅ Continuar Comprando</a>
    <a href="../index.php" class="btn">🏠 Página Inicial</a>
</div>

<h2>🛍 Seu Carrinho</h2>

<?php
if (empty($_SESSION['carrinho'])) {
    echo "<p>Seu carrinho está vazio.</p>";
} else {
    echo "<table>";
    echo "<tr><th>Produto</th><th>Imagem</th><th>Preço</th><th>Qtd</th><th>Total</th><th>Ação</th></tr>";

    $totalGeral = 0;

    foreach ($_SESSION['carrinho'] as $idProd => $qtd) {
        $sql = "SELECT * FROM produtos WHERE idProd = $idProd";
        $res = $conn->query($sql);
        $p = $res->fetch_assoc();

        $subtotal = $p['preco'] * $qtd;
        $totalGeral += $subtotal;

        echo "<tr>";
        echo "<td>{$p['nome']}</td>";
        echo "<td><img src='../{$p['fotos']}' width='80'></td>";
        echo "<td>R$ " . number_format($p['preco'], 2, ',', '.') . "</td>";
        echo "<td>{$qtd}</td>";
        echo "<td>R$ " . number_format($subtotal, 2, ',', '.') . "</td>";
        echo "<td><a href='?remover=$idProd' class='btn'>Remover</a></td>";
        echo "</tr>";
    }

    echo "<tr><td colspan='4'><strong>Total Geral:</strong></td><td colspan='2'><strong>R$ " . number_format($totalGeral, 2, ',', '.') . "</strong></td></tr>";
    echo "</table>";
    echo "<br><a href='finalizar_pedido.php' class='btn'>Finalizar Pedido</a>";
}
?>

</body>
</html>
