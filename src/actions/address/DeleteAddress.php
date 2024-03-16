<?php

namespace ApiCustomerManager\actions\address;

use ApiCustomerManager\database\InstanceDatabase;
use PDO;
use PDOException;

class DeleteAddress
{
    public function handle(int $id)
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
            $sql = $pdo->prepare("SELECT * FROM address WHERE id = $id");
            $sql->execute();

            // Verificar se a consulta foi mal-sucedida
            if (!$sql) {
                throw new PDOException("Falha ao executar consulta: " . $pdo->errorInfo()[2]);
            }

            // Resultado da consulta
            $client = $sql->fetch(PDO::FETCH_ASSOC);

            if (!empty($client)) {
                $sql = $pdo->prepare("DELETE FROM address WHERE id = $id");
                $sql->execute();
            }

            // Verificar se há resultados
            if (empty($client)) {
                throw new PDOException("endereço não encontrado.");
            }

           return json_encode([
               'code' => 200,
               'data' => [],
               'message' => 'Endereço excluído, com sucesso!'
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