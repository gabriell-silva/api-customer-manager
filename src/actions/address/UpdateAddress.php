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
            $db = new InstanceDatabase();

            // Conectar ao banco de dados
            $pdo = $db->connection();

            // Verificar se a conexão foi mal-sucedida
            if (!$pdo) {
                throw new PDOException("Falha ao conectar com o banco de dados.");
            }

            // Verificar se há dados fornecidos
            if (empty($data)) {
                throw new PDOException("Nenhuma informação fornecida para atualizar o endereço.");
            }

            // Verificar se o endereço com o ID fornecido existe
            $existingClient = $pdo->prepare("SELECT * FROM address WHERE id = ?");
            $existingClient->execute([$id]);

            $address = $existingClient->fetch(PDO::FETCH_ASSOC);

            if (!$address) {
                throw new PDOException("Cliente com o ID fornecido não existe.");
            }

            // Preparar e executar a consulta SQL para atualizar o cliente
            $sql = $pdo->prepare("UPDATE address 
                                  SET street = :street, 
                                      number_home = :number_home
                                  WHERE id = $id");

            $sql->bindParam(':street', $data['street']);
            $sql->bindParam(':number_home', $data['number_home']);
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
