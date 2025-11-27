<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['usuario']) || !isset($_SESSION['funcao'])) {
    header("Location: ../entrar.php");
    exit();
}

$funcoes_permitidas = ['gerente', 'funcionario', 'repositor', 'entregador'];

if (!in_array(strtolower($_SESSION['funcao']), $funcoes_permitidas)) {
    echo "Função de usuário inválida.";
    session_destroy();
    exit();
}
?>
