<?php

class VendorGateway
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll()
    {
        $sql = "SELECT *
                FROM vendor";

        $stmt = $this->conn->query($sql);

        $data = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            //Hacemos cast para valores booleanos
            //$row["columna"] = (bool) $row["columna"];

            $data[] = $row;

        }

        return $data;
    }

    public function get(string $id)
    {
        $sql = "SELECT * 
                FROM vendor
                WHERE ID = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id",$id,PDO::PARAM_STR);

        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            $data[] = $row;

        }

        return $data;
    }

    public function create(array $data){
        $sql = "INSERT INTO vendor (name, email, telefono, address, remarks) 
        values (:name, :email, :tel, :address, :remarks)";

        $stmt = $this->conn->prepare($sql);
        //code y reference son lo mismo
        $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);
        $stmt->bindValue(":email", $data["email"], PDO::PARAM_STR);
        $stmt->bindValue(":tel", $data["telefono"], PDO::PARAM_STR);
        $stmt->bindValue(":address", $data["address"], PDO::PARAM_INT);
        $stmt->bindValue(":remarks", $data["remarks"], PDO::PARAM_INT);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }
}