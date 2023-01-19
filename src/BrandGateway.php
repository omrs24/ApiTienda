<?php

class BrandGateway
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll()
    {
        $sql = "SELECT *
                FROM marca";

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
                FROM marca
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
        $sql = "INSERT INTO vendor (name, remarks) 
        values (:name, :remarks)";

        $stmt = $this->conn->prepare($sql);
        //code y reference son lo mismo
        $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);
        $stmt->bindValue(":remarks", $data["remarks"], PDO::PARAM_INT);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }
}