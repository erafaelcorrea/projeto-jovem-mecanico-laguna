<?php

class GlobalModel
{
    public static function retornarUmValor($sql)
    {
        $db = Database::connect();

        // 🔒 Permitir apenas SELECT
        if (stripos(trim($sql), 'select') !== 0) {
            throw new Exception('Apenas SELECT permitido');
        }

        // 🔒 Bloquear comandos perigosos
        $bloqueados = ['insert', 'update', 'delete', 'drop', 'alter', 'truncate', '--', ';'];

        foreach ($bloqueados as $palavra) {
            if (stripos($sql, $palavra) !== false) {
                throw new Exception('SQL contém comando não permitido');
            }
        }

        // Executa a query
        $stmt = $db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retorna apenas um valor
        return $result ? array_values($result)[0] : null;
    }

    public static function RetornarUmObjeto($sql)
    {
        $db = Database::connect();

        // 🔒 Permitir apenas SELECT
        if (stripos(trim($sql), 'select') !== 0) {
            throw new Exception('Apenas SELECT permitido');
        }

        // 🔒 Bloquear comandos perigosos
        $bloqueados = ['insert', 'update', 'delete', 'drop', 'alter', 'truncate', '--', ';'];

        foreach ($bloqueados as $palavra) {
            if (stripos($sql, $palavra) !== false) {
                throw new Exception('SQL contém comando não permitido');
            }
        }

        // Executa a query
        $stmt = $db->query($sql);

        // 🔁 Retorna um objeto (1 registro)
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        return $result ?: null;
    }

    public static function retornarUmaLista($sql)
    {
        $db = Database::connect();

        // 🔒 Permitir apenas SELECT
        if (stripos(trim($sql), 'select') !== 0) {
            throw new Exception('Apenas SELECT permitido');
        }

        // 🔒 Bloquear comandos perigosos
        $bloqueados = ['insert', 'update', 'delete', 'drop', 'alter', 'truncate', '--', ';'];

        foreach ($bloqueados as $palavra) {
            if (stripos($sql, $palavra) !== false) {
                throw new Exception('SQL contém comando não permitido');
            }
        }

        // Executa a query
        $stmt = $db->query($sql);

        // 🔥 Aqui está a mudança
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result ?: [];
    }

    public static function criarAvaliacao($data)
    {
        try {
            $db = Database::connect();

            $sql = "INSERT INTO avaliacao 
                    (quem_avalia, padrinho_id, afilhado_id, liberar)
                    VALUES (?, ?, ?, ?)";

            $stmt = $db->prepare($sql);

            $executado = $stmt->execute([
                $data['quem_avalia'],
                $data['padrinho_id'],
                $data['afilhado_id'],
                $data['liberar']
            ]);

            return $executado ? true : false;

        } catch (PDOException $e) {
            return $e->getMessage();
            exit;
        }
    }

    public static function excluirAvaliacao($data)
    {
        $db = Database::connect();

        $sql = "DELETE FROM avaliacao 
                WHERE id = ?";

        $stmt = $db->prepare($sql);

        $executado = $stmt->execute([
            $data['avaliacao_id']
        ]);

        return $executado ? true : false;
    }

    public static function excluirAvaliacaoInstrutor($data)
    {
        $db = Database::connect();

        $sql = "DELETE FROM avaliacao_instrutor 
                WHERE id = ?";

        $stmt = $db->prepare($sql);

        $executado = $stmt->execute([
            $data['avaliacao_id']
        ]);

        return $executado ? true : false;
    }

    public static function responderAvaliacao($dados)
    {
        $db = Database::connect();

        $sql = "INSERT INTO respostas 
                (quem_avalia, avaliacao, pergunta, resposta, data, hora)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);

        $data = date('Y-m-d');
        $hora = date('H:i:s');

        $executado = $stmt->execute([
            $dados['quem_avalia'],
            $dados['avaliacao'],
            $dados['pergunta'],
            $dados['resposta'],
            $data,
            $hora
        ]);

        return $executado ? true : false;
    }

    public static function responderAvaliacaoInstrutor($dados)
    {
        $db = Database::connect();

        $sql = "INSERT INTO respostas_instrutor 
                (instrutor, avaliacao, pergunta, resposta, data, hora)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);

        $data = date('Y-m-d');
        $hora = date('H:i:s');

        $executado = $stmt->execute([
            $dados['instrutor'],
            $dados['avaliacao'],
            $dados['pergunta'],
            $dados['resposta'],
            $data,
            $hora
        ]);

        return $executado ? true : false;
    }

    public static function atualizarBanco($sql)
    {
        $db = Database::connect();

        // 🔒 Permitir apenas SELECT
        if (stripos(trim($sql), 'update') !== 0) {
            throw new Exception('Apenas UPDATE permitido');
        }

        // 🔒 Bloquear comandos perigosos
        $bloqueados = ['insert', 'select', 'delete', 'drop', 'alter', 'truncate', '--', ';'];

        foreach ($bloqueados as $palavra) {
            if (stripos($sql, $palavra) !== false) {
                throw new Exception('SQL contém comando não permitido');
            }
        }

        // Executa a query
        $stmt = $db->query($sql);
        $linhas_afetadas = $stmt->rowCount();

        if ($linhas_afetadas > 0) {
            // ✅ Atualizou
            return true;
        } else {
            // ❌ Não atualizou (ou já estava com mesmo valor)
            return false;
        }
    }

    public static function salvarVinculo($data)
    {
        try {
            $db = Database::connect();

            $sql = "INSERT INTO batizado 
                (inicio, fim, padrinho, afilhado)
                VALUES (:inicio, :fim, :padrinho, :afilhado)";

            $stmt = $db->prepare($sql);

            $executado = $stmt->execute([
                ':inicio' => $data['inicio'],
                ':fim' => $data['fim'],
                ':padrinho' => $data['padrinho_id'],
                ':afilhado' => $data['afilhado_id']
            ]);

            return $executado ? true : false;

        } catch (PDOException $e) {
            return false;
        }
    }

}