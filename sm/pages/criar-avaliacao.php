<?php
$afilhados = GlobalModel::retornarUmaLista("SELECT id, nome FROM afilhados ORDER BY nome ASC");
$padrinhos = GlobalModel::retornarUmaLista("SELECT id, nome FROM padrinhos ORDER BY nome ASC");
?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">
    <!-- 🟦 FORMULÁRIO -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title">Criar Avaliação</h2>

            <form method="POST" action="index.php?pagina=salvar-nova-avaliacao" accept-charset="UTF-8" enctype="multipart/form-data">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend font-bold">Selecione quem vai avaliar:</legend>
                    <select name="quem_avalia" class="select select-bordered w-full">
                        <option value="padrinho">Padrinho</option>
                        <option value="afilhado">Afilhado</option>
                    </select>
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend font-bold">Selecione um afilhado:</legend>
                    <select name="afilhado_id" class="select select-bordered w-full">
                        <?php foreach ($afilhados as $afilhado): ?>
                            <option value="<?= $afilhado['id'] ?>"><?= $afilhado['nome'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend font-bold">Selecione um padrinho:</legend>
                    <select name="padrinho_id" class="select select-bordered w-full">
                        <?php foreach ($padrinhos as $padrinho): ?>
                            <option value="<?= $padrinho['id'] ?>"><?= $padrinho['nome'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend font-bold">Data de liberação:</legend>
                    <div class="flex flex-col gap-1">
                        <input type="date" name="liberar" value="<?= date('Y-m-d') ?>" class="input input-bordered w-full">
                        <span class="text-xs text-gray-500">Defina uma data para liberar a avaliação</span>
                    </div>
                </fieldset>

                <div class="mt-4">
                <button class="btn btn-neutral w-full">Salvar</button>
                </div>

            </form>
        </div>
    </div>

</div>