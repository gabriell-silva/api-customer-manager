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
            $pdo = (new InstanceDatabase())->connection();

            // Verificar se a conexão foi mal-sucedida
            if (!$pdo) {
                throw new PDOException("Falha ao conectar com o banco de dados.");
            }

            // Verificar se há dados fornecidos
            if (empty($data)) {
                throw new PDOException("Nenhuma informação fornecida para atualizar o cliente.");
            }

            // Verificar se o cliente já existe
            $existingClient = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
            $existingClient->execute([$id]);
            $client = $existingClient->fetch(PDO::FETCH_ASSOC);

            if (!$client) {
                throw new PDOException("Cliente já existe.");
            }

            // Atualizar endereços
            if (isset($data['addresses'])) {
                foreach ($data['addresses'] as $address) {
                    $sql = $pdo->prepare("UPDATE address SET street = :street, number = :number WHERE id = :id");
                    $sql->bindParam(':street', $address['street']);
                    $sql->bindParam(':number', $address['number']);
                    $sql->bindParam(':id', $address['id']);
                    $sql->execute();

                    if (!$sql) {
                        throw new PDOException("Falha ao atualizar o endereço: " . $pdo->errorInfo()[2]);
                    }
                }
            }

            // Preparar e executar a consulta SQL para atualizar o cliente
            $sql = $pdo->prepare("UPDATE clients 
                                  SET name = :name, 
                                      date_birth = :date_birth, 
                                      document_cpf = :document_cpf, 
                                      document_rg = :document_rg, 
                                      phone_number = :phone_number
                                  WHERE id = :client_id");

            $sql->bindParam(':name', $data['name']);
            $sql->bindParam(':date_birth', $data['date_birth']);
            $sql->bindParam(':document_cpf', $data['document_cpf']);
            $sql->bindParam(':document_rg', $data['document_rg']);
            $sql->bindParam(':phone_number', $data['phone_number']);
            $sql->bindParam(':client_id', $id);
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
