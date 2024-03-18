<?php

namespace ApiCustomerManager\actions\clients;

use ApiCustomerManager\database\InstanceDatabase;
use PDO;
use PDOException;

class DeleteClient
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

            // Iniciar uma transação para garantir consistência nos dados
            $pdo->beginTransaction();

            // Excluir os registros associados na tabela de associação client_address
            $sql = $pdo->prepare("DELETE FROM client_address WHERE client_id = $id");
            $sql->execute();

            // Excluir os registros da tabela clients
            $sql = $pdo->prepare("DELETE FROM clients WHERE id = $id");
            $sql->execute();

            // Confirmar a transação
            $pdo->commit();

            return json_encode([
                'code' => 200,
                'data' => [],
                'message' => 'Cliente excluído com sucesso!'
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