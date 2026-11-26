
<?php
require_once "verificar_sessao.php";

// Verificar se os dados foram enviados pelo formulário
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: cadastro.php");
    exit;
}

// Validar o token de segurança CSRF
$token = $_POST["csrf_token"] ?? "";

if (
    !is_string($token) ||
    empty($_SESSION["csrf_token"]) ||
    !hash_equals($_SESSION["csrf_token"], $token)
) {
    http_response_code(403);
    exit("Solicitação inválida. Volte à página de cadastro e tente novamente.");
}

require_once "conexao.php";

// Identificar o usuário conectado
$usuario_id = (int) $_SESSION["usuario_id"];

// Receber os dados do formulário
$nome = trim($_POST['nome'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');

$quantidade = filter_input(
    INPUT_POST,
    'quantidade',
    FILTER_VALIDATE_INT
);

$preco = filter_input(
    INPUT_POST,
    'preco',
    FILTER_VALIDATE_FLOAT
);

// Verificar se os dados são válidos
if (
    $nome === '' ||
    $categoria === '' ||
    $quantidade === false ||
    $quantidade === null ||
    $quantidade < 0 ||
    $preco === false ||
    $preco === null ||
    $preco < 0
) {
    exit("Preencha os campos corretamente.");
}

// Inserir produto associado ao usuário no banco de dados
$sql = "INSERT INTO produtos
        (nome, categoria, quantidade, preco, usuario_id)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssidi",
    $nome,
    $categoria,
    $quantidade,
    $preco,
    $usuario_id
);

// Executar o cadastro
if ($stmt->execute()) {
    echo "<script>
        alert('Produto cadastrado com sucesso no seu estoque!');
        window.location.href = 'cadastro.php';
    </script>";
} else {
    echo "Erro ao cadastrar produto.";
}

$stmt->close();
$conn->close();
?>
