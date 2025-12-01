<?php
header("Content-Type: application/json");
include("../conexao.php");

$token = $_GET["token"] ?? "";

if ($token == "") {
    echo json_encode(["status"=>"ERRO"]);
    exit;
}

$check = $conn->prepare("SELECT idCli, nome_completo, email, usuario, endereco, telefone FROM clientes WHERE token=?");
$check->bind_param("s", $token);
$check->execute();
$res = $check->get_result();

if ($res->num_rows == 0) {
    echo json_encode(["status"=>"ERRO"]);
    exit;
}

$cliente = $res->fetch_assoc();

// Buscar pedidos do cliente
$idCliente = $cliente["idCli"];

$sqlPed = $conn->prepare("SELECT idPedido, data_pedido, total FROM pedidos WHERE idCli=? ORDER BY data_pedido DESC");
$sqlPed->bind_param("i", $idCliente);
$sqlPed->execute();
$resPed = $sqlPed->get_result();

$listaPedidos = [];
while ($p = $resPed->fetch_assoc()) {
    $listaPedidos[] = $p;
}

// Montar JSON final
$saida = [
    "status"   => "OK",
    "nome_completo"     => $cliente["nome_completo"],
    "email"    => $cliente["email"],
    "usuario"  => $cliente["usuario"],
    "endereco" => $cliente["endereco"],
    "telefone" => $cliente["telefone"],
    "pedidos"  => $listaPedidos
];

echo json_encode($saida);
?>
