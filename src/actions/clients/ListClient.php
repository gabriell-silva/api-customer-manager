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
            $db = new InstanceDatabase();

            // Conectar ao banco de dados
            $pdo = $db->connection();

            // Verificar se a conexão foi mal-sucedida
            if (!$pdo) {
                throw new PDOException("Falha ao conectar com banco de dados.");
            }

            // Preparar e executar a consulta SQL
            $sql = $pdo->query("
                SELECT clients.*, address.street
                    FROM clients
                    INNER JOIN address
                    ON clients.address_id = address.id
                    ORDER BY clients.id ASC
            ");

            // Verificar se a consulta foi mal-sucedida
            if (!$sql) {
                throw new PDOException("Falha ao executar consulta: " . $pdo->errorInfo()[2]);
            }

            // Buscar os resultados da consulta
            $clients = $sql->fetchAll(PDO::FETCH_ASSOC);

            // Verificar se há resultados
            if (empty($clients)) {
                throw new PDOException("Nenhum cliente cadastrado.");
            }

           return json_encode([
               'code' => 200,
               'data' => $clients,
               'message' => 'clientes listados, com sucesso!'
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