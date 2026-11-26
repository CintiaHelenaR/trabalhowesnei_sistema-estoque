
async function atualizarResumo() {
    try {
        // Buscar os produtos do usuário conectado
        const resposta = await fetch("buscar_produtos.php", {
            cache: "no-store"
        });

        // Verificar se a sessão expirou
        if (resposta.status === 401) {
            alert("Sua sessão expirou. Entre novamente.");
            window.location.href = "login.php";
            return;
        }

        if (!resposta.ok) {
            throw new Error("Erro ao consultar produtos.");
        }

        const produtos = await resposta.json();

        // Atualizar o total de produtos
        document.getElementById("totalProdutos").textContent =
            produtos.length;

        // Calcular o total de unidades em estoque
        const unidades = produtos.reduce(
            (soma, produto) => soma + Number(produto.quantidade), 0
        );

        document.getElementById("totalUnidades").textContent =
            unidades;

    } catch (erro) {
        console.error("Erro ao atualizar o resumo:", erro);

        document.getElementById("totalProdutos").textContent = "0";
        document.getElementById("totalUnidades").textContent = "0";
    }
}

// Atualizar o resumo ao abrir a página inicial
atualizarResumo();
