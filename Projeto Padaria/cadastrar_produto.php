<?php
include "proteger.php";
include "conexao.php";

if ($_SESSION['funcao'] != 'gerente') {
    exit("Acesso negado.");
}

$result_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $categoria = $_POST['categoria'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];

    // Verifica se uma imagem foi enviada
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

        // Extensões permitidas
        $extensoesPermitidas = ["jpg", "jpeg", "png", "gif"];

        if (!in_array($extensao, $extensoesPermitidas)) {
            $result_msg = "<p style='color:red;'>Erro: a imagem deve estar nos formatos JPG, JPEG, PNG ou GIF.</p>";
        } else {
            $pasta = "imagens/";
            if (!is_dir($pasta)) mkdir($pasta, 0777, true);

            // Gera nome único com a extensão correta
            $nomeArquivo = uniqid() . "." . $extensao;
            $caminho = $pasta . $nomeArquivo;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminho)) {
                $stmt = $conn->prepare("INSERT INTO produtos (nome, categoria, preco, quantidade, fotos) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssdis", $nome, $categoria, $preco, $quantidade, $caminho);
                $stmt->execute();
                $result_msg = "<p style='color:green;'>Produto cadastrado com sucesso com imagem!</p>";
            } else {
                $result_msg = "<p style='color:red;'>Erro ao salvar a imagem.</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produtos</title>
    <link rel="stylesheet" href="estetica.css">

</head>
<body>

    <a href='lista_produtos.php'>Voltar</a>

    <h2>Cadastrar Produtos</h2>

    <?php if ($result_msg) echo "<div class='msg'>{$result_msg}</div>"; ?>

    <form method="post" enctype="multipart/form-data">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required>

        <label for="categoria">Categoria:</label>
        <input type="text" name="categoria" id="categoria" required>

        <label for="preco">Preço:</label>
        <input type="number" step="0.01" name="preco" id="preco" required>

        <label for="quantidade">Quantidade Inicial:</label>
        <input type="number" name="quantidade" id="quantidade" required>

        <label for="foto">Imagem do Produto:</label>
        <input type="file" name="foto" id="foto" accept=".jpg,.jpeg,.png,.gif">
        <small>Apenas imagens JPG, JPEG, PNG ou GIF</small>

        <input type="submit" value="Cadastrar Produto">
    </form>

</body>
</html>
