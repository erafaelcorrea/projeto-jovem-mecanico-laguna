<?php
$pergunta_id = decrypt($_GET['origem']);
$pergunta = retornarUmObjeto("SELECT * FROM perguntas WHERE id = {$pergunta_id}");
if (!$pergunta) {
    include('404.php');
} else {
?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">

    <!-- 🟦 FORMULÁRIO -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title">Editar Pergunta</h2>
            <form method="POST" action="index.php?pagina=salvar-editar-pergunta" accept-charset="UTF-8" enctype="multipart/form-data">
                <input type="hidden" name="pergunta_id" value="<?= $pergunta_id; ?>">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend font-bold">Pergunta</legend>
                    <textarea name="pergunta" class="textarea h-24 w-full textarea-bordered border"><?= $pergunta['pergunta'] ?></textarea>
                    <div class="label text-sm">Pergunta principal.</div>
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend font-bold">Descrição</legend>
                    <textarea name="descricao" class="textarea h-24 w-full textarea-bordered border"><?= $pergunta['descricao'] ?></textarea>
                    <div class="label text-sm">Descrição da pergunta ou texto ajuda da pergunta.</div>
                </fieldset>
                <div class="form-control">
                    <label class="label">Categoria</label>
                    <select name="modo" class="select select-bordered">
                        <option value="multipla-escolha" <?= $pergunta['modo'] === 'multipla-escolha' ? 'selected' : '' ?>>Multipla Escolha</option>
                        <option value="dissertativa" <?= $pergunta['modo'] === 'dissertativa' ? 'selected' : '' ?>>Dissertativa</option>
                    </select>
                </div> 
                <div class="form-control"> 
                    <label class="label">Perfil</label>
                    <select name="perfil" class="select select-bordered">
                        <option value="padrinho" <?= $pergunta['perfil'] === 'padrinho' ? 'selected' : '' ?>>Padrinho</option>
                        <option value="afilhado" <?= $pergunta['perfil'] === 'afilhado' ? 'selected' : '' ?>>Afilhado</option>
                        <option value="todos" <?= $pergunta['perfil'] === 'todos' ? 'selected' : '' ?>>Todos</option>
                    </select>
                </div>
                <div class="form-control"> 
                    <label class="label">Categoria</label>
                    <select id="categoria" name="categoria" class="select select-bordered">
                        <option value="comportamental" <?= $pergunta['categoria'] === 'comportamental' ? 'selected' : '' ?>>Comportamental</option>
                        <option value="tecnica" <?= $pergunta['categoria'] === 'tecnica' ? 'selected' : '' ?>>Técnica</option>
                    </select>
                </div>
                <div class="form-control"> 
                    <label class="label">Sub-Categoria</label>
                    <select id="subcategoria" name="subcategoria" class="select select-bordered">
                        <option value="">Selecione uma sub-categoria</option>
                    </select>
                </div>

                <div class="mt-4">
                    <button class="btn btn-neutral w-full">Salvar</button>
                </div>
            </form>
        </div>
    </div>


    <!-- 🟩 DADOS ANTIGOS -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <?php if ($pergunta['modo'] == 'multipla-escolha'): ?>  
                <span class="text-2xl text-gray-300">PERGUNTA MULTIPLA ESCOLHA #</span> 
                <fieldset class="fieldset mt-4">
                    <legend class="fieldset-legend font-semibold"><?= $pergunta['pergunta'] ?></legend>
                    <span class="label text-sm italic mt-2"><?= $pergunta['descricao'] ?></span>
                    <div class="flex justify-between gap-3 p-2 mb-2 mt-2">
                    <div class="flex flex-1 flex-col items-center gap-2">
                        <input type="radio" name="resposta" value="1"  class="radio radio-error" /> 
                        <span class="text-xs font-semibold">PÉSSIMO</span>
                    </div>
                    <div class="flex flex-1 flex-col items-center gap-2">
                        <input type="radio" name="resposta" value="2" class="radio radio-warning" />
                        <span class="text-xs font-semibold">RUIM</span>
                    </div>
                    <div class="flex flex-1 flex-col items-center gap-2">
                        <input type="radio" name="resposta" value="3" class="radio radio-info" />
                        <span class="text-xs font-semibold">BOM</span>
                    </div>
                    <div class="flex flex-1 flex-col items-center gap-2">
                        <input type="radio" name="resposta" value="4" class="radio radio-accent" />
                        <span class="text-xs font-semibold">ÓTIMO</span>
                    </div>
                    <div class="flex flex-1 flex-col items-center gap-2">
                        <input type="radio" name="resposta" value="5" checked="checked" class="radio radio-success" />
                        <span class="text-xs font-semibold">EXCELENTE</span>
                    </div>
                    </div>
                </fieldset> 
            <?php else: ?>
                <span class="text-2xl text-gray-300">PERGUNTA DISSERTATIVA #</span>  
                <fieldset class="fieldset mt-4">
                    <legend class="fieldset-legend font-semibold"><?= $pergunta['pergunta'] ?></legend>
                    <textarea class="textarea h-24 w-full textarea-bordered border"></textarea>
                    <div class="label text-sm"><?= $pergunta['descricao'] ?></div>
                </fieldset> 
            <?php endif; ?>
        </div>
    </div>

</div>
<script>
    const subcategorias = {
        comportamental: [
            { value: "pontualidade", label: "Pontualidade" },
            { value: "organizacao_limpeza", label: "Organização e Limpeza" },
            { value: "proatividade", label: "Proatividade" }
        ],
        tecnica: [
            { value: "conhecimento", label: "Conhecimento" },
            { value: "seguranca", label: "Segurança" },
            { value: "habilidade", label: "Habilidade" }
        ]
    };

    const categoriaSelect = document.getElementById("categoria");
    const subcategoriaSelect = document.getElementById("subcategoria");

    function carregarSubcategorias(categoriaSelecionada, subSelecionada = null) {
        subcategoriaSelect.innerHTML = "";

        if (categoriaSelecionada && subcategorias[categoriaSelecionada]) {
            subcategorias[categoriaSelecionada].forEach(function (sub) {
                const option = document.createElement("option");

                option.value = sub.value;
                option.textContent = sub.label;

                if (subSelecionada && sub.value === subSelecionada) {
                    option.selected = true;
                }

                subcategoriaSelect.appendChild(option);
            });
        }
    }

    // Quando trocar categoria, limpa seleção anterior
    categoriaSelect.addEventListener("change", function () {
        carregarSubcategorias(this.value);
    });

    // 🚀 Carrega com dados do banco
    window.addEventListener("DOMContentLoaded", function () {
        const categoriaAtual = categoriaSelect.value;

        // exemplo se vier do PHP:
        const subSelecionada = '<?= $pergunta['subcategoria'] ?>'; // ex: "proatividade"

        carregarSubcategorias(categoriaAtual, subSelecionada);
    });
</script>

<?php } ?>

