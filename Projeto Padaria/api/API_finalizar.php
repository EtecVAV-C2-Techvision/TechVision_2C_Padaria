<?php
header("Content-Type: application/json");
include("../conexao.php");

$token = $_POST["token"] ?? "";
$carrinho = $_POST["carrinho"] ?? ""; // JSON string

// valida usuário
$check = $conn->prepare("SELECT idCli FROM clientes WHERE token=?");
$check->bind_param("s",$token);
$check->execute();
$res = $check->get_result();
if ($res->num_rows == 0) { 
    echo json_encode(["status"=>"ERRO"]); 
    exit; 
}

$idCli = $res->fetch_assoc()["idCli"];

// cria pedido
$data = date("Y-m-d H:i:s");
$stmt = $conn->prepare("INSERT INTO pedidos (idCli, data_pedido) VALUES (?, ?)");
$stmt->bind_param("is", $idCli, $data);
$stmt->execute();
$idPedido = $stmt->insert_id;

// itens
$items = json_decode($carrinho, true);

foreach ($items as $i) {
    $idProd = $i["idProd"];
    $qtd = $i["quantidade"];

    $q = $conn->prepare("SELECT preco, quantidade FROM produtos WHERE idProd=?");
    $q->bind_param("i", $idProd);
    $q->execute();
    $r = $q->get_result()->fetch_assoc();

    $preco = $r["preco"];
    $novoEst = $r["quantidade"] - $qtd;

    // salva item
    $ins = $conn->prepare("INSERT INTO itens_pedido (idPedido,idProd,quantidade,preco_unitario)
                           VALUES (?,?,?,?)");
    $ins->bind_param("iiid", $idPedido, $idProd, $qtd, $preco);
    $ins->execute();

    // atualiza estoque
    $up = $conn->prepare("UPDATE produtos SET quantidade=? WHERE idProd=?");
    $up->bind_param("ii", $novoEst, $idProd);
    $up->execute();
}

// ---------------------------------------------
// CALCULAR O TOTAL DO PEDIDO (NOVO BLOCO)
// ---------------------------------------------
$sql_total = "SELECT SUM(quantidade * preco_unitario) AS total 
              FROM itens_pedido 
              WHERE idPedido = ?";
$stmt_total = $conn->prepare($sql_total);
$stmt_total->bind_param("i", $idPedido);
$stmt_total->execute();
$result_total = $stmt_total->get_result()->fetch_assoc();
$total_pedido = $result_total["total"] ?? 0;

// Atualiza o total na tabela pedidos
$sql_update_total = "UPDATE pedidos SET total = ? WHERE idPedido = ?";
$stmt_update_total = $conn->prepare($sql_update_total);
$stmt_update_total->bind_param("di", $total_pedido, $idPedido);
$stmt_update_total->execute();
// ---------------------------------------------

echo json_encode([
    "status" => "OK",
    "idPedido" => $idPedido,
    "total" => $total_pedido  // opcional, mas útil para o app
]);
?>
