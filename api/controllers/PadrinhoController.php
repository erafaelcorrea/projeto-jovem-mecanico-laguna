<?php

require_once 'models/PadrinhoModel.php';
require_once 'core/Request.php';

class PadrinhoController
{
    public static function index()
    {
        return [
            'status' => 200,
            'data' => PadrinhoModel::getAll()
        ];
    }

    public static function afilhados($padrinho)
    {
        if (!$padrinho) {
            return [
                'status' => 400,
                'message' => 'Parâmetro padrinho não informado'
            ];
        }

        $data = PadrinhoModel::getAfilhados($padrinho);

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
        $data = PadrinhoModel::getById($id);

        if (!$data) {
            return [
                'status' => 404,
                'message' => 'Padrinho não encontrado'
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
            'data_nascimento' => Request::input('data_nascimento'),
            'sexo' => Request::input('sexo'),
            'telefone' => Request::input('telefone'),
            'email' => Request::input('email'),
            'user' => Request::input('user'),
            'senha' => Request::input('senha')
        ];

        if (!$data['nome'] || !$data['senha'] || !$data['user']) {
            return [
                'status' => 400,
                'message' => 'Nome, user e senha são obrigatórios'
            ];
        }

        $id = PadrinhoModel::create($data);

        return [
            'status' => 201,
            'data' => [
                'id' => $id
            ]
        ];
    }

    public static function update()
    {
        $data = [
            'id' => Request::input('id'),
            'nome' => Request::input('nome'),
            'matricula' => Request::input('matricula'),
            'data_nascimento' => Request::input('data_nascimento'),
            'sexo' => Request::input('sexo'),
            'telefone' => Request::input('telefone'),
            'email' => Request::input('email')
        ];
        
        $updated = PadrinhoModel::update($data);

        if ($updated > 0) {
            return [
                'status' => 200,
                'message' => 'Padrinho atualizado com sucesso'
            ];
        } else {
            return [
                'status' => 404,
                'message' => 'Padrinho não encontrado ou dados idênticos'
            ];
        }
    }

    public static function delete()
    {
        $id = Request::input('id');

        $deleted = PadrinhoModel::delete($id);

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

        $padrinho = PadrinhoModel::login($user, $senha);
        
        if (!$padrinho) {
            return [
                'status' => 401,
                'message' => 'Login inválido'
            ];
        }

        return [
            'status' => 200,
            'data' => $padrinho
        ];
    }
}