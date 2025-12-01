<?php
header("Content-Type: text/plain; charset=utf-8");
session_start();
include("../conexao.php");

$usuario = $_GET["usuario"] ?? "";
$senha = $_GET["senha"] ?? "";

if ($usuario == "" || $senha == "") {
    echo "CAMPOS_VAZIOS";
    exit;
}

$usuario = mysqli_real_escape_string($conn, $usuario);

$sql = "SELECT * FROM clientes WHERE usuario='$usuario' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows == 1) {

    $user = $result->fetch_assoc();

    if (password_verify($senha, $user["senha"])) {

        $token = bin2hex(random_bytes(25));
        $idCli = $user["idCli"];
        $conn->query("UPDATE clientes SET token='$token' WHERE idCli=$idCli");

        echo "TOKEN:$token";

    } else {
        echo "SENHA_ERRADA";
    }

} else {
    echo "EMAIL_NAO_ENCONTRADO";
}

$conn->close();
?>