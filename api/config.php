<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO DO BANCO
|--------------------------------------------------------------------------
*/
define('DB_HOST', 'localhost');
define('DB_NAME', 'laguna');
define('DB_USER', 'root');
define('DB_PASS', '');

//define('DB_HOST', 'usinalaguna.mysql.dbaas.com.br');
//define('DB_NAME', 'usinalaguna');
//define('DB_USER', 'usinalaguna');
//define('DB_PASS', 'La#707060');


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES GERAIS
|--------------------------------------------------------------------------
*/
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

date_default_timezone_set('America/Sao_Paulo');


/*
|--------------------------------------------------------------------------
| HEADERS DE SEGURANÇA
|--------------------------------------------------------------------------
*/
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");


/*
|--------------------------------------------------------------------------
| AUTOLOAD SIMPLES (carrega classes automaticamente)
|--------------------------------------------------------------------------
*/
spl_autoload_register(function ($class) {

    $paths = [
        "controllers/$class.php",
        "models/$class.php",
        "core/$class.php"
    ];

    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});