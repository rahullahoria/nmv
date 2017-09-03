<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 9/3/17
 * Time: 1:55 PM
 */

function getInventory(){
    $sql = "SELECT a.id, a.product_id, a.quantity, a.shivir, a.location, a.creation, a.last_update, b.name as product_name
          FROM `inventory` as a inner join products as b
          WHERE a.product_id = b.id";

    try{
        $db = getDB();

        $stmt = $db->prepare($sql);



        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"products": ' . json_encode($products) . '}';
    }
    catch (Exception $e){
        echo '{"error":{"text":' . $e->getMessage() . '}}';

    }
}