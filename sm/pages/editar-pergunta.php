<?php
$pergunta_id = decrypt($_GET['origem']);
$pergunta = GlobalModel::retornarUmObjeto("SELECT * FROM perguntas WHERE id = {$pergunta_id}");
 $categorias_perguntas = [
    ["tag" => "pontualidade", "label" => "Pontualidade"],
    ["tag" => "organizacao-limpeza", "label" => "Organização e Limpeza"],
    ["tag" => "produtividade", "label" => "Produtividade"],
    ["tag" => "proatividade", "label" => "Proatividade"],
    ["tag" => "conhecimento", "label" => "Conhecimento"],
    ["tag" => "seguranca", "label" => "Segurança"],
    ["tag" => "habilidade", "label" => "Habilidade"],
    ["tag" => "desempenho", "label" => "Desempenho"],
];

if (!$pergunta->id) {
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
                    <textarea name="pergunta" class="textarea h-24 w-full textarea-bordered border"><?= $pergunta->pergunta ?></textarea>
                    <div class="label text-sm">Pergunta principal.</div>
                </fieldset>

                <fieldset class="fieldset mt-4">
                    <legend class="fieldset-legend font-bold">Descrição</legend>
                    <textarea name="descricao" class="textarea h-24 w-full textarea-bordered border"><?= $pergunta->descricao ?></textarea>
                    <div class="label text-sm">Descrição da pergunta ou texto ajuda da pergunta.</div>
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Modo da Pergunta:</legend>
                    <select name="modo" class="select select-bordered w-full">
                        <option value="multipla-escolha" <?= $pergunta->modo === 'multipla-escolha' ? 'selected' : '' ?>>Multipla Escolha</option>
                        <option value="dissertativa" <?= $pergunta->modo === 'dissertativa' ? 'selected' : '' ?>>Dissertativa</option>
                    </select>
                    <div class="label text-sm">Modelo de pergunta</div>
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Perfil:</legend>
                    <select name="perfil" class="select select-bordered w-full">
                        <option value="padrinho" <?= $pergunta->perfil === 'padrinho' ? 'selected' : '' ?>>Padrinho</option>
                        <option value="afilhado" <?= $pergunta->perfil === 'afilhado' ? 'selected' : '' ?>>Afilhado</option>
                        <option value="instrutor" <?= $pergunta->perfil === 'instrutor' ? 'selected' : '' ?>>Instrutor</option>
                    </select>
                    <div class="label text-sm">Quem vai responder a pergunta</div>
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Categoria:</legend>
                    <select id="categoria" name="categoria" class="select select-bordered w-full">
                        <?php foreach ($categorias_perguntas as $cat_p): ?>
                        <option value="<?= $cat_p['tag'] ?>" <?= $pergunta->categoria === $cat_p['tag'] ? 'selected' : '' ?>><?= $cat_p['label'] ?></option>
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


</div>
<?php } ?>

