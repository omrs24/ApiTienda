<?php

class VendorController
{

    public function __construct(private VendorGateway $gateway)
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
        $vendor = $this->gateway->get($id);

        if(!$vendor) {
            http_response_code(404);

            echo json_encode(["message" => "Proovedor no encontrado."]);

            return;
        }

        echo json_encode($vendor);
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
                    "message" => "Proveedor creado",
                    "id" => $id
                ]);
                    break;
            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }

    private function getValidationErrors(array $data): array
    {
        $errors = [];
        if(empty($data["name"])){
            $errors[] = "Nombre del proveedor requerido";
        }

        /*if(array_key_exists("code",$data)){
            if(filter_var($data["code"], FILTER_VALIDATE_INT) === false){
                $errors[] = "codigo de barras debe ser un entero";
            }
        }*/

        return $errors;
    }
}