<?php

class ProductController
{

    public function __construct(private ProductGateway $gateway)
    {
        
    }
    public function processRequest(string $method, ?string $id): void
    {
        if($id){

            $this->processResourceRequest($method,$id);

        }else{

            $this->processCollectionRequest($method);

        }
    }

    private function processResourceRequest(string $method, string $id): void
    {
        $product = $this->gateway->get($id);

        if(!$product) {
            http_response_code(404);

            echo json_encode(["message" => "Producto no encontrado."]);

            return;
        }

        echo json_encode($product);
    }

    private function processCollectionRequest(string $method): void
    {
        switch($method){
            case "GET":
                echo json_encode($this->gateway->getAll());
                break;
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);

                $errors = $this->getValidationErrors($data);
                
                if( ! empty($errors)){
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                $id = $this->gateway->create($data);

                http_response_code(201);

                echo json_encode([
                    "message" => "Producto creado",
                    "id" => $id
                ]);
                    break;
            default:
                http_response_code(405);
                header("Allow: GET");
        }
    }

    private function getValidationErrors(array $data): array
    {
        $errors = [];
        if(empty($data["name"])){
            $errors[] = "Nombre del producto requerido";
        }

        if(array_key_exists("code",$data)){
            if(filter_var($data["code"], FILTER_VALIDATE_INT) === false){
                $errors[] = "codigo de barras debe ser un entero";
            }
        }

        return $errors;
    }
}