
<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

// Verificar se o usuário está conectado
if (!isset($_SESSION["usuario_id"])) {
    http_response_code(401);
    echo json_encode(["erro" => "Usuário não autenticado."]);
    exit;
}

require_once "conexao.php";

// Identificar o usuário conectado
$usuario_id = (int) $_SESSION["usuario_id"];

// Buscar somente os produtos do usuário
$sql = "SELECT id, nome, categoria, quantidade, preco
        FROM produtos
        WHERE usuario_id = ?
        ORDER BY id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$resultado = $stmt->get_result();

$produtos = [];

while ($linha = $resultado->fetch_assoc()) {
    $produtos[] = $linha;
}

// Enviar os produtos em formato JSON
echo json_encode($produtos);

$stmt->close();
$conn->close();
?>
