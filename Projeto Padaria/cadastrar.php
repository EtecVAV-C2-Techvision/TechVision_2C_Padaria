<?php
include "proteger.php";
if ($_SESSION['funcao'] != 'gerente') {
    die("Acesso negado.");
}
?>

<?php
include "conexao.php";

function validarSenha($senha) {
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/', $senha);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $funcao = $_POST['funcao'];
    
    if (!validarSenha($senha)) {
        die("<p style='color:red;'>Senha Inválida! Deve ter entre 8 e 16 caracteres, com letras maiúsculas, minúsculas e números.</p>");
    }

    $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO funcionarios (usuario, senha, nome_completo, email, funcao) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $usuario, $senhaCriptografada, $nome, $email, $funcao);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Funcionário cadastrado com sucesso.</p>";
    } else {
        echo "<p style='color:red;'>Erro: " . $stmt->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Funcionários</title>
    <link rel="stylesheet" href="estetica.css">

</head>
<body>

    <a href='dashboard.php'>Voltar</a>

    <h2>Cadastrar Funcionários</h2>

    <form method="POST">
        <input type="text" name="usuario" placeholder="Usuário" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <input type="text" name="nome" placeholder="Nome completo" required>
        <input type="email" name="email" placeholder="Email" required>
        <select name="funcao" required>
            <option value="gerente">Gerente</option>
            <option value="funcionario">Funcionário</option>
            <option value="repositor">Repositor</option>
        </select>
        <button type="submit">Cadastrar</button>
        <p>A senha deve ter entre 8 e 16 caracteres, com letras maiúsculas, minúsculas e números.</p>
    </form>

</body>
</html>
