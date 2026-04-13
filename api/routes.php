<?php
require_once 'controllers/AfilhadoController.php';
require_once 'controllers/PadrinhoController.php';
require_once 'controllers/GlobalController.php';

function route($url)
{
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($url) {
        /*
        |--------------------------------------------------------------------------
        | GLOBAL
        |--------------------------------------------------------------------------
        */
        case 'global/sql-return-assoc':
            return GlobalController::sqlReturnAssoc();

        case 'global/sql-return-object': 
            return GlobalController::sqlReturnObject();

        case 'global/sql-return-array-list':
            return GlobalController::sqlReturnArrayList();

        case 'global/responder':
            return GlobalController::insertResposta();

        case 'global/sql-update':
            return GlobalController::sqlUpdate();
        
        case 'global/update-pergunta':
            return GlobalController::updatePergunta();

        case 'global/adicionar-pergunta':
            return GlobalController::adicionarPergunta();

        case 'global/salvar-vinculo':
            return GlobalController::salvarVinculo();

        case 'global/criar-avaliacao':
            return GlobalController::criarAvaliacao();

        case 'global/excluir-avaliacao':
            return GlobalController::excluirAvaliacao();


        /*
        |--------------------------------------------------------------------------
        | PADRINHOS
        |--------------------------------------------------------------------------
        */
        case 'padrinhos':
            if ($method === 'GET') return PadrinhoController::index();
            if ($method === 'POST') return PadrinhoController::store();
            break;

        case 'padrinhos/afilhados':
            return PadrinhoController::afilhados($_GET['padrinho'] ?? null);
        
        case 'padrinhos/show':
            return PadrinhoController::show($_GET['id'] ?? null);

        case 'padrinhos/update':
            return PadrinhoController::update();

        case 'padrinhos/delete':
            return PadrinhoController::delete();

        case 'padrinhos/login':
            return PadrinhoController::login();

        
        /*
        |--------------------------------------------------------------------------
        | AFILHADOS
        |--------------------------------------------------------------------------
        */
        case 'afilhados':
            if ($method === 'GET') return AfilhadoController::index();
            if ($method === 'POST') return AfilhadoController::store();
            break;

        case 'afilhados/padrinhos':
            return AfilhadoController::afilhados($_GET['afilhado'] ?? null);
        
        case 'afilhados/show':
            return AfilhadoController::show($_GET['id'] ?? null);

        case 'afilhados/update':
            return AfilhadoController::update();

        case 'afilhados/delete':
            return AfilhadoController::delete();

        case 'afilhados/login':
            return AfilhadoController::login();



        /*
        |--------------------------------------------------------------------------
        | DEFAULT
        |--------------------------------------------------------------------------
        */
        default:
            return [
                'status' => 404,
                'message' => 'Rota não encontrada'
            ];
    }
}