<?php
$afilhado_id = decrypt($_GET['origem']);
$aluno = GlobalModel::RetornarUmObjeto("SELECT id, nome, TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) AS idade, sexo FROM afilhados WHERE id = {$afilhado_id}");
$conteudos = [
      ["PARTICIPAÇÃO"],
      ["ORGANIZAÇÃO"],
      ["TRABALHO EM EQUIPE"],
      ["PONTUALIDADE"],
      ["PROATIVIDADE"],
      ["ASSIDUIDADE"],
      ["CONHECIMENTO TEÓRICO"],
      ["DINÂMICA EM GRUPO"],
      ["DESENVOLVIMENTO TEÓRICO"],
      ["DESENVOLVIMENTO PRÁTICO"]
    ];
$modulos = ["Matemática", "Metrologia", "Lubrificação", "Hidraulica", "Elétrica"];
?>
<div class="avatar indicator">
    <div class="w-24 h-24 rounded-full">
        <img src="<?= getFotoAfilhado($aluno->id) ?>" />
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title">Notas das Provas Aplicadas</h2>
            <?php foreach ($modulos as $modulo): ?>
            <fieldset class="fieldset border-b border-gray-200 mt-8">
                <legend class="fieldset-legend"><?= $modulo ?></legend>
                <div class="join">
                    <input class="input join-item w-50" name="n1" />
                    <button class="btn join-item rounded-r-full">SALVAR</button>
                </div>
                <p class="label mb-4"></p>
            </fieldset>
            <?php endforeach; ?>
        </div>
    </div>
</div>