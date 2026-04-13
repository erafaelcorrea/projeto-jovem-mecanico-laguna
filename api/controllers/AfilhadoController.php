<?php

require_once 'models/AfilhadoModel.php';
require_once 'core/Request.php';

class AfilhadoController
{
    public static function index()
    {
        return [
            'status' => 200,
            'data' => AfilhadoModel::getAll()
        ];
    }

    public static function afilhados($afilhado)
    {
        if (!$afilhado) {
            return [
                'status' => 400,
                'message' => 'Parâmetro afilhado não informado'
            ];
        }

        $data = AfilhadoModel::getPadrinhos($afilhado);

        if (empty($data)) {
            return [
                'status' => 404,
                'message' => 'Nenhum afilhado encontrado'
            ];
        }

        return [
            'status' => 200,
            'data' => $data
        ];
    }

    public static function show($id)
    {
        $data = AfilhadoModel::getById($id);

        if (!$data) {
            return [
                'status' => 404,
                'message' => 'Afilhado não encontrado'
            ];
        }

        return [
            'status' => 200,
            'data' => $data
        ];
    }

    public static function store()
    {
        $data = [
            'nome' => Request::input('nome'),
            'matricula' => Request::input('matricula'),
            'cargo' => Request::input('cargo'),
            'data_nascimento' => Request::input('data_nascimento'),
            'sexo' => Request::input('sexo'),
            'telefone' => Request::input('telefone'),
            'email' => Request::input('email'),
            'user' => Request::input('user'),
            'senha' => Request::input('senha')
        ];

        if (!$data['nome'] || !$data['senha']) {
            return [
                'status' => 400,
                'message' => 'Nome e senha são obrigatórios'
            ];
        }

        $id = AfilhadoModel::create($data);

        return [
            'status' => 201,
            'data' => [
                'id' => $id,
                'nome' => $data['nome']
            ]
        ];
    }

    public static function update()
    {
        $id = Request::input('id');

        $data = [
            'nome' => Request::input('nome'),
            'matricula' => Request::input('matricula'),
            'cargo' => Request::input('cargo'),
            'data_nascimento' => Request::input('data_nascimento'),
            'sexo' => Request::input('sexo'),
            'telefone' => Request::input('telefone'),
            'email' => Request::input('email')
        ];

        $updated = AfilhadoModel::update($id, $data);

        if (!$updated) {
            return [
                'status' => 404,
                'message' => 'Não atualizado'
            ];
        }

        return [
            'status' => 200,
            'message' => 'Atualizado com sucesso'
        ];
    }

    public static function delete()
    {
        $id = Request::input('id');

        $deleted = AfilhadoModel::delete($id);

        if (!$deleted) {
            return [
                'status' => 404,
                'message' => 'Não encontrado'
            ];
        }

        return [
            'status' => 200,
            'message' => 'Deletado com sucesso'
        ];
    }

    public static function login()
    {
        $user = Request::input('user');
        $senha = Request::input('senha');

        $afilhado = AfilhadoModel::login($user, $senha);
        
        if (!$afilhado) {
            return [
                'status' => 401,
                'message' => 'Login inválido'
            ];
        }

        return [
            'status' => 200,
            'data' => $afilhado
        ];
    }
}