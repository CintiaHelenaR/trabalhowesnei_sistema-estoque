
<?php

$db_servidor = "sql208.infinityfree.com";
$db_usuario = "if0_43368965";
$db_senha = "YI04QNPXfL";
$db_banco = "if0_43368965_sistema_estoque";

$conn = new mysqli(
    $db_servidor,
    $db_usuario,
    $db_senha,
    $db_banco
);

if ($conn->connect_error) {
    die("Erro ao conectar ao banco de dados.");
}

$conn->set_charset("utf8mb4");
?>
