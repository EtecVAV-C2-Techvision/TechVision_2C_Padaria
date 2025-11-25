<?php
require "proteger.php";

$conn = new mysqli("localhost", "root", "", "dbcompras");
if ($conn->connect_error) {
    die("Falha de conexão: " . $conn->connect_error);
}

$nome = $_GET['nome'];
$quantidade = $_GET['quantidade'];
$idCat = isset($_GET["idCat"]) ? intval($_GET["idCat"]) : 1;

$sql = "INSERT INTO tbcompras (nome, quantidade, idCat)
        VALUES ('$nome', '$quantidade', $idCat)";

if ($conn->query($sql) === TRUE) echo "OK";
else echo "ERRO";

$conn->close();
?>
