<?php
include('proteger_cliente.php');
include('../conexao.php');

if (isset($_SESSION['cliente'])) {
    $perfilLink = 'perfil_cliente.php';
} else {
    $perfilLink = 'login_cliente.php';
}




$result = $conn->query("SELECT * FROM produtos WHERE quantidade > 0");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<style>
    .btn-perfil {
  position: fixed;
  top: 12px;
  right: 12px;
  z-index: 1000;
}

.btn-perfil a {
  background-color: #ff914d;
  color: white;
  padding: 8px 14px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: bold;
  font-family: Arial, sans-serif;
  transition: 0.2s;
}

.btn-perfil a:hover {
  background-color: #ff7a20;
}

</style>
<meta charset="UTF-8">
<title>Compras</title>
</head>
<body>
    <div class="btn-perfil">
  <a href="<?= $perfilLink ?>">Meu Perfil</a>
</div>
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
