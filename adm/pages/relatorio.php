<?php
    $afilhado_id = decrypt($_GET['origem']);
    $afilhado = GlobalModel::RetornarUmObjeto("SELECT id, nome, TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) AS idade, sexo, DATE_FORMAT(ultimo_acesso, '%d/%m/%Y - %H:%i:%s') as acesso FROM afilhados WHERE id = {$afilhado_id}");
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
    $media = 0;
?>
<div class="w-full flex justify-between p-4">
    <span class="text-2xl">JOVEM MECÂNICO</span>
    <a href="index.php?pagina=afilhados" class="btn btn-neutral btn-sm">VOLTAR <i class="fa fa-mail-reply"></i></a>
</div>

<div class="bg-base-100 shadow-xl rounded-lg grid grid-cols-1 gap-6 mt-4">
    <div class="stats shadow w-full">
        <div class="stat">
            <div class="stat-figure text-secondary">
                <div class="avatar">
                    <div class="w-16 rounded-full">
                        <img src="../assets/img/instrutor.png" />
                    </div>
                </div>
            </div>
            <div class="stat-title text-blue text-lg">JOSÉ AGNELO</div>
            <div class="stat-desc">INSTRUTOR</div>
        </div>
        <div class="stat">
            <div class="stat-figure text-neutral">
                <div class="radial-progress bg-neutral text-neutral-content border-neutral border-4" style="--value:70;" aria-valuenow="70" role="progressbar">
                    0%
                </div>
            </div>
            <div class="stat-title">AVALIAÇÃO TÉCNICO COMPORTAMENTAL DO INSTRUTOR</div>
            <div class=""><a class="btn btn-neutral btn-sm" href="index.php?pagina=avaliacao-comportamental-instrutor&origem=<?= encrypt($afilhado_id) ?>"><i class="fa fa-eye"></i> VISUALIZAR</a></div>
            <div class="stat-desc">10 CRITÉRIOS DE AVALIAÇÃO</div>
        </div>
        <div class="stat">
            <div class="stat-figure text-neutral">
                <div class="radial-progress bg-neutral text-neutral-content border-neutral border-4" style="--value:70;" aria-valuenow="70" role="progressbar">
                    0%
                </div>
            </div>
            <div class="stat-title">MÉDIA DAS PROVAS APLICADAS</div>
            <div class=""><a class="btn btn-neutral btn-sm" href="index.php?pagina=notas-provas&origem=<?= encrypt($afilhado_id) ?>#TR<?= $afilhado_id ?>"><i class="fa fa-eye"></i> VISUALIZAR</a></div>
            <div class="stat-desc">10 CONTEÚDOS PROGRAMÁTICOS</div>
        </div> 
    </div>
</div>

