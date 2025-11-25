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

<style>
    body {
        font-family: "Segoe UI", Arial, sans-serif;
        background: #fff7e0; /* tom suave combinando com a paleta */
        margin: 0;
        padding: 0;
    }

    .container {
        width: 100%;
        max-width: 450px;
        margin: 50px auto;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        padding: 30px;
        border-top: 10px solid #ffcb45;
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 25px;
    }

    label {
        font-weight: bold;
        color: #444;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        margin-bottom: 18px;
        border: 2px solid #f0e3b0;
        border-radius: 8px;
        font-size: 15px;
        transition: 0.2s;
        background: #fffef8;
    }

    input:focus {
        border-color: #ffcb45;
        box-shadow: 0 0 5px rgba(255,203,69,0.5);
    }

    button {
        width: 100%;
        padding: 14px;
        background: #ffcb45;
        color: #333;
        font-size: 17px;
        font-weight: bold;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.2s;
    }

    button:hover {
        background: #f8d447;
    }

    .msg-erro {
        color: red;
        text-align: center;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .links {
        text-align: center;
        margin-top: 18px;
        font-size: 15px;
    }

    .links a {
        color: #d39a00;
        font-weight: bold;
        text-decoration: none;
    }

    .links a:hover {
        text-decoration: underline;
    }
</style>

</head>
<body>

<div class="container">
<h2>Cadastro de Cliente</h2>

<?php
if (isset($erro)) echo "<p class='msg-erro'>$erro</p>";
?>

<form method="post">
    <label>Nome completo:</label>
    <input type="text" name="nome_completo" required>

    <label>E-mail:</label>
    <input type="email" name="email" required>

    <label>Usuário:</label>
    <input type="text" name="usuario" required>

    <label>Senha:</label>
    <input type="password" name="senha" required>

    <label>Confirmar senha:</label>
    <input type="password" name="confirma" required>

    <label>Endereço:</label>
    <input type="text" name="endereco">

    <label>Telefone:</label>
    <input type="text" name="telefone">

    <button type="submit">Cadastrar</button>
</form>

<div class="links">
    Já tem conta? <a href="login_cliente.php">Faça login</a><br><br>
    <a href="../index.php">⬅ Voltar à página inicial</a>
</div>

</div>

</body>
</html>

</html>
