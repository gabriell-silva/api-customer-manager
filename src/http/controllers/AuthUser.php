<?php declare(strict_types=1);

namespace ApiCustomerManager\http\controllers;

use ApiCustomerManager\actions\users\SignIn;
use ApiCustomerManager\actions\users\SignOut;
use Exception;

class AuthUser
{

    public function store()
    {
        try {
            // Obtenha o corpo da requisição JSON
            $json = file_get_contents('php://input');

            // Decodifique o JSON em um array associativo
            $dataJson = json_decode($json, true);

            $signIn = (new SignIn())->handle($dataJson);

            echo $signIn;
        } catch (Exception $exception) {
            return [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

    public function destroy()
    {
        try {
            // Obtenha o corpo da requisição JSON
            $json = file_get_contents('php://input');

            // Decodifique o JSON em um array associativo
            $dataJson = json_decode($json, true);

            $signout = (new SignOut())->handle($dataJson);

            echo $signout;
        } catch (Exception $exception) {
            return [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
        }
    }

}