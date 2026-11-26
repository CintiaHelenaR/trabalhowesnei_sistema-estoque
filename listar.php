
<?php
require_once "verificar_sessao.php";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

    <title>Produtos Cadastrados</title>
    <link rel="stylesheet" href="estilo.css?v=3">
</head>
<body>
    <header>
        <h1>Produtos cadastrados</h1>
        <p>Confira os produtos disponíveis no seu estoque.</p>
    </header>

    <nav aria-label="Principal">
        <a href="index.php">Início</a>
        <a href="listar.php" aria-current="page">Produtos</a>
        <a href="cadastro.php">Cadastrar produto</a>

        <form id="formLogout" action="logout.php" method="POST" hidden>
            <input type="hidden" name="csrf_token"
                value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
        </form>

        <a href="logout.php"
           onclick="document.getElementById('formLogout').submit(); return false;">
            Sair
        </a>
    </nav>

    <main>
        <section class="resumo" aria-label="Resumo do estoque">
            <div class="indicador">
                <h3>Total de produtos</h3>
                <p id="totalProdutos">0</p>
            </div>
            <div class="indicador">
                <h3>Unidades em estoque</h3>
                <p id="totalUnidades">0</p>
            </div>
        </section>

        <section class="card-tabela">
            <div class="titulo-tabela">
                <div>
                    <h2>Meus produtos</h2>
                    <p>Edite ou exclua os itens registrados na sua conta.</p>
                </div>
                <a href="cadastro.php" class="botao">Cadastrar produto</a>
            </div>

            <div class="tabela-responsiva">
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Categoria</th>
                            <th>Quantidade</th>
                            <th>Preço</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody id="listaProdutos">
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer>
        <p>Projeto desenvolvido por Cintia, Iago, Maria Eduarda Cruz e Sara.</p>
    </footer>

    <script src="listar.js?v=6"></script>
</body>
</html>
