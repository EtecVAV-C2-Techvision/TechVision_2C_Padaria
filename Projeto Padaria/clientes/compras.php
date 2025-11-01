<?php
session_start();
include('../conexao.php');

// se o cliente não estiver logado, volta para o login
if (!isset($_SESSION['cliente'])) {
    header("Location: login_cliente.php");
    exit;
}

include('proteger_cliente.php');

$result = $conn->query("SELECT * FROM produtos WHERE quantidade > 0");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Compras</title>
</head>
<body>
<h2>Produtos disponíveis</h2>

<p>Bem-vindo(a), <strong><?php echo $_SESSION['cliente']['nome']; ?></strong>!</p>
<p><a href="carrinho.php">🛍 Ver carrinho</a> | <a href="logout_cliente.php">Sair</a></p>
<p><a href="../index.php">⬅ Voltar à página inicial</a></p>

<hr>

<div class="produtos">
<?php while($p = $result->fetch_assoc()): ?>
  <div class="produto">
    <img src="../<?php echo $p['fotos']; ?>" 
     alt="<?php echo $p['nome']; ?>" 
     width="120">
    <br>
    <strong><?php echo $p['nome']; ?></strong><br>
    Categoria: <?php echo $p['categoria']; ?><br>
    Preço: R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?><br>
    <form action="carrinho.php" method="post">
        <input type="hidden" name="idProd" value="<?php echo $p['idProd']; ?>">
        <input type="number" name="quantidade" min="1" max="<?php echo $p['quantidade']; ?>" value="1" required>
        <button type="submit">Adicionar ao carrinho</button>
    </form>
  </div>
  <hr>
<?php endwhile; ?>
</div>
</body>
</html>
