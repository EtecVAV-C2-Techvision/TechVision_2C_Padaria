<?php
include "proteger.php";
include('../conexao.php');

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novaSenha = $_POST['senha'];

    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/', $novaSenha)) {
        $msg = "<p style='color:red;'>Senha inválida. Deve conter letras maiúsculas, minúsculas e números (8 a 16 caracteres).</p>";
    } else {
        $senhaCriptografada = password_hash($novaSenha, PASSWORD_DEFAULT);
        $idFunc = $_SESSION['idFunc'];

        $stmt = $conn->prepare("UPDATE funcionarios SET senha = ? WHERE idFunc = ?");
        $stmt->bind_param("si", $senhaCriptografada, $idFunc);

        if ($stmt->execute()) {
            $msg = "<p style='color:green;'>Senha alterada com sucesso!</p>";
        } else {
            $msg = "<p style='color:red;'>Erro ao alterar senha.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Senha</title>
        <link rel="stylesheet" href="estetica.css">

</head>
<body>

    <a href="dashboard.php">Voltar</a>

    <h2>Alterar Senha</h2>

    <?php if ($msg) echo "<div class='msg'>{$msg}</div>"; ?>

    <form method="POST">
        <label for="senha">Nova Senha:</label>
        <input type="password" name="senha" id="senha" placeholder="Digite sua nova senha" required>
        <input type="submit" value="Alterar Senha">
    </form>

</body>
</html>
