<?php
session_start();
include('../conexao.php');

// Se o cliente já estiver logado, vai direto para compras
if (isset($_SESSION['cliente'])) {
    header("Location: compras.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome_completo']);
    $email = trim($_POST['email']);
    $usuario = trim($_POST['usuario']);
    $senha = $_POST['senha'];
    $confirma = $_POST['confirma'];
    $endereco = trim($_POST['endereco']);
    $telefone = trim($_POST['telefone']);

    if ($senha !== $confirma) {
        $erro = "As senhas não coincidem.";
    } else {
        $sql = "SELECT * FROM clientes WHERE usuario = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $usuario, $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $erro = "Usuário ou e-mail já cadastrados.";
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "INSERT INTO clientes (nome_completo, email, usuario, senha, endereco, telefone)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $nome, $email, $usuario, $senha_hash, $endereco, $telefone);

            if ($stmt->execute()) {
                // Login automático após cadastro
                $_SESSION['cliente'] = [
                    'idCli' => $conn->insert_id,
                    'usuario' => $usuario,
                    'nome' => $nome
                ];
                header("Location: compras.php");
                exit;
            } else {
                $erro = "Erro ao cadastrar. Tente novamente.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Cadastro de Cliente</title>
</head>
<body>
<h2>Cadastro de Cliente</h2>

<?php
if (isset($erro)) echo "<p style='color:red;'>$erro</p>";
?>

<form method="post">
    <label>Nome completo:</label><br>
    <input type="text" name="nome_completo" required><br><br>

    <label>E-mail:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Usuário:</label><br>
    <input type="text" name="usuario" required><br><br>

    <label>Senha:</label><br>
    <input type="password" name="senha" required><br><br>

    <label>Confirmar senha:</label><br>
    <input type="password" name="confirma" required><br><br>

    <label>Endereço:</label><br>
    <input type="text" name="endereco"><br><br>

    <label>Telefone:</label><br>
    <input type="text" name="telefone"><br><br>

    <button type="submit">Cadastrar</button>
</form>

<p>Já tem conta? <a href="login_cliente.php">Faça login</a></p>
<p><a href="../index.php">⬅ Voltar à página inicial</a></p>
</body>
</html>
