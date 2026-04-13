<?php

require_once 'core/Database.php';

class GlobalModel
{
    public static function executarsqlReturnAssoc($sql)
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

    public static function executarSqlReturnObject($sql)
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

    public static function executarsqlReturnArrayList($sql)
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

    public static function executarCriarAvaliacao($data)
    {
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
    }

    public static function executarExcluirAvaliacao($data)
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

    public static function insertResposta($quem_avalia, $avaliacao, $pergunta, $resposta)
    {
        $db = Database::connect();

        $sql = "INSERT INTO respostas 
                (quem_avalia, avaliacao, pergunta, resposta, data, hora)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);

        $data = date('Y-m-d');
        $hora = date('H:i:s');

        $executado = $stmt->execute([
            $quem_avalia,
            $avaliacao,
            $pergunta,
            $resposta,
            $data,
            $hora
        ]);

        return $executado ? true : false;
    }

    public static function executarsqlUpdate($sql)
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

    public static function executarUpdatePergunta($data)
    {
        try {
            $db = Database::connect();

            $sql = "UPDATE perguntas SET 
                pergunta = :pergunta,
                descricao = :descricao,
                modo = :modo,
                perfil = :perfil,
                categoria = :categoria,
                subcategoria = :subcategoria
                WHERE id = :id";

            $stmt = $db->prepare($sql);

            $stmt->bindParam(':id', $data['id']);
            $stmt->bindParam(':pergunta', $data['pergunta']);
            $stmt->bindParam(':descricao', $data['descricao']);
            $stmt->bindParam(':modo', $data['modo']);
            $stmt->bindParam(':perfil', $data['perfil']);
            $stmt->bindParam(':categoria', $data['categoria']);
            $stmt->bindParam(':subcategoria', $data['subcategoria']);

            $stmt->execute();
            return $stmt->rowCount();

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function executarAdicionarPergunta($data)
    {
        try {
            $db = Database::connect();

            $sql = "INSERT INTO perguntas 
                (pergunta, descricao, modo, perfil, categoria, subcategoria)
                VALUES (:pergunta, :descricao, :modo, :perfil, :categoria, :subcategoria)";

            $stmt = $db->prepare($sql);

            $executado = $stmt->execute([
                ':pergunta' => $data['pergunta'],
                ':descricao' => $data['descricao'],
                ':modo' => $data['modo'],
                ':perfil' => $data['perfil'],
                ':categoria' => $data['categoria'],
                ':subcategoria' => $data['subcategoria']
            ]);

            return $executado ? true : false;

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function executarSalvarVinculo($data)
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

    /*
    public static function executarSalvarVinculo($data)
    {
        try {
            $db = Database::connect(); 

            // Inicia transação
            $db->beginTransaction();

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

            if (!$executado) {
                $db->rollBack();
                return false;
            }

            $batizado = $db->lastInsertId();

            if (!$batizado) {
                $db->rollBack();
                return false;
            }

            // INSERT 1
            $sql = "INSERT INTO avaliar 
                (batizado, liberar, tipo, perguntas)
                VALUES (:batizado, :liberar, 'pa', '1')";
            $stmt = $db->prepare($sql);

            $executado_a = $stmt->execute([
                ':batizado' => $batizado,
                ':liberar' => $data['fim']
            ]);

            // INSERT 2
            $sql = "INSERT INTO avaliar 
                (batizado, liberar, tipo, perguntas)
                VALUES (:batizado, :liberar, 'ap', '1')";
            $stmt = $db->prepare($sql);

            $executado_b = $stmt->execute([
                ':batizado' => $batizado,
                ':liberar' => $data['fim']
            ]);

            // Validação completa
            if ($executado_a && $executado_b) {
                $db->commit();
                return true;
            } else {
                $db->rollBack();
                return false;
            }

        } catch (PDOException $e) {
            // Segurança extra
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            return false;
        }
    }
    */
}