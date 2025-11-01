<?php
include "proteger.php";
include "conexao.php";

if ($_SESSION['funcao'] != 'gerente') {
    exit("Acesso negado.");
}

$result_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idProd = $_POST['idProd'];
    $preco = $_POST['preco'];

    $stmt = $conn->prepare("UPDATE produtos SET preco=? WHERE idProd=?");
    $stmt->bind_param("di", $preco, $idProd);
    $stmt->execute();

    $result_msg = "<p style='color:green;'>Preço atualizado com sucesso!</p>";
}

$result = $conn->query("SELECT idProd, nome FROM produtos");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Atualizar Preço</title>
        <link rel="stylesheet" href="estetica.css">

</head>
<body>

    <a href='lista_produtos.php'>Voltar</a>

    <h2>Atualizar Preço do Produto</h2>

    <?php if ($result_msg) echo "<div class='msg'>{$result_msg}</div>"; ?>

    <form method="post">
        <label for="idProd">Produto:</label>
        <select name="idProd" id="idProd">
            <?php while ($row = $result->fetch_assoc()): ?>
                <option value="<?= $row['idProd'] ?>"><?= $row['nome'] ?></option>
            <?php endwhile; ?>
        </select>

        <label for="preco">Novo Preço:</label>
        <input type="number" step="0.01" name="preco" id="preco" required>

        <input type="submit" value="Atualizar Preço">
    </form>

</body>
</html>
