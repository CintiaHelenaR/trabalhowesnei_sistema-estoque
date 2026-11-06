<?php
$servidor = "sql208.infinityfree.com";
$usuario = "if0_43368965";
$senha = "ipMU6m22Su";
$banco = "if0_43368965_sistema_estoque";

$conn = new mysqli($servidor, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro ao conectar ao banco de dados.");
}

$conn->set_charset("utf8mb4");
?>