<?php

declare(strict_types=1);

spl_autoload_register(function($class) {
    require __DIR__ . "/src/$class.php";
});

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

header(("Content-type: application/json; charset=UTF-8"));

$parts = explode("/",$_SERVER["REQUEST_URI"]);

$database = new Database("127.0.0.1","3306","tienda","root","");

$id = $parts[4] ?? null;

switch ($parts[3]) {
    case 'products':

        $gateway = new ProductGateway($database);

        $controller = new ProductController($gateway);

        $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);

        break;

    case 'vendors':

        $gateway = new VendorGateway($database);

        $controller = new VendorController($gateway);

        $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);

        break;

    case 'categories':

        $gateway = new CatGateway($database);

        $controller = new CatController($gateway);

        $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);

        break;
    case 'brands':

        $gateway = new BrandGateway($database);

        $controller = new BrandController($gateway);

        $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);

        break;
    
    default:
    http_response_code(404);
    exit; 
        break;
}