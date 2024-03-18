<?php

namespace ApiCustomerManager\actions\users;

use ApiCustomerManager\database\InstanceDatabase;
use PDO;
use PDOException;

class SignIn
{
    public function handle($data)
    {
        try {
            // Criar uma instância do banco de dados
            $pdo = (new InstanceDatabase())->connection();

            // Verificar se a conexão foi mal-sucedida
            if (!$pdo) {
                throw new PDOException("Falha ao conectar com banco de dados.");
            }

            // Preparar e executar a consulta SQL para verificar as credenciais do usuário
            $sql = $pdo->prepare("SELECT * 
                                        FROM users
                                        WHERE username = :username 
                                        AND password = :password");
            $sql->bindParam(':username', $data['username']);
            $sql->bindParam(':password', $data['password']);
            $sql->execute();

            // Verificar se as credenciais são válidas
            $user = $sql->fetch(PDO::FETCH_ASSOC);
            if (!$user) {
                throw new PDOException("Credenciais inválidas. Por favor, verifique seu nome de usuário ou senha.");
            }

            // Gerar um token simples
            $token = bin2hex(random_bytes(16)); // Gera um token aleatório de 32 caracteres hexadecimal

            // Atualizar o token do usuário no banco de dados
            $updateTokenSql = $pdo->prepare("UPDATE users
                                                   SET token = :token
                                                   WHERE id = :id");
            $updateTokenSql->bindParam(':token', $token);
            $updateTokenSql->bindParam(':id', $user['id']);
            $updateTokenSql->execute();

            if (!$updateTokenSql) {
                throw new PDOException("Falha ao atualizar token de usuário.");
            }

            return json_encode([
                'code' => 200,
                'message' => "Login bem-sucedido!",
                'token' => $token
            ]);
        } catch (PDOException $exception) {
            // Capturar e lidar com exceções
            return json_encode([
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ]);
        }
    }
}