<?php
include "proteger.php";
include "conexao.php";

if ($_SESSION['funcao'] != 'repositor' && $_SESSION['funcao'] != 'gerente') {
    exit("Acesso negado.");
}

$result_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idProd = $_POST['idProd'];
    $entrada = $_POST['quantidade'];

    if (empty($idProd) || empty($entrada) || !is_numeric($entrada) || $entrada <= 0) {
        $result_msg = "<p style='color:red;'>Erro: selecione um produto e digite uma quantidade válida maior que zero.</p>";
    } else {
        $stmt = $conn->prepare("UPDATE produtos SET quantidade = quantidade + ? WHERE idProd = ?");
        $stmt->bind_param("ii", $entrada, $idProd);
        $stmt->execute();
        $result_msg = "<p style='color:green;'>Entrada registrada com sucesso!</p>";
    }
}

$result = $conn->query("SELECT idProd, nome FROM produtos");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Registrar Entrada de Produtos</title>
    <link rel="stylesheet" href="estetica.css">

</head>
<body>

    <a href='lista_produtos.php'>Voltar</a>

    <h2>Registrar Entrada de Produtos</h2>

    <?php if ($result_msg) echo "<div class='msg'>{$result_msg}</div>"; ?>

    <form method="post">
        <label for="idProd">Produto:</label>
        <select name="idProd" id="idProd" required>
            <option value="">-- Selecione --</option>
            <?php while ($row = $result->fetch_assoc()): ?>
                <option value="<?= $row['idProd'] ?>"><?= $row['nome'] ?></option>
            <?php endwhile; ?>
        </select>

        <label for="quantidade">Quantidade recebida:</label>
        <input type="number" name="quantidade" id="quantidade" min="1" required>

        <input type="submit" value="Registrar Entrada">
    </form>

</body>
</html>
