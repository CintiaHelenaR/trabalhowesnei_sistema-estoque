
<?php
session_start();
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
    <title>Criar Conta - Controle de Estoque</title>
    <link rel="stylesheet" href="estilo.css?v=4">
</head>
<body>

    <header>
        <h1>Controle de Estoque</h1>
        <p>Organize seus produtos de maneira simples e eficiente</p>
    </header>

    <main class="pagina-autenticacao">
        <section class="card-autenticacao">

            <h2>Criar minha conta</h2>
            <p>Cadastre-se para começar a gerenciar seu próprio estoque.</p>
<?php if (($_GET["erro"] ?? "") === "1"): ?>
    <div class="mensagem-erro">
        Dados inválidos. Verifique os campos preenchidos.
    </div>

<?php elseif (($_GET["erro"] ?? "") === "2"): ?>
    <div class="mensagem-erro">
        Este e-mail já está cadastrado.
    </div>

<?php elseif (($_GET["erro"] ?? "") === "3"): ?>
    <div class="mensagem-erro">
        Não foi possível criar a conta. Tente novamente.
    </div>
<?php endif; ?>
            <form class="formulario formulario-autenticacao"
                  action="salvar_usuario.php" method="POST">
<input type="hidden" name="csrf_token"
    value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                <div class="campo">
                    <label for="nome">Nome completo</label>
                    <input type="text"
                           id="nome"
                           name="nome"
                           placeholder="Digite seu nome"
                           maxlength="100"
                           autocomplete="name"
                           required>
                </div>

                <div class="campo">
                    <label for="email">E-mail</label>
                    <input type="email"
                           id="email"
                           name="email"
                           placeholder="Digite seu e-mail"
                           maxlength="150"
                           autocomplete="email"
                           required>
                </div>

                <div class="campo">
                    <label for="senha">Senha</label>
                    <input type="password"
                           id="senha"
                           name="senha"
                           placeholder="Mínimo de 8 caracteres"
                           minlength="8"
                           autocomplete="new-password"
                           required>
                </div>

                <div class="acoes-form">
                    <button type="submit" class="botao">
                        Criar conta
                    </button>
                </div>

            </form>

            <div class="link-autenticacao">
                <p>Já possui uma conta?</p>

                <a href="login.php"
                   class="botao botao-secundario">
                    Fazer login
                </a>
            </div>

        </section>
    </main>

    <footer>
        <p>Projeto desenvolvido por Cintia, Iago, Maria Eduarda Cruz e Sara.</p>
    </footer>

</body>
</html>
