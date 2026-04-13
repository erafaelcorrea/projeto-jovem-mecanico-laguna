<?php

require_once 'models/GlobalModel.php';
require_once 'core/Request.php';

class GlobalController
{
    public static function sqlReturnAssoc()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $sql = $input['sql'] ?? null;

        if (!$sql) {
            return [
                'status' => 400,
                'message' => 'Parâmetro SQL não informado'
            ];
        }

        try {
            $valor = GlobalModel::executarsqlReturnAssoc($sql);

            return [
                'status' => 200,
                'valor' => $valor
            ];
        } catch (Exception $e) {
            return [
                'status' => 400,
                'message' => $e->getMessage()
            ];
        }
    } 

    public static function sqlReturnObject()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $sql = $input['sql'] ?? null;

        if (!$sql) {
            return [
                'status' => 400,
                'message' => 'Parâmetro SQL não informado'
            ];
        }

        try {
            $valor = GlobalModel::executarSqlReturnObject($sql);

            return [
                'status' => 200,
                'valor' => $valor
            ];
        } catch (Exception $e) {
            return [
                'status' => 400,
                'message' => $e->getMessage()
            ];
        }
    }

    public static function sqlReturnArrayList()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $sql = $input['sql'] ?? null;

        if (!$sql) {
            return [
                'status' => 400,
                'message' => 'Parâmetro SQL não informado'
            ];
        }

        try {
            $valor = GlobalModel::executarsqlReturnArrayList($sql);

            return [
                'status' => 200,
                'valor' => $valor
            ];
        } catch (Exception $e) {
            return [
                'status' => 400,
                'message' => $e->getMessage()
            ];
        }
    } 

    public static function insertResposta()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $quem_avalia = $input['quem_avalia'] ?? null;
        $avaliacao = $input['avaliacao'] ?? null;
        $pergunta = $input['pergunta'] ?? null;
        $resposta = $input['resposta'] ?? null;

        // 🔒 Validação básica
        if (!$quem_avalia || !$avaliacao || !$pergunta || !$resposta) {
            return [
                'status' => 400,
                'message' => 'Parâmetros obrigatórios não informados'
            ];
        }

        try {
            $inserido = GlobalModel::insertResposta(
                $quem_avalia,
                $avaliacao,
                $pergunta,
                $resposta
            );

            return [
                'status' => 200,
                'valor' => [
                    'registro_inserido' => $inserido
                ]
            ];
        } catch (Exception $e) {
            return [
                'status' => 400,
                'message' => $e->getMessage()
            ];
        }
    }

    public static function sqlUpdate()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $sql = $input['sql'] ?? null;

        if (!$sql) {
            return [
                'status' => 400,
                'message' => 'Parâmetro SQL não informado'
            ];
        }

        try {
            $valor = GlobalModel::executarsqlUpdate($sql);

            return [
                'status' => 200,
                'valor' => $valor
            ];
        } catch (Exception $e) {
            return [
                'status' => 400,
                'message' => $e->getMessage()
            ];
        }
    } 

    public static function updatePergunta()
    {
        $data = [
            'id' => Request::input('id'),
            'pergunta' => Request::input('pergunta'),
            'descricao' => Request::input('descricao'),
            'modo' => Request::input('modo'),
            'perfil' => Request::input('perfil'),
            'categoria' => Request::input('categoria'),
            'subcategoria' => Request::input('subcategoria')
        ];
        
        $updated = GlobalModel::executarUpdatePergunta($data);

        if ($updated !== false) {
            return [
                'status' => 200,
                'message' => 'Pergunta atualizada com sucesso'
            ];
        } else {
            return [
                'status' => 404,
                'message' => 'erro'
            ];
        }
    }

    public static function criarAvaliacao()
    {
        $data = [
            'quem_avalia' => Request::input('quem_avalia'),
            'padrinho_id' => Request::input('padrinho_id'),
            'afilhado_id' => Request::input('afilhado_id'),
            'liberar' => Request::input('liberar')
        ];
        
        $updated = GlobalModel::executarcriarAvaliacao($data);

        if ($updated !== false) {
            return [
                'status' => 200,
                'message' => 'Avaliação criada com sucesso'
            ];
        } else {
            return [
                'status' => 404,
                'message' => 'erro'
            ];
        }
    }

    public static function excluirAvaliacao()
    {
        $data = [
            'avaliacao_id' => Request::input('avaliacao_id')
        ];
        
        $updated = GlobalModel::executarExcluirAvaliacao($data);

        if ($updated !== false) {
            return [
                'status' => 200,
                'message' => 'Avaliação excluída com sucesso'
            ];
        } else {
            return [
                'status' => 404,
                'message' => 'erro'
            ];
        }
    }

    public static function adicionarPergunta()
    {
        $data = [
            'id' => Request::input('id'),
            'pergunta' => Request::input('pergunta'),
            'descricao' => Request::input('descricao'),
            'modo' => Request::input('modo'),
            'perfil' => Request::input('perfil'),
            'categoria' => Request::input('categoria'),
            'subcategoria' => Request::input('subcategoria')
        ];
        
        $updated = GlobalModel::executarAdicionarPergunta($data);

        if ($updated !== false) {
            return [
                'status' => 200,
                'message' => 'Pergunta adicionada com sucesso'
            ];
        } else {
            return [
                'status' => 404,
                'message' => 'erro'
            ];
        }
    }

    public static function salvarVinculo()
    {
        $data = [
            'padrinho_id' => Request::input('padrinho_id'),
            'afilhado_id' => Request::input('afilhado_id'),
            'inicio' => Request::input('inicio'),
            'fim' => Request::input('fim')
        ];

        $saved = GlobalModel::executarSalvarVinculo($data);

        if ($saved !== false) {
            return [
                'status' => 200,
                'message' => 'Vínculo salvo com sucesso'
            ];
        } else {
            return [
                'status' => 404,
                'message' => 'erro'
            ];
        }
    }
}