
<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

// Verificar se o usuário está conectado
if (!isset($_SESSION["usuario_id"])) {
    http_response_code(401);
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Usuário não autenticado."
    ]);
    exit;
}

// Verificar o método da requisição
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Método não permitido."
    ]);
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
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Solicitação inválida. Atualize a página e tente novamente."
    ]);
    exit;
}

try {
    require_once "conexao.php";

    // Identificar o usuário conectado
    $usuario_id = (int) $_SESSION["usuario_id"];

    // Receber o ID do produto
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    // Validar o ID
    if ($id === false || $id === null || $id <= 0) {
        http_response_code(400);
        echo json_encode([
            "sucesso" => false,
            "mensagem" => "ID inválido."
        ]);
        exit;
    }

    // Excluir somente produtos do usuário conectado
    $stmt = $conn->prepare(
        "DELETE FROM produtos
         WHERE id = ? AND usuario_id = ?"
    );

    $stmt->bind_param("ii", $id, $usuario_id);

    if (!$stmt->execute()) {
        throw new Exception("Erro ao excluir produto.");
    }

    // Verificar se algum produto foi excluído
    if ($stmt->affected_rows > 0) {
        echo json_encode([
            "sucesso" => true,
            "mensagem" => "Produto excluído com sucesso!"
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "sucesso" => false,
            "mensagem" => "Produto não encontrado no seu estoque."
        ]);
    }

    $stmt->close();
    $conn->close();

} catch (Throwable $erro) {
    http_response_code(500);
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Erro interno ao excluir produto."
    ]);
}
?>
