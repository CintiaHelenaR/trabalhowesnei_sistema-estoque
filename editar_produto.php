
<?php
session_start();

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

// Verificar autenticação
if (!isset($_SESSION["usuario_id"])) {
    http_response_code(401);
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Usuário não autenticado."
    ]);
    exit;
}

// Verificar método
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Método não permitido."
    ]);
    exit;
}

// Validar token
$token = $_POST["csrf_token"] ?? "";

if (empty($token)) {
    http_response_code(403);
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Token não recebido pelo servidor."
    ]);
    exit;
}

if (
    !is_string($token) ||
    empty($_SESSION["csrf_token"]) ||
    !hash_equals($_SESSION["csrf_token"], $token)
) {
    http_response_code(403);
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Token inválido ou sessão expirada."
    ]);
    exit;
}

try {
    require_once "conexao.php";

    $usuario_id = (int) $_SESSION["usuario_id"];

    $id = filter_input(
        INPUT_POST,
        "id",
        FILTER_VALIDATE_INT
    );

    $nome = trim($_POST["nome"] ?? "");
    $categoria = trim($_POST["categoria"] ?? "");

    $quantidade = filter_input(
        INPUT_POST,
        "quantidade",
        FILTER_VALIDATE_INT
    );

    $precoTexto = str_replace(
        ",",
        ".",
        $_POST["preco"] ?? ""
    );

    $preco = filter_var(
        $precoTexto,
        FILTER_VALIDATE_FLOAT
    );

    // Validar os dados
    if (
        $id === false ||
        $id === null ||
        $id <= 0 ||
        $nome === "" ||
        $categoria === "" ||
        strlen($nome) > 100 ||
        strlen($categoria) > 100 ||
        $quantidade === false ||
        $quantidade === null ||
        $quantidade < 0 ||
        $preco === false ||
        $preco < 0
    ) {
        http_response_code(400);
        echo json_encode([
            "sucesso" => false,
            "mensagem" => "Preencha os dados corretamente."
        ]);
        exit;
    }

    // Verificar proprietário do produto
    $verificar = $conn->prepare(
        "SELECT id FROM produtos
         WHERE id = ? AND usuario_id = ?"
    );

    $verificar->bind_param("ii", $id, $usuario_id);
    $verificar->execute();
    $verificar->store_result();

    if ($verificar->num_rows === 0) {
        $verificar->close();

        http_response_code(404);
        echo json_encode([
            "sucesso" => false,
            "mensagem" => "Produto não encontrado no seu estoque."
        ]);
        exit;
    }

    $verificar->close();

    // Atualizar produto
    $stmt = $conn->prepare(
        "UPDATE produtos
         SET nome = ?, categoria = ?, quantidade = ?, preco = ?
         WHERE id = ? AND usuario_id = ?"
    );

    $stmt->bind_param(
        "ssidii",
        $nome,
        $categoria,
        $quantidade,
        $preco,
        $id,
        $usuario_id
    );

    if (!$stmt->execute()) {
        throw new Exception("Erro ao atualizar produto.");
    }

    $stmt->close();
    $conn->close();

    echo json_encode([
        "sucesso" => true,
        "mensagem" => "Produto atualizado com sucesso!"
    ]);

} catch (Throwable $erro) {
    http_response_code(500);

    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Erro interno ao atualizar produto."
    ]);
}
