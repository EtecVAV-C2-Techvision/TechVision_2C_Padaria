<?php
require "proteger.php"; // agora usa autenticação por token

header("Content-Type: text/plain; charset=utf-8");

$conn = new mysqli("localhost", "root", "", "dbcompras");
if ($conn->connect_error) {
    echo "ERRO_CON";
    exit;
}

$idCat = $_GET["idCat"] ?? "";

if ($idCat == "") {
    echo "CAMPOS_VAZIOS";
    exit;
}

$idCat = intval($idCat);

$sql = "DELETE FROM tbcompras WHERE idCat = $idCat";

if ($conn->query($sql) === TRUE) {
    echo "OK";
} else {
    echo "ERRO";
}

$conn->close();
?>
