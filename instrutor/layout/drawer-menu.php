<label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label> 

<div class="w-64 min-h-full bg-base-100 text-base-content flex flex-col">

    <!-- 👤 PERFIL (fora do menu) -->
    <div class="p-4 flex flex-col items-center border-b border-gray-300">
        <div class="avatar indicator">
            <span class="indicator-item badge badge-sm badge-neutral">INSTRUTOR</span>
            <div class="w-24 rounded-full">
                <img src="../assets/img/instrutor.png" />
            </div>
        </div>

        <span class="mt-2 font-semibold">
            <?= $_SESSION['instrutor']['nome'] ?>
        </span>
    </div>

    <!-- 📋 MENU -->
    <ul class="menu p-4 flex-1">
        <?php 
        $menu = [
            ['pagina' => 'alunos', 'label' => 'Notas dos Alunos', 'icone'=> 'users'],
            ['pagina' => 'avaliacoes', 'label' => 'Avaliações do Cliente', 'icone'=> 'file'],
            ['pagina' => 'sair', 'label' => 'Sair', 'icone'=> 'sign-out']
        ];

        $paginaAtual = filter_input(INPUT_GET, 'pagina', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';

        foreach ($menu as $item): 
            $active = ($paginaAtual === $item['pagina']) ? 'active' : '';
        ?>

        <li class="mb-1">
            <a href="<?= "?pagina=".$item['pagina'] ?>" class="<?= $active ?>">
                <i class="fa fa-<?= $item['icone']; ?> mr-1" aria-hidden="true"></i> <?= $item['label'] ?>
            </a>
        </li>

        <?php endforeach; ?>
    </ul>

    <div class="p-4 flex flex-col items-center border-t border-gray-300">
        <span class="text-xs font-semibold"><?= date('d/m/Y H:i:s'); ?></span>
    </div>

</div>