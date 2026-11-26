
<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate");

// Gerar o token de segurança
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Redirecionar caso o usuário já esteja conectado
if (isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Controle de Estoque</title>
    <link rel="stylesheet" href="estilo.css?v=4">
</head>
<body>

    <header>
        <h1>Controle de Estoque</h1>
        <p>Organize seus produtos de maneira simples e eficiente</p>
    </header>

    <main class="pagina-autenticacao">
        <section class="card-autenticacao">

            <h2>Bem-vindo(a)!</h2>
            <p>Entre na sua conta para gerenciar seu estoque.</p>

            <?php if (($_GET["erro"] ?? "") === "1"): ?>
                <div class="mensagem-erro">
                    E-mail ou senha incorretos. Tente novamente.
                </div>

            <?php elseif (($_GET["erro"] ?? "") === "2"): ?>
                <div class="mensagem-erro">
                    Não foi possível entrar. Tente novamente mais tarde.
                </div>
            <?php endif; ?>

            <?php if (($_GET["cadastro"] ?? "") === "sucesso"): ?>
                <div class="mensagem-sucesso">
                    Conta criada com sucesso! Faça seu login.
                </div>
            <?php endif; ?>

            <form class="formulario formulario-autenticacao"
                  action="autenticar.php" method="POST">

                <!-- Token de segurança CSRF -->
                <input type="hidden" name="csrf_token"
                    value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

                <div class="campo">
                    <label for="email">E-mail</label>
                    <input type="email"
                           id="email"
                           name="email"
                           placeholder="Digite seu e-mail"
                           autocomplete="email"
                           required>
                </div>

                <div class="campo">
                    <label for="senha">Senha</label>
                    <input type="password"
                           id="senha"
                           name="senha"
                           placeholder="Digite sua senha"
                           autocomplete="off"
                           required>
                </div>

                <div class="acoes-form">
                    <button type="submit" class="botao">
                        Entrar
                    </button>
                </div>

            </form>

            <div class="link-autenticacao">
                <p>Ainda não possui uma conta?</p>
                <a href="cadastro_usuario.php"
                   class="botao botao-secundario">
                    Criar conta
                </a>
            </div>

        </section>
    </main>

    <footer>
        <p>Projeto desenvolvido por Cintia, Iago, Maria Eduarda Cruz e Sara.</p>
    </footer>

</body>
</html>
