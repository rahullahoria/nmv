<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 7/30/17
 * Time: 3:41 PM
 */

function getProducts(){
    $sql = "SELECT * from products where 1";

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