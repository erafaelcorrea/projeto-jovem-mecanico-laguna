<?php
$avaliacoes = GlobalModel::retornarUmaLista("SELECT * FROM avaliacao WHERE quem_avalia = 'padrinho' AND padrinho_id = {$_SESSION['padrinho']['padrinho_id']} ORDER BY id DESC");
?>
<div class="overflow-x-auto rounded-box bg-base-100 shadow-xl rounded-lg">
  <table class="table min-w-full table-auto">
    <!-- head -->
    <thead>
      <tr>
        <th class="w-8"></th>
        <th class="w-50">Quem será Avaliado</th>
        <th class="w-20 text-center">Status</th>
        <th class="w-20 text-center">Data de liberação</th>
        <th class="w-40 text-center">Data de realização</th>
        <th class="w-20 text-center">Qtd. Perguntas</th>
        <th class="w-30 text-center">Avaliar</th>
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
                        $status = "Bloqueada";
                    } else if ($avaliacao['liberar'] <= date('Y-m-d') && !$data_realizacao) {
                        $status = "Pendente";
                    } else {
                        $status = "Realizada";
                    }

            ?>
                <td><?= $index+1; ?></td>
                <td>
                    <div class="">
                        <div class="avatar indicator">
                            <div class="w-8 rounded-full"><img src="<?= $avaliado['foto'] ?>" /></div>
                        </div>
                        <span><?= $avaliado['nome'] ?></span>
                    </div>
                </td>
                <td class="text-center">
                  <?php 
                    if ($status === "Bloqueada") {
                        echo "<span class='badge badge-xs font-semibold badge-neutral p-2'>Bloqueada</span>";
                    } else if ($status === "Pendente") {
                        echo "<span class='badge badge-xs font-semibold badge-warning p-2'>Pendente</span>";
                    } else {
                        echo "<span class='badge badge-xs font-semibold badge-success p-2'>Realizada</span>";
                    }
                  ?>
                </td>
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
                <td class="text-center">
                    <?php if ($status === "Pendente"): ?>
                      <a class="btn btn-neutral btn-sm" href="index.php?pagina=avaliar&origem=<?= encrypt($avaliacao['id']) ?>"><i class="fa fa-pencil-square ml-1"></i> Avaliar</a>
                    <?php else: ?>
                      <button class="btn btn-disabled btn-sm"><i class="fa fa-pencil-square ml-1"></i> Avaliar</button>
                    <?php endif; ?>
                </td>
        <?php endforeach; ?>
    </tbody>
  </table>
</div>
