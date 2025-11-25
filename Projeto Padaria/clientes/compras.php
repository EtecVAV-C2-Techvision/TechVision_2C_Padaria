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
<meta charset="UTF-8">
<title>Compras</title>

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
        margin-bottom: 15px;
    }

    /* BOTÃO PERFIL */
    .btn-perfil {
        position: fixed;
        top: 12px;
        right: 12px;
        z-index: 1000;
    }

    .btn-perfil a {
        background-color: #ffcb45;
        color: #333;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        transition: 0.2s;
        border: 2px solid #f8d447;
    }

    .btn-perfil a:hover {
        background-color: #f8d447;
    }

    /* LINKS SUPERIORES */
    .top-links {
        text-align: center;
        margin-top: 50px;
        margin-bottom: 20px;
    }

    .top-links a {
        color: #d39a00;
        font-weight: bold;
        font-size: 15px;
        margin: 0 5px;
        text-decoration: none;
    }

    .top-links a:hover {
        text-decoration: underline;
    }

    hr {
        border: 0;
        height: 2px;
        background: #ffcb45;
        margin: 25px 0;
    }

    /* GRID DE PRODUTOS */
    .produtos {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
        gap: 20px;
        padding: 10px;
    }

    /* CARD DO PRODUTO */
    .produto {
        background: #fff;
        border-radius: 12px;
        padding: 15px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        text-align: center;
        border-top: 5px solid #ffcb45;
        transition: 0.2s;
    }

    .produto:hover {
        transform: scale(1.03);
        box-shadow: 0 0 12px rgba(0,0,0,0.15);
    }

    .produto img {
        border-radius: 8px;
        border: 2px solid #f8d447;
        max-width: 100%;
    }

    strong {
        font-size: 17px;
        color: #333;
    }

    .produto p {
        margin: 4px 0;
    }

    /* FORMULÁRIO DE ADIÇÃO */
    .produto form {
        margin-top: 8px;
    }

    input[type="number"] {
        width: 70px;
        padding: 5px;
        border: 2px solid #f0e3b0;
        border-radius: 6px;
        font-size: 14px;
        text-align: center;
        background: #fffef8;
        transition: 0.2s;
    }

    input[type="number"]:focus {
        border-color: #ffcb45;
        box-shadow: 0 0 4px rgba(255,203,69,0.5);
    }

    button {
        background: #ffcb45;
        border: none;
        padding: 8px 12px;
        margin-top: 6px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        transition: 0.2s;
        border: 2px solid #f8d447;
        color: #333;
    }

    button:hover {
        background: #f8d447;
    }
</style>
</head>

<body>

<div class="btn-perfil">
  <a href="<?= $perfilLink ?>">Meu Perfil</a>
</div>

<h2>Produtos disponíveis</h2>

<div class="top-links">
    <p>Bem-vindo(a), <strong><?= $_SESSION['cliente']['nome']; ?></strong>!</p>

    <a href="carrinho.php">🛍 Ver carrinho</a> |
    <a href="logout_cliente.php">Sair</a> |
    <a href="../index.php">⬅ Página inicial</a>
</div>

<hr>

<div class="produtos">
<?php while($p = $result->fetch_assoc()): ?>
    
    <div class="produto">
        <img src="../<?= $p['fotos']; ?>" alt="<?= $p['nome']; ?>">

        <p><strong><?= $p['nome']; ?></strong></p>
        <p>Categoria: <?= $p['categoria']; ?></p>
        <p>Preço: R$ <?= number_format($p['preco'], 2, ',', '.'); ?></p>

        <form action="carrinho.php" method="post">
            <input type="hidden" name="idProd" value="<?= $p['idProd']; ?>">
            <input type="number" name="quantidade" min="1" max="<?= $p['quantidade']; ?>" value="1" required>
            <button type="submit">Adicionar ao carrinho</button>
        </form>
    </div>

<?php endwhile; ?>
</div>

</body>
</html>

</html>
