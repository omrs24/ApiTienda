<?php

class ProductGateway
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll()
    {
        $sql = "SELECT *
                FROM product";

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
                FROM product
                WHERE reference = :code";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":code",$id,PDO::PARAM_STR);

        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            $data[] = $row;

        }

        return $data;
    }

    public function create(array $data){
        $sql = "INSERT INTO product (code, name, reference, detail, vendorID, marca, catID, 
        cost, price1, lists, inventory, remarks, active, image, listas) 
        values (:code, :name, :reference, :detail, :vendorID, :marca, :catID, :cost, 
        :price1, '0', :inventory, :remarks, 'Y', :image, '0')";

        $stmt = $this->conn->prepare($sql);
        //code y reference son lo mismo
        $stmt->bindValue(":code", $data["code"], PDO::PARAM_STR);
        $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);
        $stmt->bindValue(":reference", $data["code"], PDO::PARAM_STR);
        $stmt->bindValue(":detail", $data["detail"], PDO::PARAM_STR);
        $stmt->bindValue(":vendorID", $data["vendorID"], PDO::PARAM_INT);
        $stmt->bindValue(":marca", $data["marca"], PDO::PARAM_STR);
        $stmt->bindValue(":catID", $data["catID"], PDO::PARAM_INT);
        $stmt->bindValue(":cost", $data["cost"], PDO::PARAM_STR);
        $stmt->bindValue(":price1", $data["price"], PDO::PARAM_STR);
        $stmt->bindValue(":inventory", $data["inventory"], PDO::PARAM_INT);
        $stmt->bindValue(":remarks", $data["remarks"], PDO::PARAM_STR);
        $stmt->bindValue(":image", $data["image"], PDO::PARAM_STR);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }
}