<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/14/17
 * Time: 4:11 PM
 */

function getWaiterCalls($storeId){
    $sql = "SELECT distinct `table_id` FROM `store_waiter_calls` WHERE status = 'in-queue'";

    try{
        $db = getDB();

        $stmt = $db->prepare($sql);

        //$stmt->bindParam("userId", $userId);

        $stmt->execute();
        $locks = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"tables": ' . json_encode($locks) . '}';
    }
    catch (Exception $e){
        echo '{"error":{"text":' . $e->getMessage() . '}}';

    }
}