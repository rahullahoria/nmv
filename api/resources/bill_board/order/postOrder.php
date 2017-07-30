<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/14/17
 * Time: 12:12 PM
 */

function postOrder($id){

    $request = \Slim\Slim::getInstance()->request();


    $feed = json_decode($request->getBody());
    if (is_null($feed)){
        echo '{"error":{"text":"Invalid Json"}}';
        die();
    }


    $sql = "INSERT INTO `store_orders`(`ads_id`, `table_id`, `bb_id`)
              VALUES (:adsId, :tableId, :bbId);";



    try {
        $db = getDB();

        $stmt = $db->prepare($sql);


        $stmt->bindParam("adsId", $feed->adv);
        $stmt->bindParam("tableId", $feed->table);
        $stmt->bindParam("bbId", $id);


        $stmt->execute();

        $feed->id = $db->lastInsertId();



        $db = null;
        echo '{"response": ' . json_encode($feed) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}