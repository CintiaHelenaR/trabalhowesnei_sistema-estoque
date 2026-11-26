
<?php
require_once "verificar_sessao.php";

// Permitir somente requisições POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Método não permitido.");
}

// Validar o token de segurança CSRF
$token = $_POST["csrf_token"] ?? "";

if (
    !is_string($token) ||
    empty($_SESSION["csrf_token"]) ||
    !hash_equals($_SESSION["csrf_token"], $token)
) {
    http_response_code(403);
    exit("Solicitação inválida.");
}

// Limpar os dados da sessão
$_SESSION = [];

// Remover o cookie da sessão
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Encerrar a sessão
session_destroy();

// Redirecionar para a tela de login
header("Location: login.php");
exit;
