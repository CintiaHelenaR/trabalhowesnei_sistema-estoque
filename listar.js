
const listaProdutos = document.getElementById("listaProdutos");

// Recuperar o token de segurança
function obterToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');

    if (!meta || !meta.content.trim()) {
        throw new Error("Token ausente. Atualize a página.");
    }

    return meta.content.trim();
}

// Verificar a sessão
function verificarSessao(resposta) {
    if (resposta.status === 401) {
        alert("Sua sessão expirou. Entre novamente.");
        window.location.href = "login.php";
        return false;
    }

    return true;
}

// Carregar os produtos
async function carregarProdutos() {
    try {
        const resposta = await fetch("buscar_produtos.php", {
            cache: "no-store",
            credentials: "same-origin"
        });

        if (!verificarSessao(resposta)) return;

        if (!resposta.ok) {
            throw new Error("Erro ao buscar produtos.");
        }

        const produtos = await resposta.json();

        listaProdutos.innerHTML = "";

        document.getElementById("totalProdutos").textContent =
            produtos.length;

        const unidades = produtos.reduce((total, produto) => {
            return total + Number(produto.quantidade);
        }, 0);

        document.getElementById("totalUnidades").textContent =
            unidades;

        if (produtos.length === 0) {
            listaProdutos.innerHTML =
                '<tr><td colspan="5">Nenhum produto cadastrado no seu estoque.</td></tr>';
            return;
        }

        produtos.forEach((produto) => {
            const linha = document.createElement("tr");

            linha.insertCell().textContent = produto.nome;
            linha.insertCell().textContent = produto.categoria;
            linha.insertCell().textContent = produto.quantidade;

            linha.insertCell().textContent =
                "R$ " + Number(produto.preco)
                .toFixed(2).replace(".", ",");

            const acoes = linha.insertCell();

            const editar = document.createElement("button");
            editar.type = "button";
            editar.className = "botao-editar";
            editar.textContent = "Editar";

            editar.addEventListener("click", () => {
                editarProduto(produto);
            });

            const excluir = document.createElement("button");
            excluir.type = "button";
            excluir.className = "botao-excluir";
            excluir.textContent = "Excluir";

            excluir.addEventListener("click", () => {
                excluirProduto(produto.id);
            });

            acoes.append(editar, excluir);
            listaProdutos.appendChild(linha);
        });

    } catch (erro) {
        console.error(erro);

        document.getElementById("totalProdutos").textContent = "0";
        document.getElementById("totalUnidades").textContent = "0";

        listaProdutos.innerHTML =
            '<tr><td colspan="5">Erro ao carregar produtos.</td></tr>';
    }
}

// Enviar dados usando FormData
async function enviarDados(arquivo, dados) {
    dados.set("csrf_token", obterToken());

    const resposta = await fetch(arquivo, {
        method: "POST",
        credentials: "same-origin",
        cache: "no-store",
        body: dados
    });

    if (!verificarSessao(resposta)) {
        return null;
    }

    const resultado = await resposta.json();

    if (!resposta.ok || !resultado.sucesso) {
        throw new Error(
            resultado.mensagem || "Erro na operação."
        );
    }

    return resultado;
}

// Excluir produto
async function excluirProduto(id) {
    if (!confirm("Tem certeza que deseja excluir este produto?")) {
        return;
    }

    try {
        const dados = new FormData();
        dados.set("id", id);

        const resultado = await enviarDados(
            "excluir_produto.php",
            dados
        );

        if (!resultado) return;

        alert(resultado.mensagem);
        await carregarProdutos();

    } catch (erro) {
        alert(erro.message);
    }
}

// Editar produto
async function editarProduto(produto) {
    const nome = prompt("Nome do produto:", produto.nome);
    if (nome === null) return;

    const categoria = prompt("Categoria:", produto.categoria);
    if (categoria === null) return;

    const quantidade = prompt("Quantidade:", produto.quantidade);
    if (quantidade === null) return;

    const preco = prompt("Preço:", produto.preco);
    if (preco === null) return;

    const precoFormatado = preco.trim().replace(",", ".");

    if (
        !nome.trim() ||
        !categoria.trim() ||
        quantidade.trim() === "" ||
        precoFormatado === "" ||
        !Number.isInteger(Number(quantidade)) ||
        Number(quantidade) < 0 ||
        !Number.isFinite(Number(precoFormatado)) ||
        Number(precoFormatado) < 0
    ) {
        alert("Preencha os dados corretamente.");
        return;
    }

    try {
        const dados = new FormData();

        dados.set("id", produto.id);
        dados.set("nome", nome.trim());
        dados.set("categoria", categoria.trim());
        dados.set("quantidade", Number(quantidade));
        dados.set("preco", precoFormatado);

        const resultado = await enviarDados(
            "editar_produto.php",
            dados
        );

        if (!resultado) return;

        alert(resultado.mensagem);
        await carregarProdutos();

    } catch (erro) {
        alert(erro.message);
    }
}

// Carregar os produtos ao abrir a página
carregarProdutos();
