<?php

require_once 'core/Database.php';

class PadrinhoModel
{
    public static function getAll()
    {
        try {
            $db = Database::connect();

            $stmt = $db->prepare("SELECT * FROM padrinhos ORDER BY id DESC");
            $stmt->execute();

            return $stmt->fetchAll();

        } catch (PDOException $e) {
            return false;
        }
    }

   public static function getAfilhados($padrinho)
    {
        try {
            $db = Database::connect();

            $sql = "SELECT 
            DATE_FORMAT(a.inicio, '%d/%m/%Y') as inicio, 
            DATE_FORMAT(a.fim, '%d/%m/%Y') as fim, 
                a.afilhado, 
                b.nome,
                a.id as batizado_id
            FROM batizado a  
            INNER JOIN afilhados b 
                ON a.afilhado = b.id 
            WHERE a.padrinho = :padrinho 
            AND a.fim < CURDATE() 
            ORDER BY a.id DESC";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':padrinho', $padrinho, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function getById($id)
    {
        try {
            $db = Database::connect();

            $stmt = $db->prepare("SELECT * FROM padrinhos WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch();

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function create($data)
    {
        try {
            $db = Database::connect();

            $sql = "INSERT INTO padrinhos 
                (nome, matricula, data_nascimento, sexo, telefone, email, user, senha) 
                VALUES 
                (:nome, :matricula, :data_nascimento, :sexo, :telefone, :email, :user, :senha)";

            $stmt = $db->prepare($sql);

    
            $stmt->bindParam(':nome', $data['nome']);
            $stmt->bindParam(':matricula', $data['matricula']);
            $stmt->bindParam(':data_nascimento', $data['data_nascimento']);
            $stmt->bindParam(':sexo', $data['sexo']);
            $stmt->bindParam(':telefone', $data['telefone']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':user', $data['user']);
            $stmt->bindParam(':senha', $data['senha']);

            $stmt->execute();

            return $db->lastInsertId();

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function update($data)
    {
        try {
            $db = Database::connect();

            $sql = "UPDATE padrinhos SET 
                nome = :nome,
                matricula = :matricula,
                data_nascimento = :data_nascimento,
                sexo = :sexo,
                telefone = :telefone,
                email = :email
                WHERE id = :id";

            $stmt = $db->prepare($sql);

            $stmt->bindParam(':id', $data['id']);
            $stmt->bindParam(':nome', $data['nome']);
            $stmt->bindParam(':matricula', $data['matricula']);
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

    public static function delete($id)
    {
        try {
            $db = Database::connect();

            $stmt = $db->prepare("DELETE FROM padrinhos WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->rowCount();

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function login($user, $senha)
    {
        try {
            $db = Database::connect();

            // 🔎 Busca o padrinho
            $stmt = $db->prepare("SELECT id, nome, senha FROM padrinhos WHERE user = :user LIMIT 1");
            $stmt->bindParam(':user', $user);
            $stmt->execute();

            $padrinho = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($padrinho) {

                // 🔐 Validação de senha 
                if ($padrinho['senha'] === $senha) {

                    // 🕒 Atualiza último acesso
                    $update = $db->prepare("UPDATE padrinhos SET ultimo_acesso = NOW() WHERE id = :id");
                    $update->execute([
                        ':id' => $padrinho['id']
                    ]);

                    // 🔎 Busca batizado
                    $sql = "SELECT 
                                DATE_FORMAT(a.inicio, '%d/%m/%Y') as inicio, 
                                DATE_FORMAT(a.fim, '%d/%m/%Y') as fim, 
                                a.afilhado, 
                                b.nome, 
                                a.id 
                            FROM batizado a 
                            INNER JOIN afilhados b 
                                ON a.afilhado = b.id 
                            WHERE a.padrinho = :padrinho 
                            AND DATE(a.fim) >= CURDATE() 
                            ORDER BY a.id DESC
                            LIMIT 1";

                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':padrinho', $padrinho['id'], PDO::PARAM_INT);
                    $stmt->execute();
                    $afilhado = $stmt->fetch(PDO::FETCH_ASSOC);

                    // 🎯 Se encontrou batizado
                    if ($afilhado) {
                        return [
                            'batizado' => true,
                            'batizado_id' =>$afilhado['id'],
                            'padrinho_id' => $padrinho['id'],
                            'padrinho_nome' => $padrinho['nome'],
                            'afilhado_id' => $afilhado['afilhado'], 
                            'afilhado_nome' => $afilhado['nome'],
                            'inicio' => $afilhado['inicio'],
                            'fim' => $afilhado['fim']
                        ];
                    }

                    // ❌ Se NÃO encontrou batizado
                    return [
                        'batizado' => false,
                        'padrinho_id' => $padrinho['id'],
                        'padrinho_nome' => $padrinho['nome']
                    ];
                }
            }

            return false;

        } catch (PDOException $e) {
            return false;
        }
    }
}