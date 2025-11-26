<?php
ob_clean();
header('Content-Type: application/json; charset=utf-8');

$conn = new mysqli("localhost", "root", "", "dbcompras");
if ($conn->connect_error) { 
    echo "NEGADO";
    exit;
}

$token = $_GET["token"] ?? "";

if ($token == "") {
    echo "NEGADO";
    exit;
}

$sql = "SELECT id FROM usuarios WHERE token='$token' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "NEGADO";
    exit;
}

$conn = new mysqli("localhost", "root", "", "dbcompras");
if ($conn->connect_error) { 
    echo json_encode([]);
    exit;
}

if (isset($_GET["idCat"])) {
    $idCat = intval($_GET["idCat"]);
    $sql = "SELECT * FROM tbcompras WHERE idCat = $idCat ORDER BY nome ASC";
} else {
    $sql = "SELECT * FROM tbcompras ORDER BY nome ASC";
}

$result = $conn->query($sql);

$lista = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $lista[] = $row;
    }
}

echo json_encode($lista);
$conn->close();
?>
