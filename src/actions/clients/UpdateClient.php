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

            // Verificar se o cliente já existe
            $existingClient = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
            $existingClient->execute([$id]);
            $client = $existingClient->fetch(PDO::FETCH_ASSOC);

            if (!$client) {
                throw new PDOException("Cliente não encontrado.");
            }

            // Atualizar ou criar endereços
            if (isset($data['addresses']) && is_array($data['addresses'])) {
                $this->updateAddresses($pdo, $id, $data['addresses']);
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

    private function updateAddresses(PDO $pdo, int $clientId, array $addresses)
    {
        foreach ($addresses as $address) {
            if (!isset($address['id'])) {
                $sql = $pdo->prepare("INSERT INTO address (street, number) VALUES (:street, :number)");
                $sql->bindParam(':street', $address['street']);
                $sql->bindParam(':number', $address['number']);
                $sql->execute();

                if (!$sql) {
                    throw new PDOException("Falha ao inserir o novo endereço: " . $pdo->errorInfo()[2]);
                }

                $addressId = $pdo->lastInsertId();

                // Relacionar o endereço ao cliente
                $sql = $pdo->prepare("INSERT INTO client_address (client_id, address_id) VALUES (:client_id, :address_id)");
                $sql->bindParam(':client_id', $clientId);
                $sql->bindParam(':address_id', $addressId);
                $sql->execute();

                if (!$sql) {
                    throw new PDOException("Falha ao relacionar o endereço ao cliente: " . $pdo->errorInfo()[2]);
                }
            } else {
                if (isset($address['removed']) && $address['removed'] === true) {
                    // Remover o relacionamento do endereço com o cliente
                    $sql = $pdo->prepare("DELETE FROM client_address WHERE client_id = :client_id AND address_id = :address_id");
                    $sql->bindParam(':client_id', $clientId);
                    $sql->bindParam(':address_id', $address['id']);
                    $sql->execute();

                    if (!$sql) {
                        throw new PDOException("Falha ao remover o relacionamento do endereço com o cliente: " . $pdo->errorInfo()[2]);
                    }
                } else {
                    // Atualizar o endereço
                    $sql = $pdo->prepare("UPDATE address SET street = :street, number = :number WHERE id = :address_id");
                    $sql->bindParam(':street', $address['street']);
                    $sql->bindParam(':number', $address['number']);
                    $sql->bindParam(':address_id', $address['id']);
                    $sql->execute();

                    if (!$sql) {
                        throw new PDOException("Falha ao atualizar o endereço: " . $pdo->errorInfo()[2]);
                    }
                }
            }
        }
    }
}
