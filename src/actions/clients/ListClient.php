<?php

namespace ApiCustomerManager\actions\clients;

use ApiCustomerManager\database\InstanceDatabase;
use PDO;
use PDOException;

class ListClient
{
    public function handle()
    {
        try {
            // Criar uma instância do banco de dados
            $pdo = (new InstanceDatabase())->connection();

            // Verificar se a conexão foi mal-sucedida
            if (!$pdo) {
                throw new PDOException("Falha ao conectar com banco de dados.");
            }

            // Preparar e executar a consulta SQL
            $sql = $pdo->query("
                SELECT clients.*, address.id as address_id, address.street, address.number
                FROM clients
                LEFT JOIN client_address ON clients.id = client_address.client_id
                LEFT JOIN address ON client_address.address_id = address.id
                ORDER BY clients.id ASC
            ");


            // Verificar se a consulta foi mal-sucedida
            if (!$sql) {
                throw new PDOException("Falha ao executar consulta: " . $pdo->errorInfo()[2]);
            }

            // Buscar os resultados da consulta
            $clients = [];
            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                $clientId = $row['id'];
                if (!isset($clients[$clientId])) {
                    $clients[$clientId] = [
                        'id' => $clientId,
                        'name' => $row['name'],
                        'date_birth' => $row['date_birth'],
                        'document_cpf' => $row['document_cpf'],
                        'document_rg' => $row['document_rg'],
                        'phone_number' => $row['phone_number'],
                        'addresses' => []
                    ];
                }

                if (!is_null($row['address_id'])) {
                    $clients[$clientId]['addresses'][] = [
                        'id' => $row['address_id'],
                        'street' => $row['street'],
                        'number' => $row['number']
                    ];
                }
            }

            // Verificar se há resultados
            if (empty($clients)) {
                throw new PDOException("Nenhum cliente cadastrado.");
            }

            return json_encode([
                'code' => 200,
                'data' => array_values($clients),
                'message' => 'Clientes listados com sucesso!'
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
