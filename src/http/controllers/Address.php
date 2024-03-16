<?php  declare(strict_types = 1);

namespace ApiCustomerManager\http\controllers;

use ApiCustomerManager\actions\address\StoreAddress;
use ApiCustomerManager\actions\address\UpdateAddress;
use ApiCustomerManager\actions\address\DeleteAddress;

class Address
{
    public function store()
    {
        try {
            // Obtenha o corpo da requisição JSON
            $json = file_get_contents('php://input');

            // Decodifique o JSON em um array associativo
            $dataJson = json_decode($json, true);

            $storeClient = (new StoreAddress())->handle($dataJson);

            echo $storeClient;
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }
    }

    public function update(int $id)
    {
        try {
            // Obtenha o corpo da requisição JSON
            $json = file_get_contents('php://input');

            // Decodifique o JSON em um array associativo
            $dataJson = json_decode($json, true);

            $updateClient = (new UpdateAddress())->handle($id, $dataJson);

            echo $updateClient;
        } catch (\Exception $exception) {
            return [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    public function destroy(int $id)
    {
        try {
            $showClient = (new DeleteAddress())->handle($id);

            echo $showClient;
        } catch (\Exception $exception) {
            return [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

}