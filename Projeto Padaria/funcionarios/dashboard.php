<?php
include "proteger.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="estetica.css">

</head>
<body>

    <div class="welcome">
        Bem-vindo, <?= $_SESSION['usuario'] ?>
    </div>

    <div class="buttons">
        <?php if ($_SESSION['funcao'] == 'gerente'): ?>
            <a href='cadastrar.php'>Cadastrar Funcionário</a>
            <a href='listar_funcionarios.php'>Gerenciar Funcionários</a>
        <?php endif; ?>
        <?php if ($_SESSION['funcao'] == 'gerente' || $_SESSION['funcao'] == 'entregador' ): ?>
            <a href='gerenciar_pedidos.php'>Gerenciar Pedidos</a>
        <?php endif; ?>
        <?php if ($_SESSION['funcao'] != 'entregador'): ?>
            <a href='lista_produtos.php'>Ver Lista de Produtos</a>
        <?php endif; ?>

        <a href='alterar_senha.php'>Alterar Minha Senha</a>
        <a href='logout.php'>Sair</a>
    </div>

</body>
</html>
