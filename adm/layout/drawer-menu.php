<label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label> 

<div class="w-64 min-h-full bg-base-100 text-base-content flex flex-col">

    <!-- 👤 PERFIL (fora do menu) -->
    <div class="p-4 flex flex-col items-center border-b border-gray-300">
        <div class="avatar indicator">
            <span class="indicator-item badge badge-sm badge-neutral">ADM</span>
            <div class="w-24 rounded-full">
                <img src="assets/img/perfil.jpg" />
            </div>
        </div>

        <span class="mt-2 font-semibold">
            Painel Administrativo
        </span>
    </div>

    <!-- 📋 MENU -->
    <ul class="menu p-4 flex-1">
        <?php 
        $menu = [
            ['pagina' => 'afilhados', 'label' => 'Jovens Mecânicos', 'icone'=> 'users'],
            ['pagina' => 'padrinhos', 'label' => 'Padrinhos', 'icone'=> 'users'],
            ['pagina' => 'perguntas', 'label' => 'Perguntas', 'icone'=> 'question'],
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