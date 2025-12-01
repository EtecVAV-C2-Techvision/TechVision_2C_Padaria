<?php
header("Content-Type: application/json");
include("../conexao.php");

$token = $_GET["token"] ?? "";
$idPedido = $_GET["idPedido"] ?? 0;

$check = $conn->prepare("SELECT idCli FROM clientes WHERE token=?");
$check->bind_param("s",$token);
$check->execute();
$res = $check->get_result();
if ($res->num_rows == 0) { echo json_encode(["status"=>"NEGADO"]); exit; }

$idCli = $res->fetch_assoc()["idCli"];

// verifica dono
$ver = $conn->prepare("SELECT * FROM pedidos WHERE idPedido=? AND idCli=?");
$ver->bind_param("ii",$idPedido,$idCli);
$ver->execute();
$pedido = $ver->get_result()->fetch_assoc();

if (!$pedido) {
    echo json_encode(["status"=>"NEGADO"]);
    exit;
}

// itens
$sql = "
SELECT i.*, p.nome, p.fotos
FROM itens_pedido i
JOIN produtos p ON p.idProd = i.idProd
WHERE idPedido = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$idPedido);
$stmt->execute();
$resItens = $stmt->get_result();

$itens = [];
while ($r = $resItens->fetch_assoc()) $itens[] = $r;

echo json_encode([
    "status"=>"OK",
    "pedido"=>$pedido,
    "itens"=>$itens
]);
?>
