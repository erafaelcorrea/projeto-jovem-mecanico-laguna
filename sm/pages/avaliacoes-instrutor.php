<?php
$avaliacoes = GlobalModel::retornarUmaLista("SELECT * FROM avaliacao_instrutor ORDER BY id DESC");
?>
<div class="flex w-full p-2 justify-end">
    <a href="index.php?pagina=criar-avaliacao-instrutor" class="btn btn-neutral">
        <i class="fa fa-plus" aria-hidden="true"></i> Criar Avaliação
    </a>
</div>
<div class="overflow-x-auto rounded-box bg-base-100 shadow-xl rounded-lg">
  <table class="table min-w-full table-auto">
    <!-- head -->
    <thead>
      <tr>
        <th class="w-8"></th>
        <th class="w-40">Instrutor</th>
        <th class="w-50">Quem será Avaliado</th>
        <th class="w-20 text-center">Status</th>
        <th class="w-20 text-center">Data de liberação</th>
        <th class="w-40 text-center">Data de realização</th>
        <th class="w-20 text-center">Qtd. Perguntas</th>
        <th class="w-20 text-center">Editar</th>
      </tr>
    </thead>
    <tbody>
        <?php foreach ($avaliacoes as $index => $avaliacao): ?>
            <tr>
                <?php
                    $data_realizacao = $avaliacao['realizada'] ? $avaliacao['realizada'] : false;
                    
                   $avaliado = [
                        'nome' => GlobalModel::retornarUmValor("SELECT nome FROM afilhados WHERE id = {$avaliacao['afilhado_id']}"),
                        'foto' => getFotoAfilhado($avaliacao['afilhado_id']),
                        'badge' => 'AFILHADO'
                    ];

                    if ($avaliacao['liberar'] > date('Y-m-d')) {
                        $status = "<span class='badge badge-xs font-semibold badge-neutral p-2'>Bloqueada</span>";
                    } else if ($avaliacao['liberar'] <= date('Y-m-d') && !$data_realizacao) {
                        $status = "<span class='badge badge-xs font-semibold badge-warning p-2'>Pendente</span>";
                    } else {
                        $status = "<span class='badge badge-xs font-semibold badge-success p-2'>Realizada</span>";
                    }

            ?>
                <td><?= $index+1; ?></td>
                <td><?= $avaliacao['instrutor'] ?></td>
                <td>
                    <div class="">
                        <div class="avatar indicator">
                            <div class="w-8 rounded-full"><img src="<?= $avaliado['foto'] ?>" /></div>
                        </div>
                        <span><?= $avaliado['nome'] ?></span>
                    </div>
                </td>
                <td class="text-center"><?= $status; ?></td>
                <td class="text-center">
                    <?php 
                        $data = new DateTime($avaliacao['liberar']);
                        $dataFormatada = $data->format('d/m/Y');
                        echo $dataFormatada;
                    ?>
                </td>
                <td class="text-center">
                   <?php 
                        if ($data_realizacao) {
                            $data = new DateTime($data_realizacao);
                            $dataFormatada = $data->format('d/m/Y');
                            echo $dataFormatada;
                        } else {
                            echo "<i class='fa fa-times'></i>";
                        }
                    ?>
                </td>
                <td class="text-center"><span class="badge badge-neutral rounded-full h-8 w-8"><?= count(array_filter(explode(',', $avaliacao['perguntas']))) ?></span></td>
                <td>
                    <a class="btn btn-neutral btn-sm" href="index.php?pagina=editar-avaliacao-instrutor&origem=<?= encrypt($avaliacao['id']) ?>"><i class="fa fa-pencil"></i></a>
                </td>
        <?php endforeach; ?>
    </tbody>
  </table>
</div>
