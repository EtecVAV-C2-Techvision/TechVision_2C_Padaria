<?php
session_start();
include('../conexao.php');

$usuario = $_POST['usuario'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM funcionarios WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();
    if (password_verify($senha, $usuario['senha'])) {
        $_SESSION['idFunc'] = $usuario['idFunc'];
        $_SESSION['funcao'] = $usuario['funcao'];
        $_SESSION['usuario'] = $usuario['usuario'];
        header("Location: dashboard.php");
    } else {
        echo "Senha incorreta.";
    }
} else {
    echo "Usuário não encontrado.";
}
?>