<div class="grid grid-cols-1 gap-6 mt-4 mb-8">
    <div class="bg-base-100 shadow-xl rounded-lg p-2 w-full flex flex-col lg:flex-row gap-4"> 
        <div class="w-1/3 flex justify-between items-center p-1 border-r-0 border-gray-200 lg:border-r">
            <div class="avatar w-16">
                <div class="w-16 h-16 rounded-full">
                    <img src="<?= getFotoAfilhado($afilhado->id) ?>" />
                </div>
            </div>
            <div class="flex-grow ml-4">
                <div class="stat-title text-blue text-lg"><?= $afilhado->nome; ?></div>
                <div class="stat-desc">IDADE: <?= $afilhado->idade ?></div>
                <div class="stat-desc">SEXO: <?= $afilhado->sexo ?></div>
                <div class="stat-desc">ÚLTIMO ACESSO: <?= $afilhado->acesso ?></div>
            </div>
                
        </div>

        <div class="w-1/3 flex p-1 border-r-0 border-gray-200 lg:border-r">
            <div class="stat">
                <div class="stat-title text-center">PADRINHOS</div>
                <div class="stat-value text-center"><?= GlobalModel::retornarUmValor("SELECT COUNT(*) AS total FROM batizado WHERE afilhado = {$afilhado_id}") ?></div>
                <div class="text-xs text-wrap text-gray-500 text-center">
                    TOTAL DE PADRINHOS ATÉ O MOMENTO
                </div>
            </div>
            <div class="stat">
                <div class="stat-title text-center">RESPOSTAS DISSERTATIVAS</div>
                <div class="stat-value text-center"><?= GlobalModel::retornarUmValor("SELECT COUNT(*) AS total FROM respostas a INNER JOIN perguntas b ON a.pergunta = b.id INNER JOIN avaliacao c ON a.avaliacao = c.id WHERE c.afilhado_id = {$afilhado_id} AND c.quem_avalia = 'padrinho' AND b.modo = 'dissertativa'") ?></div>
                <div class="stat-actions text-center">
                <a class="btn btn-neutral btn-sm" href="index.php?pagina=dissertativas&origem=<?= encrypt($afilhado_id) ?>"><i class="fa fa-eye"></i> VISUALIZAR</a>
                </div>
            </div>
        </div>

        <div class="w-1/3 flex p-1 items-center justify-center">
            <div class="flex flex-col gap-4">
                <div class="stat-title">📊 Metodologia de Cálculo e Análise dos Resultados</div>
                <div class="w-full text-center"><button class="btn btn-sm btn-accent mt-2" onclick="my_modal_1.showModal()"><i class="fa fa-eye"></i> VISUALIZAR</button></div>
            </div>
        </div> 
    </div>
</div>


<div class="overflow-x-auto rounded-box bg-base-100 shadow-xl rounded-lg grid grid-cols-1 gap-6 mt-4">
    <div class="w-full flex p-2">
        <span class="text-lg font-thin text-base-400 p-4">AVALIÇÃO DOS PADRINHOS</span>
    </div>
    <table class="table min-w-full table-auto">
        <!-- head -->
        <thead>
            <tr>
            <th class="">Categoria</th>
            <th class="text-center">
                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="pessimo" class="radio radio-xs radio-error" checked /><span>PÉSSIMO</span></label>
            </th>
            <th class="text-center">
                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="ruim" class="radio radio-xs radio-warning" checked /><span>RUIM</span></label>
            </th>
            <th class="text-center">
                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="bom" class="radio radio-xs radio-info" checked /><span>BOM</span></label>
            </th>
            <th class="text-center">
                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="otimo" class="radio radio-xs radio-accent" checked /><span>ÓTIMO</span></label>
            </th>
            <th class="text-center">
                <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="excelente" class="radio radio-xs radio-success" checked /><span>EXCELENTE</span></label>
            </th>
            <th class="text-center">Qtd. Respostas</th>
            <th class="text-center">Percentual</th>
            </tr>
        </thead>
        <tbody class="font-semibold">
            <?php foreach ($categorias_perguntas as $index => $cat_p): ?>
            <?php
                $respostas = GlobalModel::retornarUmaLista("SELECT a.resposta FROM respostas a INNER JOIN perguntas b ON a.pergunta = b.id INNER JOIN avaliacao c ON a.avaliacao = c.id WHERE b.modo = 'multipla-escolha' AND b.categoria = '{$cat_p['tag']}' AND c.afilhado_id = {$afilhado_id}");
                $r = analisarRespostas($respostas);
                $media += $r['percentual'];
            ?>
            <tr>
                <td><div class="tooltip tooltip-right" data-tip="<?= GlobalModel::retornarUmValor("SELECT pergunta FROM perguntas WHERE perfil = 'padrinho' AND categoria = '{$cat_p['tag']}'") ?>"><?= $cat_p['label'] ?> <i class="fa fa-info-circle" aria-hidden="true"></i></div></td>
                <td class="text-center"><?= $r['distribuicao']['pessimo'] ?></td>
                <td class="text-center"><?= $r['distribuicao']['ruim'] ?></td>
                <td class="text-center"><?= $r['distribuicao']['bom'] ?></td>
                <td class="text-center"><?= $r['distribuicao']['otimo'] ?></td>
                <td class="text-center"><?= $r['distribuicao']['excelente'] ?></td>
                <td class="text-center"><?= $r['qtd_respostas'] ?></td>
                <td class="text-center"><?= $r['percentual'] ?>%</td>
            </tr>
            <?php endforeach; ?>
            <tr class="bg-yellow-200">
                <td colspan="7" class="text-right">MÉDIA GERAL</td>
                <td class="text-center text-2xl"><?= $media ?>%</td>
            </tr>
        </tbody>
    </table>
