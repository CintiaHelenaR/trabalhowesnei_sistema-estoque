
<?php
require_once "verificar_sessao.php";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto</title>
    <link rel="stylesheet" href="estilo.css?v=2">
</head>
<body>
    <header>
        <h1>Cadastrar produto</h1>
        <p>Adicione um novo item ao seu estoque.</p>
    </header>

    <nav aria-label="Principal">
        <a href="index.php">Início</a>
        <a href="listar.php">Produtos</a>
        <a href="cadastro.php" aria-current="page">Cadastrar produto</a>

        <!-- Saída segura da conta -->
        <form id="formLogout" action="logout.php" method="POST" hidden>
            <input type="hidden" name="csrf_token"
                value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
        </form>

        <a href="logout.php" onclick="document.getElementById('formLogout').submit(); return false;">Sair</a>
    </nav>

    <main>
        <section>
            <h2>Dados do produto</h2>
            <p>Todos os campos são obrigatórios.</p>

            <form id="formCadastro" class="formulario" action="salvar.php" method="POST">

                <!-- Token de segurança CSRF -->
                <input type="hidden" name="csrf_token"
                    value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

                <div class="campo">
                    <label for="nome">Nome do produto</label>
                    <input type="text" id="nome" name="nome" placeholder="Ex.: Caneta esferográfica" required>
                </div>

                <div class="campo">
                    <label for="categoria">Categoria</label>
                    <input type="text" id="categoria" name="categoria" placeholder="Ex.: Papelaria" required>
                </div>

                <div class="campo campo-meio">
                    <label for="quantidade">Quantidade</label>
                    <input type="number" id="quantidade" name="quantidade" min="0" placeholder="0" required>
                </div>

                <div class="campo campo-meio">
                    <label for="preco">Preço (R$)</label>
                    <input type="number" id="preco" name="preco" min="0" step="0.01" placeholder="0,00" required>
                </div>

                <div class="acoes-form">
                    <button type="submit" class="botao">Cadastrar produto</button>
                    <a href="index.php" class="botao botao-secundario">Voltar ao início</a>
                </div>
            </form>
        </section>
    </main>

    <footer>
        <p>Projeto desenvolvido por Cintia, Iago, Maria Eduarda Cruz e Sara.</p>
    </footer>
</body>
</html>
