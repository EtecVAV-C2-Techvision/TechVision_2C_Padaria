<?php
header("Content-Type: text/plain; charset=utf-8");
include("../conexao.php");

$nome = $_GET["nome"] ?? "";
$email = $_GET["email"] ?? "";
$usuario = $_GET["usuario"] ?? "";
$senha = $_GET["senha"] ?? "";
$endereco = $_GET["endereco"] ?? "";
$telefone = $_GET["telefone"] ?? "";

if ($nome == "" || $email == "" || $usuario == "" || $senha == "") {
    echo "CAMPOS_VAZIOS";
    exit;
}

$nome  = mysqli_real_escape_string($conn, $nome);
$usuario = mysqli_real_escape_string($conn, $usuario);
$email = mysqli_real_escape_string($conn, $email);


$verifica = $conn->query("SELECT idCli FROM clientes WHERE usuario='$usuario' LIMIT 1");

if ($verifica->num_rows > 0) {
    echo "USER_EXISTE";
    exit;
}

$hash = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO clientes (nome_completo,email,usuario,senha,endereco,telefone) VALUES ('$nome', '$email', '$usuario', '$hash', '$endereco', '$telefone')";

if ($conn->query($sql)) {

    $idNovo = $conn->insert_id;
    $token = hash("sha256", uniqid() . rand() . microtime());
    $conn->query("UPDATE clientes SET token='$token' WHERE idCli=$idNovo");
    echo "OK|" . $token . "|" . $nome;

} else {
    echo "ERRO_INSERT";
}

$conn->close();
?>