</div>

<dialog id="my_modal_1" class="modal">
    <form method="dialog">
    <div class="modal-box relative">
        <h3 class="text-sm font-bold">📊 Metodologia de Cálculo e Análise dos Resultados</h3>
        <button class="btn btn-sm btn-neutral absolute right-2 top-2"><i class="fa fa-times"></i></button>
        <div class="modal-action">
    <div class="flex flex-col gap-1 text-sm">
    <p>Para a avaliação do desempenho, cada pergunta dentro de cada categoria é analisada individualmente com base nas respostas fornecidas pelos avaliadores.</p>

    <p class="mt-2">As respostas são convertidas em uma escala numérica padronizada:</p>
    <div class="flex flex-col gap-1">
    <span>🔴 Péssimo = 1</span>
    <span>🟠 Ruim = 2</span>
    <span>🟡 Bom = 3</span>
    <span>🔵 Ótimo = 4</span>
    <span>🟢 Excelente = 5</span>
    </div>

    <p class="mt-2">Essa padronização permite transformar percepções qualitativas em dados mensuráveis.</p>

    <p class="mt-2 font-semibold">⚙️ Etapas do Cálculo</p>
    <p class="">Para cada pergunta de cada categoria, são realizados os seguintes procedimentos:</p>

    <p class="mt-2">1. ➕ Soma dos valores</p>
    <p>Somam-se todos os valores atribuídos às respostas daquela pergunta.</p>

    <p class="mt-2">2. 🔢 Quantidade de respostas</p>
    <p>É contabilizado o total de avaliações válidas recebidas.</p>

    <p class="mt-2">3. 📈 Cálculo da média</p>
    <p>A média é obtida dividindo-se a soma total pela quantidade de respostas, indicando o desempenho médio na escala de 1 a 5.</p>

    <p class="mt-2">4. 📊 Conversão para percentual</p>
    <p>A média é convertida em percentual para facilitar a interpretação:</p>
    <p>Percentual = (Média ÷ 5) × 100</p>

    <p class="mt-2">5. 📌 Distribuição das respostas</p>
    <p>Também é analisada a quantidade de respostas em cada nível (péssimo a excelente), permitindo entender como as avaliações estão distribuídas.</p>

    <p class="mt-2 font-semibold">🧠 Interpretação dos Resultados</p>
    <p>O percentual obtido representa o nível geral de desempenho em cada pergunta avaliada dentro de sua respectiva categoria.</p>

    <p class="mt-2 font-semibold">De forma geral:</p>

    <p>🟢 Percentuais elevados indicam predominância de avaliações positivas</p>
    <p>🟡 Percentuais intermediários indicam desempenho adequado com oportunidades de melhoria</p>
    <p>🔴 Percentuais baixos indicam necessidade de atenção</p>

    <p class="mt-2">A distribuição das respostas complementa a análise, evidenciando se o resultado foi consistente ou se houve divergência entre os avaliadores.</p>

    <p class="mt-2 font-semibold">📌 Considerações Finais</p>

    <p>A metodologia aplicada garante uma análise completa, pois combina:</p>

    <p class="mt-2">📊 Indicadores quantitativos (média e percentual)</p>
    <p>📈 Distribuição qualitativa das respostas</p>

    <p class="mt-2">Dessa forma, é possível obter uma visão clara, objetiva e confiável do desempenho em cada aspecto avaliado.</p>
    <button class="btn btn-sm btn-neutral mt-2"><i class="fa fa-times"></i> Fechar</button>
    </div>
        </div>
    </div>
    </form>
</dialog>



