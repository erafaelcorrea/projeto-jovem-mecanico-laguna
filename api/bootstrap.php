<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/core/Database.php';

// autoload simples (já que você usa padrão de pastas)
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . "/models/$class.php",
        __DIR__ . "/core/$class.php"
    ];

    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});