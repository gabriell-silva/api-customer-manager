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
            $pdo = (new InstanceDatabase())->connection();

            // Verificar se a conexão foi mal-sucedida
            if (!$pdo) {
                throw new PDOException("Falha ao conectar com banco de dados.");
            }

            if (empty($data)) {
                throw new PDOException("Sem informações para efetuar o registro.");
            }

            // Iniciar uma transação para garantir consistência nos dados
            $pdo->beginTransaction();

            // Inserir o cliente
            $sql = $pdo->prepare("INSERT INTO clients 
                                        (name, date_birth, document_cpf, document_rg, phone_number)
                                    VALUES
                                        (:name, :date_birth, :document_cpf, :document_rg, :phone_number)");

            $sql->bindParam(':name', $data['name']);
            $sql->bindParam(':date_birth', $data['date_birth']);
            $sql->bindParam(':document_cpf', $data['document_cpf']);
            $sql->bindParam(':document_rg', $data['document_rg']);
            $sql->bindParam(':phone_number', $data['phone_number']);
            $sql->execute();

            // Verificar se a inserção do cliente foi bem-sucedida
            $clientId = $pdo->lastInsertId();
            if (!$clientId) {
                $pdo->rollBack();
                throw new PDOException("Falha ao inserir o cliente: " . $pdo->errorInfo()[2]);
            }

            // Inserir os endereços associados ao cliente na tabela de endereços
            foreach ($data['addresses'] as $address) {
                $sql = $pdo->prepare("INSERT INTO address (street, number) VALUES (:street, :number)");
                $sql->bindParam(':street', $address['street']);
                $sql->bindParam(':number', $address['number']);
                $sql->execute();

                // Verificar se a inserção do endereço foi bem-sucedida
                $addressId = $pdo->lastInsertId();
                if (!$addressId) {
                    $pdo->rollBack();
                    throw new PDOException("Falha ao inserir o endereço: " . $pdo->errorInfo()[2]);
                }

                // Relacionar o endereço ao cliente na tabela de associação client_address
                $sql = $pdo->prepare("INSERT INTO client_address (client_id, address_id) VALUES (:client_id, :address_id)");
                $sql->bindParam(':client_id', $clientId);
                $sql->bindParam(':address_id', $addressId);
                $sql->execute();

                // Verificar se a inserção da relação cliente-endereço foi bem-sucedida
                if (!$sql) {
                    $pdo->rollBack();
                    throw new PDOException("Falha ao relacionar o cliente ao endereço: " . $pdo->errorInfo()[2]);
                }
            }

            // Confirmar a transação
            $pdo->commit();

            return json_encode([
                'code' => 200,
                'data' => $data,
                'message' => "Cliente cadastrado com sucesso!"
            ]);
        } catch (PDOException $exception) {
            // Rollback da transação em caso de erro
            $pdo->rollBack();

            // Capturar e lidar com exceções
            return json_encode([
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ]);
        }
    }
}
