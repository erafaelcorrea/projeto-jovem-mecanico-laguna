<?php
require_once 'config.php';
require_once 'routes.php';

// Captura a URL amigável
$url = $_GET['url'] ?? '';
$url = trim($url, '/');

// Executa a rota
$response = route($url);

// Retorno em ARRAY PHP (como você pediu)
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);