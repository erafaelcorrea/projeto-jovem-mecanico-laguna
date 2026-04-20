<?php

class AfilhadoModel
{
    public static function listarAfilhados()
    {
        try {
            $db = Database::connect();

            $stmt = $db->prepare("SELECT * FROM afilhados ORDER BY id DESC");
            $stmt->execute();

            return $stmt->fetchAll();

        } catch (PDOException $e) {
            return false;
        }
    }

   public static function padrinhosDoAfilhado($afilhado)
    {
        try {
            $db = Database::connect();

            $sql = "SELECT 
            DATE_FORMAT(a.inicio, '%d/%m/%Y') as inicio, 
            DATE_FORMAT(a.fim, '%d/%m/%Y') as fim, 
            DATE_FORMAT(a.afilhado_avaliou_data, '%d/%m/%Y') as afilhado_avaliou_data, 
                a.afilhado, 
                b.nome,
                a.id as batizado_id
            FROM batizado a 
            INNER JOIN padrinhos b 
                ON a.padrinho = b.id 
            INNER JOIN avaliar c
                ON a.id = c.batizado
            WHERE a.afilhado = :afilhado 
            AND a.fim < CURDATE() 
            AND c.tipo = 'pa'
            ORDER BY a.id DESC";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':afilhado', $afilhado, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function afilhado($id)
    {
        try {
            $db = Database::connect();

            $stmt = $db->prepare("SELECT * FROM afilhados WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch();

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function adicionarAfilhado($data)
    {
        try {
            $db = Database::connect();

            $sql = "INSERT INTO afilhados 
                (nome, matricula, data_nascimento, sexo, telefone, email, user, senha) 
                VALUES 
                (:nome, :matricula, :data_nascimento, :sexo, :telefone, :email, :user, :senha)";

            $stmt = $db->prepare($sql);

            // HASH DE SENHA 🔐
            $senhaHash = password_hash($data['senha'], PASSWORD_DEFAULT);

            $stmt->bindParam(':nome', $data['nome']);
            $stmt->bindParam(':matricula', $data['matricula']);
            $stmt->bindParam(':data_nascimento', $data['data_nascimento']);
            $stmt->bindParam(':sexo', $data['sexo']);
            $stmt->bindParam(':telefone', $data['telefone']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':user', $data['user']);
            $stmt->bindParam(':senha', $senhaHash);

            $stmt->execute();

            return $db->lastInsertId();

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function salvarAfilhado($id, $data)
    {
        try {
            $db = Database::connect();

            $sql = "UPDATE afilhados SET 
                nome = :nome,
                matricula = :matricula,
                cargo = :cargo,
                data_nascimento = :data_nascimento,
                sexo = :sexo,
                telefone = :telefone,
                email = :email
                WHERE id = :id";

            $stmt = $db->prepare($sql);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nome', $data['nome']);
            $stmt->bindParam(':matricula', $data['matricula']);
            $stmt->bindParam(':cargo', $data['cargo']);
            $stmt->bindParam(':data_nascimento', $data['data_nascimento']);
            $stmt->bindParam(':sexo', $data['sexo']);
            $stmt->bindParam(':telefone', $data['telefone']);
            $stmt->bindParam(':email', $data['email']);

            $stmt->execute();

            return $stmt->rowCount();

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function excluirAfilhado($id)
    {
        try {
            $db = Database::connect();

            $stmt = $db->prepare("DELETE FROM afilhados WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->rowCount();

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function loginAfilhado($user, $senha)
    {
        try {
            $db = Database::connect();

            // 🔎 Busca o padrinho
            $stmt = $db->prepare("SELECT id, nome, senha FROM afilhados WHERE user = :user LIMIT 1");
            $stmt->bindParam(':user', $user);
            $stmt->execute();

            $afilhado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($afilhado) {

                // 🔐 Validação de senha 
                if ($afilhado['senha'] === $senha) {

                    // 🕒 Atualiza último acesso
                    $update = $db->prepare("UPDATE afilhados SET ultimo_acesso = NOW() WHERE id = :id");
                    $update->execute([
                        ':id' => $afilhado['id']
                    ]);

                    // 🔎 Busca batizado
                    $sql = "SELECT 
                                DATE_FORMAT(a.inicio, '%d/%m/%Y') as inicio, 
                                DATE_FORMAT(a.fim, '%d/%m/%Y') as fim, 
                                a.padrinho, 
                                b.nome, 
                                a.id 
                            FROM batizado a 
                            INNER JOIN padrinhos b 
                                ON a.padrinho = b.id 
                            WHERE a.afilhado = :afilhado 
                            AND DATE(a.fim) >= CURDATE() 
                            ORDER BY a.id DESC
                            LIMIT 1";

                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':afilhado', $afilhado['id'], PDO::PARAM_INT);
                    $stmt->execute();
                    $padrinho = $stmt->fetch(PDO::FETCH_ASSOC);

                    // 🎯 Se encontrou batizado
                    if ($padrinho) {
                        return [
                            'batizado' => true,
                            'batizado_id' =>$padrinho['id'],
                            'afilhado_id' => $afilhado['id'],
                            'afilhado_nome' => $afilhado['nome'],
                            'padrinho_id' => $padrinho['padrinho'], 
                            'padrinho_nome' => $padrinho['nome'],
                            'inicio' => $padrinho['inicio'],
                            'fim' => $padrinho['fim']
                        ];
                    }

                    // ❌ Se NÃO encontrou batizado
                    return [
                        'batizado' => false,
                        'afilhado_id' => $afilhado['id'],
                        'afilhado_nome' => $afilhado['nome']
                    ];
                }
            }

            return false;

        } catch (PDOException $e) {
            return false;
        }
    }
}