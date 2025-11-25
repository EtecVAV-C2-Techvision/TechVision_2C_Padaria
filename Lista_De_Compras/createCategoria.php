<?php
require "proteger.php"; // agora usa o sistema de token

header("Content-Type: text/plain; charset=utf-8");

$conn = new mysqli("localhost", "root", "", "dbcompras");
if ($conn->connect_error) {
    echo "ERRO_CON";
    exit;
}

$nomeCat = $_GET["nomeCat"] ?? "";

if ($nomeCat == "") {
    echo "CAMPOS_VAZIOS";
    exit;
}

$nomeCat = mysqli_real_escape_string($conn, $nomeCat);

$sql = "INSERT INTO tbcategorias (nomeCat) VALUES ('$nomeCat')";

if ($conn->query($sql) === TRUE) {
    echo "OK";
} else {
    echo "ERRO";
}

$conn->close();
?>
