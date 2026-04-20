<?php
$afilhados = GlobalModel::retornarUmaLista("SELECT id, nome, DATE_FORMAT(ultimo_acesso, '%d/%m/%Y - %H:%i:%s') as ultimo_acesso FROM afilhados ORDER BY nome");

$conteudos_id = explode(',', $_SESSION['instrutor']['conteudos']);
$ids = implode(',', array_map('intval', $conteudos_id));
$result = GlobalModel::retornarUmaLista("SELECT id, conteudo FROM conteudos WHERE id IN ($ids)");
$conteudos = [];
foreach ($result as $row) {
    $conteudos[] = [
        'id' => $row['id'],
        'conteudo' => $row['conteudo']
    ];
}
?>
<div class="max-h-150 overflow-y-auto rounded-box bg-base-100 shadow-xl rounded-lg">
  <table class="table min-w-full">
    <!-- head -->
    <thead class="sticky top-0 bg-base-100 z-10">
      <tr>
        <th class="w-8"></th>
        <th class="w-auto">Alunos</th>
        <?php foreach ($conteudos as $index => $conteudo): ?>
            <th class="w-15 text-center cursor-pointer hover:bg-yellow-100"><div class="tooltip tooltip-left" data-tip="<?= $conteudo['conteudo'] ?>">NOTA <?= ($index + 1) ?>  <i class="fa fa-info-circle" aria-hidden="true"></i></div></th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
        <?php foreach ($afilhados as $index => $afilhado): ?>
            <tr class="hover:bg-gray-100">
                <?php
                    $tem_padrinho_no_momento = GlobalModel::retornarUmObjeto("SELECT b.nome, b.id FROM batizado a INNER JOIN padrinhos b WHERE a.padrinho = b.id AND a.afilhado = {$afilhado['id']} AND a.fim >= CURDATE()");
                    $total_avaliacoes_pendentes = GlobalModel::retornarUmValor("SELECT COUNT(*) as total FROM avaliacao WHERE quem_avalia = 'afilhado' AND afilhado_id = {$afilhado['id']} AND realizada IS NULL AND liberar <= CURDATE()");
                    $total_padrinhos = GlobalModel::retornarUmValor("SELECT COUNT(*) as total FROM batizado WHERE afilhado = {$afilhado['id']}");
                ?>
                <td>
                    <div class="avatar">
                        <div class="w-12 rounded-full">
                            <a href="index.php?pagina=relatorio&origem=<?= encrypt($afilhado['id']); ?>"><img src="<?= getFotoAfilhado($afilhado['id']) ?>" /></a>
                        </div>
                    </div>
                </td>
                <td><a href="index.php?pagina=relatorio&origem=<?= encrypt($afilhado['id']); ?>"><?= $afilhado['nome']; ?></a></td>
                <?php foreach ($conteudos as $index => $conteudo): ?>
                    <td class="w-15 text-center">
                    <button
                        onclick="aplicarNotaAluno(this)" 
                        class="btn btn-ghost btn-circle"
                        data-aluno-id="<?= $afilhado['id']; ?>"
                        data-aluno-nome="<?= $afilhado['nome']; ?>"
                        data-conteudo-id="<?= $conteudo['id']; ?>"
                        data-conteudo-nome="<?= $conteudo['conteudo']; ?>"
                    >
                        <?php 
                            $nota = GlobalModel::retornarUmValor("SELECT nota FROM notas_instrutor WHERE conteudo = {$conteudo['id']} AND aluno = {$afilhado['id']}"); 
                            if ($nota) { echo $nota; } else { echo 0; }
                        ?>
                    </button>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
  </table>
</div>

<dialog id="aplicar_nota" class="modal">
  <div class="modal-box flex flex-col gap-2">
    <span class="text-xs text-gray-400">ALUNO:</span>
    <span class="font-thin text-lg" id="aluno_nome"></span>
    <span class="text-xs text-gray-400">CONTEÚDO APLICADO:</span>
    <span class="font-thin text-lg" id="conteudo_nome"></span>
    <span class="text-xs text-gray-400">NOTA:</span>
    <form action="index.php?pagina=aplicar-nota" method="POST">
        <input type="hidden" id="conteudo_id" name="conteudo_id" value="" />
        <input type="hidden" id="aluno_id" name="aluno_id" value="" />
        <fieldset class="fieldset border-base-300 rounded-box w-xs p-4">
            <div class="join">
                <input type="text" name="nota" class="input join-item" />
                <button type="submit" class="btn join-item">SALVAR</button>
            </div>
        </fieldset>
    </form>
    <div class="modal-action">
      <form method="dialog">
        <button class="btn">Cancelar</button>
      </form>
    </div>
  </div>
</dialog>
<script>
function aplicarNotaAluno(btn) {

    // Nome do aluno
    document.getElementById("aluno_nome").innerText = btn.dataset.alunoNome;

    // Nome do conteúdo (matéria)
    document.getElementById("conteudo_nome").innerText = btn.dataset.conteudoNome;

    // IDs (inputs hidden)
    document.getElementById("aluno_id").value = btn.dataset.alunoId;
    document.getElementById("conteudo_id").value = btn.dataset.conteudoId;

    // Abrir modal
    document.getElementById("aplicar_nota").showModal();
}
</script>