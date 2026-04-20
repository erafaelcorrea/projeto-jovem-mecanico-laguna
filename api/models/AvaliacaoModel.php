<?php
class AvaliacaoModel
{
    public static function listarPerguntas()
    {
        $db = Database::connect();

        return $db->query("
            SELECT * FROM perguntas
            ORDER BY id DESC
        ")->fetchAll();
    }
    public static function criarAvaliacaoInstrutor($data)
    {
        try {
            $db = Database::connect();

            $sql = "INSERT INTO avaliacao_instrutor 
                    (afilhado_id, liberar, instrutor)
                    VALUES (?, ?, ?)";

            $stmt = $db->prepare($sql);

            $executado = $stmt->execute([
                $data['afilhado_id'],
                $data['liberar'],
                $data['instrutor']
            ]);

            return $executado ? true : false;

        } catch (PDOException $e) {
            return $e->getMessage();
            exit;
        }
    }

    public static function excluirAvaliacaoInstrutor($data)
    {
        try {
            $db = Database::connect();

            $sql = "DELETE FROM avaliacao_instrutor 
                    WHERE id = ?";

            $stmt = $db->prepare($sql);

            $executado = $stmt->execute([
                $data['avaliacao_id']
            ]);

            return $executado ? true : false;

        } catch (PDOException $e) {
            return $e->getMessage();
            exit;
        }
    }

    public static function aplicarNotaInstrutor($data)
    {
        try {
            $db = Database::connect();

            $sql = "INSERT INTO notas_instrutor 
                    (aluno, realizada, conteudo, nota)
                    VALUES (?, CURDATE(), ?, ?)";

            $stmt = $db->prepare($sql);

            $executado = $stmt->execute([
                $data['aluno_id'],
                $data['conteudo_id'],
                $data['nota'] // aqui entra a nota já validada
            ]);

            return $executado ? true : false;

        } catch (PDOException $e) {
            return $e->getMessage();
            exit;
        }
    }

    public static function criarPergunta($data)
    {
        try {
            $db = Database::connect();

            $sql = "INSERT INTO perguntas 
                (pergunta, descricao, modo, perfil, categoria)
                VALUES (:pergunta, :descricao, :modo, :perfil, :categoria)";

            $stmt = $db->prepare($sql);

            $executado = $stmt->execute([
                ':pergunta' => $data['pergunta'],
                ':descricao' => $data['descricao'],
                ':modo' => $data['modo'],
                ':perfil' => $data['perfil'],
                ':categoria' => $data['categoria']
            ]);

            return $executado ? true : false;

        } catch (PDOException $e) {
            return false;
        }
    }

     public static function salvarPergunta($data)
    {
        try {
            $db = Database::connect();

            $sql = "UPDATE perguntas SET 
                pergunta = :pergunta,
                descricao = :descricao,
                modo = :modo,
                perfil = :perfil,
                categoria = :categoria
                WHERE id = :id";

            $stmt = $db->prepare($sql);

            $stmt->bindParam(':id', $data['id']);
            $stmt->bindParam(':pergunta', $data['pergunta']);
            $stmt->bindParam(':descricao', $data['descricao']);
            $stmt->bindParam(':modo', $data['modo']);
            $stmt->bindParam(':perfil', $data['perfil']);
            $stmt->bindParam(':categoria', $data['categoria']);

            $stmt->execute();
            return $stmt->rowCount();

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function responderAvaliacao($dados)
    {
        $db = Database::connect();

        $sql = "INSERT INTO respostas 
                (quem_avalia, avaliado, avaliacao, pergunta, categoria, resposta, data, hora)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);

        $data = date('Y-m-d');
        $hora = date('H:i:s');

        $executado = $stmt->execute([
            $dados['quem_avalia'],
            $dados['avaliado'],
            $dados['avaliacao'],
            $dados['pergunta'],
            $dados['categoria'],
            $dados['resposta'],
            $data,
            $hora
        ]);

        return $executado ? true : false;
    }

}

