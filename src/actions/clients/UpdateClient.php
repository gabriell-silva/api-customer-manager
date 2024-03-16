<?php

namespace ApiCustomerManager\actions\clients;

use ApiCustomerManager\database\InstanceDatabase;
use PDO;
use PDOException;

class UpdateClient
{
    public function handle(int $id, array $data)
    {
        try {
            // Criar uma instância do banco de dados
            $db = new InstanceDatabase();

            // Conectar ao banco de dados
            $pdo = $db->connection();

            // Verificar se a conexão foi mal-sucedida
            if (!$pdo) {
                throw new PDOException("Falha ao conectar com o banco de dados.");
            }

            // Verificar se há dados fornecidos
            if (empty($data)) {
                throw new PDOException("Nenhuma informação fornecida para atualizar o cliente.");
            }

            // Verificar se o cliente com o ID fornecido existe
            $existingClient = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
            $existingClient->execute([$id]);
            $client = $existingClient->fetch(PDO::FETCH_ASSOC);

            if (!$client) {
                throw new PDOException("Cliente com o ID fornecido não existe.");
            }

            // Preparar e executar a consulta SQL para atualizar o cliente
            $sql = $pdo->prepare("UPDATE clients 
                                  SET name = :name, 
                                      date_birth = :date_birth, 
                                      document_cpf = :document_cpf, 
                                      document_rg = :document_rg, 
                                      phone_number = :phone_number, 
                                      address_id = :address_id
                                  WHERE id = $id");

            $sql->bindParam(':name', $data['name']);
            $sql->bindParam(':date_birth', $data['date_birth']);
            $sql->bindParam(':document_cpf', $data['document_cpf']);
            $sql->bindParam(':document_rg', $data['document_rg']);
            $sql->bindParam(':phone_number', $data['phone_number']);
            $sql->bindParam(':address_id', $data['address_id']);
            $sql->execute();

            // Verificar se a consulta foi bem-sucedida
            if (!$sql) {
                throw new PDOException("Falha ao executar a consulta de atualização: " . $pdo->errorInfo()[2]);
            }

            return json_encode([
                'code' => 200,
                'data' => $data,
                'message' => "Cliente atualizado com sucesso!"
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
