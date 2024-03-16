<?php

namespace ApiCustomerManager\actions\users;

use ApiCustomerManager\database\InstanceDatabase;
use Exception;
use PDO;
use PDOException;

class SignOut
{
    public function handle($token)
    {
        try {
            // Criar uma instância do banco de dados
            $db = new InstanceDatabase();

            // Conectar ao banco de dados
            $pdo = $db->connection();

            // Verificar se a conexão foi mal-sucedida
            if (!$pdo) {
                throw new PDOException("Falha ao conectar com banco de dados.");
            }

            // Procurar o usuário pelo token
            $sql = $pdo->prepare("SELECT * FROM users WHERE token = :token");
            $sql->bindParam(':token', $token['token']);
            $sql->execute();

            // Verificar se o token é válido
            $user = $sql->fetch(PDO::FETCH_ASSOC);
            if (!$user) {
                throw new PDOException("Token inválido. O usuário não está autenticado.");
            }

            // Limpar o token do usuário no banco de dados
            $clearTokenSql = $pdo->prepare("UPDATE users SET token = NULL WHERE token = :token");
            $clearTokenSql->bindParam(':token', $token['token']);
            $clearTokenSql->execute();

            if (!$clearTokenSql) {
                throw new PDOException("Falha ao limpar token de usuário.");
            }

            return json_encode([
                'code' => 200,
                'message' => "Logout bem-sucedido!"
            ]);

        } catch (Exception $exception) {
            // Capturar e lidar com exceções
            return json_encode([
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ]);
        }
    }
}