<?php
$avaliacao_id = decrypt($_GET['origem']);
$avaliacao = retornarUmObjeto("SELECT * FROM avaliacao WHERE id = {$avaliacao_id}");

if ($avaliacao['realizada'] !== null) {
    exit("<script>window.location.href = \"index.php?pagina=avaliacoes&status=error&msg=Avaliação já foi realizada e não pode ser editada.\";</script>");
}

if (!$avaliacao) {
    include('404.php');
} else {
    $perguntas_id = !empty($avaliacao['perguntas'])
    ? array_map('intval', explode(",", $avaliacao['perguntas']))
    : [];
    $total_perguntas = count($perguntas_id);
    $data = new DateTime($avaliacao['liberar']);
    $dl = $data->format('d/m/Y');

    if($avaliacao['quem_avalia'] === 'padrinho') {
        $quem_avalia = [
            'nome' => retornarUmValor("SELECT nome FROM padrinhos WHERE id = {$avaliacao['padrinho_id']}"),
            'foto' => getFotoPadrinho($avaliacao['padrinho_id']),
            'label' => 'Padrinho'
        ];

        $avaliado = [
            'nome' => retornarUmValor("SELECT nome FROM afilhados WHERE id = {$avaliacao['afilhado_id']}"),
            'foto' => getFotoAfilhado($avaliacao['afilhado_id']),
            'label' => 'Afilhado'
        ];
    } else {
        $quem_avalia = [
            'nome' => retornarUmValor("SELECT nome FROM afilhados WHERE id = {$avaliacao['afilhado_id']}"),
            'foto' => getFotoAfilhado($avaliacao['afilhado_id']),
            'label' => 'Afilhado'
        ];

        $avaliado = [
            'nome' => retornarUmValor("SELECT nome FROM padrinhos WHERE id = {$avaliacao['padrinho_id']}"),
            'foto' => getFotoPadrinho($avaliacao['padrinho_id']),
            'label' => 'Padrinho'
        ];
    }

    $modo = $_GET['modo'] ?? null;
    $perfil = $_GET['perfil'] ?? null;
    $categoria = $_GET['categoria'] ?? null;
    $filtro = '';

    if ($modo) {
        $filtro .= " AND modo = '{$modo}'";
    }
    if ($perfil) {
        $filtro .= " AND perfil = '{$perfil}'";
    }
    if ($categoria) {
        $filtro .= " AND categoria = '{$categoria}'";
    }

    $perguntas_banco = retornarLista("SELECT * FROM perguntas WHERE id > 0{$filtro}");
?>
<div class="flex w-full p-2 justify-end">
    <a href="index.php?pagina=excluir-avaliacao&origem=<?= encrypt($avaliacao_id) ?>" class="btn btn-sm btn-error">
        <i class="fa fa-times" aria-hidden="true"></i> Excluir avaliação
    </a>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">

    <!-- 🟦 FORMULÁRIO -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title">Editar Avaliação</h2>
            <form method="POST" action="index.php?pagina=atualizar-data-avaliacao" accept-charset="UTF-8" enctype="multipart/form-data">
                <input type="hidden" name="avaliacao_id" value="<?= encrypt($avaliacao_id); ?>">
                <div class="form-control mt-4">
                    <div class="flex flex-col gap-1">
                        <input type="date" name="liberar" value="<?= $avaliacao['liberar']; ?>" class="input input-bordered w-full">
                        <span class="text-xs text-gray-500">Data para liberar a avaliação</span>
                    </div>
                </div>
                <div class="mt-4">
                <button type="submit" class="btn btn-neutral w-full">Atualizar data</button>
                </div>
            </form>
            <div class="divider"></div>

            <form id="filtros">
                <div class="flex gap-4">
                    <div class="flex flex-col">
                        <div class="flex items-start"><label class="label"><input name="modo" value="multipla-escolha" type="checkbox" class="checkbox checkbox-sm mr-1" <?= $modo === 'multipla-escolha' ? 'checked' : '' ?>> <badge class="badge badge-info badge-sm">multipla-escolha</badge></label></div>
                        <div class="flex items-start"><label class="label"><input name="modo" value="dissertativa" type="checkbox" class="checkbox checkbox-sm mr-1" <?= $modo === 'dissertativa' ? 'checked' : '' ?>> <badge class="badge badge-info badge-sm">dissertativa</badge></label></div>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-start"><label class="label"><input name="perfil" value="padrinho" type="checkbox" class="checkbox checkbox-sm mr-1" <?= $perfil === 'padrinho' ? 'checked' : '' ?>> <badge class="badge badge-accent badge-sm">padrinho</badge></label></div>
                        <div class="flex items-start"><label class="label"><input name="perfil" value="afilhado" type="checkbox" class="checkbox checkbox-sm mr-1" <?= $perfil === 'afilhado' ? 'checked' : '' ?>> <badge class="badge badge-accent badge-sm">afilhado</badge></label></div>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-start"><label class="label"><input name="categoria" value="comportamental" type="checkbox" class="checkbox checkbox-sm mr-1" <?= $categoria === 'comportamental' ? 'checked' : '' ?>> <badge class="badge badge-neutral badge-sm">comportamental</badge></label></div>
                        <div class="flex items-start"><label class="label"><input name="categoria" value="tecnica" type="checkbox" class="checkbox checkbox-sm mr-1" <?= $categoria === 'tecnica' ? 'checked' : '' ?>> <badge class="badge badge-neutral badge-sm">tecnica</badge></label></div>
                    </div>
                    <div class="flex justify-end flex-grow">
                        <button type="button" id="btnFiltrar" class="btn w-14 h-14 rounded-full mt-2">Filtrar</button>
                    </div>
                </div>
            </form>
            <script>
                document.getElementById('btnFiltrar').addEventListener('click', () => {
                    
                    const checkboxes = document.querySelectorAll('#filtros input[type="checkbox"]');
                    
                    let grupos = {};

                    // agrupar por name
                    checkboxes.forEach(cb => {
                        if (!grupos[cb.name]) {
                            grupos[cb.name] = [];
                        }

                        if (cb.checked) {
                            grupos[cb.name].push(cb.value);
                        }
                    });

                    let params = new URLSearchParams();

                    // regra: só envia se tiver exatamente 1 selecionado
                    for (let grupo in grupos) {
                        if (grupos[grupo].length === 1) {
                            params.append(grupo, grupos[grupo][0]);
                        }
                    }

                    // redireciona
                    window.location.href = 'index.php?pagina=editar-avaliacao&origem=<?= encrypt($avaliacao_id) ?>&' + params.toString();
                });
                </script>
            <div class="mt-2 ml-1">
                <span class="text-xs font-semibold text-base-400">PERGUNTAS ENCONTRADAS NO BANCO DE DADOS: <?= count($perguntas_banco) ?></span>
            </div>
            <div class="overflow-y-auto" style="height: 600px !important;">
                <?php foreach ($perguntas_banco as $index => $pergunta_banco): ?>
                <div class="flex flex-col bg-gray-100 rounded-lg p-4 mb-2 relative">
                    <a href="index.php?pagina=adicionar-pergunta-na-avaliacao&origem=<?= encrypt($avaliacao_id) ?>&pergunta=<?= encrypt($pergunta_banco['id']) ?>" class="h-8 w-8 rounded-full bg-accent text-center py-1 absolute right-2 top-2"><i class="fa fa-plus mt-1"></i></a>
                    <div class="flex gap-2">
                        <span class="font-bold text-sm w-6 h-6 rounded-full bg-neutral text-white flex items-center justify-center"><?= $pergunta_banco['id']; ?></span>
                        <badge class="badge badge-neutral badge-sm mt-1"><?= $pergunta_banco['modo']; ?></badge>
                        <badge class="badge badge-neutral badge-sm mt-1"><?= $pergunta_banco['perfil']; ?></badge>
                        <badge class="badge badge-neutral badge-sm mt-1"><?= $pergunta_banco['categoria']; ?></badge>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm mt-2"><?= $pergunta_banco['pergunta']; ?></span>
                        <span class="text-xs mt-1 ml-4"><?= $pergunta_banco['descricao']; ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>


    <!-- 🟩 DADOS ANTIGOS -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex w-full">
                <div class="card grid w-1/2 place-items-center">
                    <div class="avatar indicator">
                        <span class="indicator-item badge badge-xs badge-neutral"><?= $quem_avalia['label'] ?></span>
                        <div class="w-24 rounded-full"><img src="<?= $quem_avalia['foto'] ?>" /></div>
                    </div>
                    <span class="text-wrap"><?= $quem_avalia['nome'] ?></span>
                </div>
                <div class="divider divider-horizontal">COM</div>
                <div class="card grid w-1/2 place-items-center">
                    <div class="avatar indicator">
                        <span class="indicator-item badge badge-xs badge-neutral"><?= $avaliado['label'] ?></span>
                        <div class="w-24 rounded-full"><img src="<?= $avaliado['foto'] ?>" /></div>
                    </div>
                    <span class="text-wrap"><?= $avaliado['nome'] ?></span>
                </div>
            </div>
            <div class="divider"></div>
            <span class="text-sm text-center">PERGUNTAS CADASTRADAS NESSA AVALIAÇÃO</span>
            <div class="flex justify-between mt-1">
                <span class="text-sm text-gray-500 mt-2">Data de liberação: <span class="text-sm ml-2 font-bold"><?= $dl; ?></span></span>
                <span class="text-sm text-gray-500 mt-2">Total de perguntas: <span class="text-sm ml-2 font-bold"><?= $total_perguntas; ?></span></span>
            </div>
            <div class="flex justify-between mt-1">
                <span class="text-sm text-gray-500 mt-2">Status: <span class="text-sm ml-2 font-bold">
                    <?php 
                        if ($avaliacao['liberar'] > date('Y-m-d')) {
                            echo "<span class='badge badge-xs font-semibold badge-neutral p-2'>Bloqueada</span>";
                        } else if ($avaliacao['liberar'] <= date('Y-m-d')) {
                            echo "<span class='badge badge-xs font-semibold badge-warning p-2'>Pendente</span>";
                        } else {
                            echo "<span class='badge badge-xs font-semibold badge-success p-2'>Realizada</span>";
                        }
                    ?>
                </span></span>
            </div>
            <div class="overflow-y-auto" style="height: 550px !important;">
                <?php
                    foreach ($perguntas_id as $index => $pergunta_id) {
                        $pergunta = retornarUmObjeto("SELECT * FROM perguntas WHERE id = {$pergunta_id}");
                        if ($pergunta) { ?>
                        <div class="flex flex-col bg-gray-100 rounded-lg p-4 mb-2 relative">
                            <a href="index.php?pagina=remover-pergunta-na-avaliacao&origem=<?= encrypt($avaliacao_id) ?>&pergunta=<?= encrypt($pergunta['id']) ?>" class="h-8 w-8 rounded-full bg-error text-center py-1 absolute right-2 top-2"><i class="fa fa-minus mt-1"></i></a>
                            <div class="flex gap-2">
                                <span class="font-bold text-sm w-6 h-6 rounded-full bg-neutral text-white flex items-center justify-center"><?= $pergunta['id']; ?></span>
                                <badge class="badge badge-neutral badge-sm mt-1"><?= $pergunta['modo']; ?></badge>
                                <badge class="badge badge-neutral badge-sm mt-1"><?= $pergunta['perfil']; ?></badge>
                                <badge class="badge badge-neutral badge-sm mt-1"><?= $pergunta['categoria']; ?></badge>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm mt-2"><?= $pergunta['pergunta']; ?></span>
                                <span class="text-xs mt-1 ml-4"><?= $pergunta['descricao']; ?></span>
                            </div>
                        </div>
                            
                        <?php
                        }
                    }
                ?>
            </div>
        </div>
    </div> 

</div>
<?php } ?>