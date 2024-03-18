<?php

namespace ApiCustomerManager\actions\address;

use ApiCustomerManager\database\InstanceDatabase;
use PDO;
use PDOException;

class UpdateAddress
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
                throw new PDOException("Nenhuma informação fornecida para atualizar o endereço.");
            }

            // Verificar se o endereço com o ID fornecido existe
            $existingAddress = $pdo->prepare("SELECT * FROM address WHERE id = ?");
            $existingAddress->execute([$id]);

            $address = $existingAddress->fetch(PDO::FETCH_ASSOC);

            if (!$address) {
                throw new PDOException("Endereço não encontrado.");
            }

            // Preparar e executar a consulta SQL para atualizar o cliente
            $sql = $pdo->prepare("UPDATE address 
                                  SET street = :street, 
                                      number = :number
                                  WHERE id = $id");

            $sql->bindParam(':street', $data['street']);
            $sql->bindParam(':number', $data['number']);
            $sql->execute();

            // Verificar se a consulta foi bem-sucedida
            if (!$sql) {
                throw new PDOException("Falha ao executar a consulta de atualização: " . $pdo->errorInfo()[2]);
            }

            return json_encode([
                'code' => 200,
                'data' => $data,
                'message' => "Endereço atualizado com sucesso!"
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
