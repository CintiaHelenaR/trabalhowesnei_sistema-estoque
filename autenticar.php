
<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate");

// Verificar o método da requisição
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

// Remover qualquer autenticação anterior
unset($_SESSION["usuario_id"]);
unset($_SESSION["usuario_nome"]);

// Validar o token de segurança CSRF
$token = $_POST["csrf_token"] ?? "";

if (
    !is_string($token) ||
    empty($_SESSION["csrf_token"]) ||
    !hash_equals($_SESSION["csrf_token"], $token)
) {
    http_response_code(403);
    exit("Solicitação inválida. Volte ao login e tente novamente.");
}

// Receber os dados do formulário
$email = $_POST["email"] ?? "";
$senha = $_POST["senha"] ?? "";

// Validar os tipos dos dados
if (!is_string($email) || !is_string($senha)) {
    header("Location: login.php?erro=1");
    exit;
}

$email = trim($email);

// Validar os campos
if (
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    strlen($email) > 150 ||
    $senha === "" ||
    strlen($senha) > 72
) {
    header("Location: login.php?erro=1");
    exit;
}

try {
    require_once "conexao.php";

    // Buscar o usuário pelo e-mail
    $stmt = $conn->prepare(
        "SELECT id, nome, senha FROM usuarios WHERE email = ?"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();
 

    // Bloquear caso o usuário não exista
    // ou a senha esteja incorreta
    if (!$usuario || !password_verify($senha, $usuario["senha"])) {

        unset($_SESSION["usuario_id"]);
        unset($_SESSION["usuario_nome"]);

        $stmt->close();
        $conn->close();

        header("Location: login.php?erro=1");
        exit;
    }

    // Renovar a sessão após validar a senha
    if (!session_regenerate_id(true)) {
        throw new Exception("Erro ao renovar sessão.");
    }

    $_SESSION = [];

    $_SESSION["usuario_id"] = (int) $usuario["id"];
    $_SESSION["usuario_nome"] = $usuario["nome"];

    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));

    $stmt->close();
    $conn->close();

    session_write_close();

    // Permitir acesso somente após autenticação
    header("Location: index.php");
    exit;

} catch (Throwable $erro) {

    unset($_SESSION["usuario_id"]);
    unset($_SESSION["usuario_nome"]);

    header("Location: login.php?erro=2");
    exit;
}
