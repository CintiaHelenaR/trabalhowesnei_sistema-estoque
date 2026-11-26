
<?php

// Iniciar a sessão caso ainda não esteja ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se o usuário está conectado
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

// Evitar o armazenamento de páginas privadas em cache
header("Cache-Control: no-store, no-cache, must-revalidate");

// Gerar um token de segurança CSRF
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}
