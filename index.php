
<?php
require_once "verificar_sessao.php";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Estoque</title>
    <link rel="stylesheet" href="estilo.css?v=2">
</head>
<body>
    <!--Cabeçalho do site-->
    <header>
        <h1>Controle de Estoque</h1>
        <p>Organize seus produtos de maneira simples e eficiente</p>
    </header>

    <nav aria-label="Principal">
        <a href="index.php" aria-current="page">Início</a>
        <a href="listar.php">Produtos</a>
        <a href="cadastro.php">Cadastrar produto</a>

        <!-- Saída segura da conta -->
        <form id="formLogout" action="logout.php" method="POST" hidden>
            <input type="hidden" name="csrf_token"
                value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
        </form>

        <a href="logout.php" onclick="document.getElementById('formLogout').submit(); return false;">Sair</a>
    </nav>

    <!--Conteúdo principal-->
    <main>
        <section>
            <h2>Visão geral</h2>
            <p>Bem-vindo(a), <?php echo htmlspecialchars($_SESSION["usuario_nome"] ?? "Usuário", ENT_QUOTES, "UTF-8"); ?>!</p>
            <p>Aqui você pode cadastrar, consultar e gerenciar seus produtos.</p>
        </section>

        <!--Informações do estoque-->
        <section class="resumo" aria-label="Resumo do estoque">
            <div class="indicador">
                <h3>Produtos cadastrados</h3>
                <p id="totalProdutos">0</p>
            </div>
            <div class="indicador">
                <h3>Produtos disponíveis</h3>
                <p id="totalUnidades">0</p>
            </div>
        </section>

        <!--Ações principais-->
        <section>
            <h2>Gerenciar estoque</h2>
            <div class="acoes">
                <a href="listar.php" class="botao">Visualizar produtos</a>
                <a href="cadastro.php" class="botao botao-secundario">Cadastrar produto</a>
            </div>
        </section>
    </main>

    <!--Rodapé-->
    <footer>
        <p>Projeto desenvolvido por Cintia, Iago, Maria Eduarda Cruz e Sara.</p>
    </footer>

    <script src="resumo_inicio.js"></script>
</body>
</html>
