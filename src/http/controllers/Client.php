<?php declare(strict_types=1);

namespace ApiCustomerManager\http\controllers;

use ApiCustomerManager\actions\clients\DeleteClient;
use ApiCustomerManager\actions\clients\ListClient;
use ApiCustomerManager\actions\clients\ShowClient;
use ApiCustomerManager\actions\clients\StoreClient;
use ApiCustomerManager\actions\clients\UpdateClient;

class Client
{

    public function index()
    {
        try {
            $listClient = (new ListClient())->handle();
            
            echo $listClient;
        } catch (\Exception $exception) {
            return [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    public function store()
    {
        try {
            // Obtenha o corpo da requisição JSON
            $json = file_get_contents('php://input');

            // Decodifique o JSON em um array associativo
            $dataJson = json_decode($json, true);

            $storeClient = (new StoreClient())->handle($dataJson);

            echo $storeClient;
        } catch (\Exception $exception) {
            return [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    public function show(int $id)
    {
        try {
            $showClient = (new ShowClient())->handle($id);

            echo $showClient;
        } catch (\Exception $exception) {
            return [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    public function update(int $id)
    {
        try {
            // Obtenha o corpo da requisição JSON
            $json = file_get_contents('php://input');

            // Decodifique o JSON em um array associativo
            $dataJson = json_decode($json, true);

            $updateClient = (new UpdateClient())->handle($id, $dataJson);

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
            $showClient = (new DeleteClient())->handle($id);

            echo $showClient;
        } catch (\Exception $exception) {
            return [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

}