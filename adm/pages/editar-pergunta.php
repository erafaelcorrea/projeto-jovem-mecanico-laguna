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
$map = array_column($categorias_perguntas, 'label', 'tag');
if (!$pergunta->id) {
    include('404.php');
} else {
?>
<div class="w-full p-4">
    <a href="index.php?pagina=bem-vindo" class="btn btn-neutral btn-sm">VOLTAR <i class="fa fa-mail-reply"></i></a>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">

    <!-- 🟦 FORMULÁRIO -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex gap-2 p-2">
                <div class="text-xs">TIPO: <span class="badge badge-neutral text-xs font-semibold"><?= $pergunta->modo ?></span></div>
                <div class="text-xs">CATEGORIA: <span class="badge badge-accent text-xs font-semibold"><?= $map[$pergunta->categoria] ?></span></div>
                <div class="text-xs">QUEM RESPONDE: <span class="badge badge-warning text-xs font-semibold"><?= $pergunta->perfil ?></span></div>
            </div>
            <form method="POST" action="index.php?pagina=salvar-editar-pergunta" accept-charset="UTF-8" enctype="multipart/form-data">
                <input type="hidden" name="pergunta_id" value="<?= $pergunta_id; ?>">
                <input type="hidden" name="modo" value="<?= $pergunta->modo; ?>">
                <input type="hidden" name="perfil" value="<?= $pergunta->perfil; ?>">
                <input type="hidden" name="categoria" value="<?= $pergunta->categoria; ?>">
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

                <div class="mt-4">
                    <button class="btn btn-neutral w-full">Salvar</button>
                </div>
            </form>
        </div>
    </div>


</div>
<?php } ?>

