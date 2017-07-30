<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/14/17
 * Time: 4:19 PM
 */

function callAccepted($storeId,$waiterId,$tableId){
    $sql = "UPDATE `store_waiter_calls` SET `status`='recieved', `call_accepted`=:callAccept WHERE `table_id` = :tableId";

    try{
        $db = getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindParam("callAccept", date("Y-m-d H:i:s"));
        $stmt->bindParam("tableId", $tableId);

        $stmt->execute();

        $db = null;
        echo '{"status": "success"}';
    }
    catch (Exception $e){
        echo '{"error":{"text":' . $e->getMessage() . '}}';

    }

}