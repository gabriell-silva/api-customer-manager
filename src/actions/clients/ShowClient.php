<?php

namespace ApiCustomerManager\actions\clients;

use ApiCustomerManager\database\InstanceDatabase;
use PDO;
use PDOException;

class ShowClient
{
    public function handle(int $id)
    {
        try {

            // Criar uma instância do banco de dados
            $pdo = (new InstanceDatabase())->connection();

            // Verificar se a conexão foi mal-sucedida
            if (!$pdo) {
                throw new PDOException("Falha ao conectar com banco de dados.");
            }

            // Preparar e executar a consulta SQL para obter os dados do cliente
            $sql = $pdo->prepare("
                SELECT clients.*, address.id as address_id, address.street, address.number
                FROM clients
                LEFT JOIN client_address ON clients.id = client_address.client_id
                LEFT JOIN address ON client_address.address_id = address.id
                WHERE clients.id = :id
                ORDER BY clients.id ASC
            ");
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            $sql->execute();

            // Verificar se a consulta foi mal-sucedida
            if (!$sql) {
                throw new PDOException("Falha ao executar consulta: " . $pdo->errorInfo()[2]);
            }

            // Buscar os resultados da consulta
            $clientData = $sql->fetch(PDO::FETCH_ASSOC);

            // Verificar se há resultados
            if (empty($clientData)) {
                throw new PDOException("Cliente não encontrado.");
            }

            // Preparar e executar a consulta SQL para obter os endereços do cliente
            $sqlAddresses = $pdo->prepare("
                SELECT address.id, address.street, address.number
                FROM address
                INNER JOIN client_address ON address.id = client_address.address_id
                WHERE client_address.client_id = :id
            ");
            $sqlAddresses->bindParam(':id', $id, PDO::PARAM_INT);
            $sqlAddresses->execute();

            // Buscar os endereços do cliente
            $addresses = $sqlAddresses->fetchAll(PDO::FETCH_ASSOC);

            // Adicionar os endereços aos dados do cliente
            $clientData['addresses'] = $addresses;

            return json_encode([
                'code' => 200,
                'data' => $clientData,
                'message' => "Cliente listado com sucesso!"
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
