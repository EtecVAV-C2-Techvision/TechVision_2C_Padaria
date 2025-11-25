<?php
header("Content-Type: text/plain; charset=utf-8");

$conn = new mysqli("localhost", "root", "", "dbcompras");
if ($conn->connect_error) {
    echo "ERRO_CON";
    exit;
}

$nome  = $_GET["nome"]  ?? "";
$email = $_GET["email"] ?? "";
$senha = $_GET["senha"] ?? "";

if ($nome == "" || $email == "" || $senha == "") {
    echo "CAMPOS_VAZIOS";
    exit;
}

$nome  = mysqli_real_escape_string($conn, $nome);
$email = mysqli_real_escape_string($conn, $email);

// Verifica se o email já existe
$verifica = $conn->query("SELECT id FROM usuarios WHERE email='$email' LIMIT 1");

if ($verifica->num_rows > 0) {
    echo "EMAIL_EXISTE";
    exit;
}

// Criptografa senha
$hash = password_hash($senha, PASSWORD_DEFAULT);

// Insere novo usuário
$sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$hash')";

if ($conn->query($sql)) {

    // Pega o ID do novo usuário
    $idNovo = $conn->insert_id;

    // 🔥 Gera token automático, igual ao login
    $token = hash("sha256", uniqid() . rand() . microtime());

    // Atualiza o token no usuário
    $conn->query("UPDATE usuarios SET token='$token' WHERE id=$idNovo");

    // Retorna dados necessários
    // Formato: OK|TOKEN|NOME
    echo "OK|" . $token . "|" . $nome;

} else {
    echo "ERRO_INSERT";
}

$conn->close();
?>
