<?php

namespace ApiCustomerManager\actions\address;

use ApiCustomerManager\database\InstanceDatabase;
use PDO;
use PDOException;

class StoreAddress
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

            $sql = $pdo->prepare("INSERT INTO address (street, number_home) VALUES (:street, :number_home)");
            $sql->bindParam(':street', $data['street']);
            $sql->bindParam(':number_home', $data['number_home']);
            $sql->execute();

            // Verificar se a consulta foi mal-sucedida
            if (!$sql) {
                throw new PDOException("Falha ao executar a consulta: " . $pdo->errorInfo()[2]);
            }

            return json_encode([
                'code' => 200,
                'data' => $data,
                'message' => "Endereço cadastrado com sucesso!"
            ]);
        } catch (PDOException $exception) {
            // Capturar e lidar com exceções
            return json_encode([
                'code'=> $exception->getCode(),
                'message' => $exception->getMessage()
            ]);
        }
    }
}
