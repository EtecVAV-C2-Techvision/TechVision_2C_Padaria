<?php
header("Content-Type: text/plain; charset=utf-8");
session_start();

$conn = new mysqli("localhost", "root", "", "dbcompras");
if ($conn->connect_error) { 
    echo "ERRO_CON";
    exit;
}

$email = $_GET["email"] ?? "";
$senha = $_GET["senha"] ?? "";

if ($email == "" || $senha == "") {
    echo "CAMPOS_VAZIOS";
    exit;
}

$email = mysqli_real_escape_string($conn, $email);

$sql = "SELECT * FROM usuarios WHERE email='$email' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows == 1) {

    $user = $result->fetch_assoc();

    if (password_verify($senha, $user["senha"])) {

        $token = bin2hex(random_bytes(25));
        $idUser = $user["id"];
        $conn->query("UPDATE usuarios SET token='$token' WHERE id=$idUser");

        echo "TOKEN:$token";

    } else {
        echo "SENHA_ERRADA";
    }

} else {
    echo "EMAIL_NAO_ENCONTRADO";
}

$conn->close();
?>
