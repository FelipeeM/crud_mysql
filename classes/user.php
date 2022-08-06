<?php

require_once 'database.php';

class User {
    private $conn;

    // Constructor
    public function __construct(){
      $database = new Database();
      $db = $database->dbConnection();
      $this->conn = $db;
    }


    // Execute queries SQL
    public function runQuery($sql){
      $stmt = $this->conn->prepare($sql);
      return $stmt;
    }

    // Insert
    public function insert($name, $cpfcnpj,$birth, $fone){
      try{
        $stmt = $this->conn->prepare("INSERT INTO client (CLIENT_NAME, CPF_CNPJ,BIRTH, FONE) VALUES(:name, :cpfcnpj,:birth, :fone)");
        $stmt->bindparam(":name", $name);
        $stmt->bindparam(":cpfcnpj", $cpfcnpj);
        $stmt->bindparam(":birth", $birth);
        $stmt->bindparam(":fone", $fone);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }
    // Insert Product
    public function insertProduct($descriptions, $unitMeasure,$price){
      try{
        $stmt = $this->conn->prepare("INSERT INTO product (DESCRIPTION_P,UNIT_MEASURE,PRICE) VALUES(:descriptions,:unitMeasure,:price)");
        $stmt->bindparam(":descriptions", $descriptions);
        $stmt->bindparam(":unitMeasure", $unitMeasure);
        $stmt->bindparam(":price", $price);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }
    public function insertPaymentForm($description){
      try{
        $stmt = $this->conn->prepare("INSERT INTO payment_form (DESCRIPTION) VALUES(:description)");
        $stmt->bindparam(":description", $description);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }
     // Update Product
    public function updateProduct($id,$descriptions, $unitMeasure,$price){
      try{
        $stmt = $this->conn->prepare("UPDATE product SET DESCRIPTION_P = :descriptions, UNIT_MEASURE = :unitMeasure, PRICE = :price WHERE ID_PRODUCT = :id");
        $stmt->bindparam(":descriptions", $descriptions);
        $stmt->bindparam(":unitMeasure", $unitMeasure);
        $stmt->bindparam(":price", $price);
        $stmt->bindparam(":id", $id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }

     // Update payment form
     public function updatePaymentForm($id,$description){
      try{
        $stmt = $this->conn->prepare("UPDATE payment_form SET DESCRIPTION = :description WHERE ID_PAYMENT_FORM = :id");
        $stmt->bindparam(":description", $description);
        $stmt->bindparam(":id", $id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }


    // Update
    public function update($id,$name, $cpfcnpj,$birth, $fone){
        try{
          $stmt = $this->conn->prepare("UPDATE client SET CLIENT_NAME = :name, CPF_CNPJ = :cpfcnpj, BIRTH = :birth, FONE = :fone WHERE ID_CLIENT = :id");
          $stmt->bindparam(":name", $name);
          $stmt->bindparam(":cpfcnpj", $cpfcnpj);
          $stmt->bindparam(":birth", $birth);
          $stmt->bindparam(":fone", $fone);
          $stmt->bindparam(":id", $id);
          $stmt->execute();
          return $stmt;
        }catch(PDOException $e){
          echo $e->getMessage();
        }
    }

    // Delete Product
    public function deleteProduct($id){
      try{
        $stmt = $this->conn->prepare("DELETE FROM product WHERE ID_PRODUCT = :id");
        $stmt->bindparam(":id", $id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
          echo $e->getMessage();
      }
    }
    // Delete payment form
    public function deletePaymentForm($id){
      try{
        $stmt = $this->conn->prepare("DELETE FROM payment_form WHERE ID_PAYMENT_FORM = :id");
        $stmt->bindparam(":id", $id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
          echo $e->getMessage();
      }
    }


    // Delete
    public function delete($id){
      try{
        $stmt = $this->conn->prepare("DELETE FROM client WHERE ID_CLIENT = :id");
        $stmt->bindparam(":id", $id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
          echo $e->getMessage();
      }
    }

    // Redirect URL method
    public function redirect($url){
      header("Location: $url");
    }
}
?>
