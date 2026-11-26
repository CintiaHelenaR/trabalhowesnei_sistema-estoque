
<?php
session_start();

// Verificar o método da requisição
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: cadastro_usuario.php");
    exit;
}

// Impedir cadastro caso já esteja conectado
if (isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
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
    exit("Solicitação inválida. Volte ao cadastro e tente novamente.");
}
// Receber os dados
$nome = $_POST["nome"] ?? "";
$email = $_POST["email"] ?? "";
$senha = $_POST["senha"] ?? "";

// Verificar os tipos dos dados
if (
    !is_string($nome) ||
    !is_string($email) ||
    !is_string($senha)
) {
    header("Location: cadastro_usuario.php?erro=1");
    exit;
}

$nome = trim($nome);
$email = trim($email);

// Validar os campos
if (
    $nome === "" ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    strlen($senha) < 8 ||
    strlen($senha) > 72 ||
    strlen($nome) > 100 ||
    strlen($email) > 150
) {
    header("Location: cadastro_usuario.php?erro=1");
    exit;
}

try {
    require_once "conexao.php";

    // Verificar se o e-mail já está cadastrado
    $stmt = $conn->prepare(
        "SELECT id FROM usuarios WHERE email = ?"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        $conn->close();

        header("Location: cadastro_usuario.php?erro=2");
        exit;
    }

    $stmt->close();

    // Proteger a senha antes de armazenar
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Cadastrar o novo usuário
    $stmt = $conn->prepare(
        "INSERT INTO usuarios (nome, email, senha)
         VALUES (?, ?, ?)"
    );

    $stmt->bind_param("sss", $nome, $email, $senhaHash);

    if (!$stmt->execute()) {
        throw new Exception("Erro ao criar conta.");
    }

    $stmt->close();
    $conn->close();

    // Redirecionar para o login
    header("Location: login.php?cadastro=sucesso");
    exit;

} catch (Throwable $erro) {

    if ((int) $erro->getCode() === 1062) {
        header("Location: cadastro_usuario.php?erro=2");
        exit;
    }

    header("Location: cadastro_usuario.php?erro=3");
    exit;
}
