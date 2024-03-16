<?php

namespace ApiCustomerManager\actions\clients;

use ApiCustomerManager\database\InstanceDatabase;
use PDO;
use PDOException;

class StoreClient
{
    public function handle($data)
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

            if (empty($data)) {
                throw new PDOException("Sem informações para efetuar o registro.");
            }

            // Inserir primeiro o endereço
            $sql = $pdo->prepare("INSERT INTO address (street, number_home) VALUES (:street, :number_home)");
            $sql->bindParam(':street', $data['street']);
            $sql->bindParam(':number_home', $data['number_home']);
            $sql->execute();

            // Verificar se a inserção do endereço foi bem-sucedida
            if (!$sql) {
                throw new PDOException("Falha ao inserir o endereço: " . $pdo->errorInfo()[2]);
            }

            // Obter o ID do endereço inserido
            $addressId = $pdo->lastInsertId();

            // Inserir o cliente usando o ID do endereço
            $sql = $pdo->prepare("INSERT INTO clients 
                                            (name, date_birth, document_cpf, document_rg, phone_number, address_id)
                                        VALUES
                                            (:name, :date_birth, :document_cpf, :document_rg, :phone_number, $addressId)");

            $sql->bindParam(':name', $data['name']);
            $sql->bindParam(':date_birth', $data['date_birth']);
            $sql->bindParam(':document_cpf', $data['document_cpf']);
            $sql->bindParam(':document_rg', $data['document_rg']);
            $sql->bindParam(':phone_number', $data['phone_number']);
            $sql->execute();

            // Verificar se a consulta foi mal-sucedida
            if (!$sql) {
                throw new PDOException("Falha ao executar a consulta: " . $pdo->errorInfo()[2]);
            }

            return json_encode([
                'code' => 200,
                'data' => $data,
                'message' => "Cliente cadastrado com sucesso!"
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
