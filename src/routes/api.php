<?php declare(strict_types = 1);

use ApiCustomerManager\http\controllers\Client;
use ApiCustomerManager\http\controllers\Address;
use ApiCustomerManager\http\controllers\AuthUser;

return [

    // Rotas de usuário
    ['POST', '/auth', function() {
        $controller = (new AuthUser());
        $controller->store();
    }],
    ['POST', '/logout', function() {
        $controller = new AuthUser();
        $controller->destroy();
    }],

    // Rotas de clientes
    ['GET', '/clients', function() {
        $controller = new Client();
        $controller->index();
    }],
    ['POST', '/clients', function() {
        $controller = new Client();
        $controller->store();
    }],
    ['GET', '/clients/{id}', function($args) {
        $id = (int) $args['id'];

        $controller = new Client();
        $controller->show($id);
    }],
    ['PUT', '/clients/{id}', function($args) {
        $id = (int) $args['id'];

        $controller = new Client();
        $controller->update($id);
    }],
    ['DELETE', '/clients/{id}', function($args) {
        $id = (int) $args['id'];

        $controller = new Client();
        $controller->destroy($id);
    }],

    // Rotas de endereço
    ['POST', '/address', function() {
        $controller = new Address();
        $controller->store();
    }],
    ['PUT', '/address/{id}', function($args) {
        $id = (int) $args['id'];

        $controller = new Address();
        $controller->update($id);
    }],
    ['DELETE', '/address/{id}', function($args) {
        $id = (int) $args['id'];

        $controller = new Address();
        $controller->destroy($id);
    }],
];