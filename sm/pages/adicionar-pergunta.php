<?php
    $categorias_perguntas = [
        ["tag" => "pontualidade", "label" => "Pontualidade"],
        ["tag" => "organizacao-limpeza", "label" => "Organização e Limpeza"],
        ["tag" => "produtividade", "label" => "Produtividade"],
        ["tag" => "proatividade", "label" => "Proatividade"],
        ["tag" => "conhecimento", "label" => "Conhecimento"],
        ["tag" => "seguranca", "label" => "Segurança"],
        ["tag" => "habilidade", "label" => "Habilidade"],
        ["tag" => "desempenho", "label" => "Desempenho"],
    ]
?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">
    <!-- 🟦 FORMULÁRIO -->
    <div class="card bg-base-100 shadow-xl"> 
        <div class="card-body">
            <h2 class="card-title">Adicionar Pergunta</h2>
            <form method="POST" action="index.php?pagina=salvar-pergunta" accept-charset="UTF-8" enctype="multipart/form-data">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend font-bold">Pergunta</legend>
                    <textarea name="pergunta" class="textarea h-24 w-full textarea-bordered border"></textarea>
                    <div class="label text-sm">Pergunta principal.</div>
                </fieldset>

                <fieldset class="fieldset mt-4">
                    <legend class="fieldset-legend font-bold">Descrição</legend>
                    <textarea name="descricao" class="textarea h-24 w-full textarea-bordered border"></textarea>
                    <div class="label text-sm">Descrição da pergunta ou texto ajuda da pergunta.</div>
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Categoria:</legend>
                    <select name="modo" class="select select-bordered w-full">
                        <option value="multipla-escolha">Múltipla Escolha</option>
                        <option value="dissertativa">Dissertativa</option>
                    </select>
                    <div class="label text-sm">Modelo de pergunta</div>
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Perfil:</legend>
                    <select name="perfil" class="select select-bordered w-full">
                        <option value="padrinho">Padrinho</option>
                        <option value="afilhado">Afilhado</option>
                        <option value="instrutor">Instrutor</option>
                    </select>
                    <div class="label text-sm">Quem vai responder a pergunta</div>
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Categoria:</legend>
                    <select id="categoria" name="categoria" class="select select-bordered w-full">
                        <?php foreach ($categorias_perguntas as $cat_p): ?>
                        <option value="<?= $cat_p['tag'] ?>"><?= $cat_p['label'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="label text-sm">Categoria da pergunta</div>
                </fieldset>

                <div class="mt-4">
                    <button class="btn btn-neutral w-full">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <span class="text-2xl text-gray-300">PERGUNTA MULTIPLA ESCOLHA #</span> 
            <fieldset class="fieldset mt-4">
                <legend class="fieldset-legend font-semibold">Pergunta</legend>
                <span class="label text-sm italic mt-2">Descrição da pergunta</span>
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
            <div class="divider">OU</div>
            <span class="text-2xl text-gray-300">PERGUNTA DISSERTATIVA #</span>  
            <fieldset class="fieldset mt-4">
                <legend class="fieldset-legend font-semibold">Pergunta</legend>
                <textarea class="textarea h-24 w-full textarea-bordered border"></textarea>
                <div class="label text-sm">Descrição da pergunta</div>
            </fieldset> 
        </div>
    </div>
</div>