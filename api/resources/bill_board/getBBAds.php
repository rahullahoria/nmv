<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 5/23/17
 * Time: 6:03 PM
 */

function getBBAds($id){

    $sql = "SELECT vedio_id
                FROM `ads` ORDER BY rand()
                LIMIT 0 , 1";

    try{
        $db = getDB();

        $stmt = $db->prepare($sql);

        //$stmt->bindParam("userId", $userId);

        $stmt->execute();
        $locks = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"ads": ' . json_encode($locks) . '}';
    }
    catch (Exception $e){
        echo '{"error":{"text":' . $e->getMessage() . '}}';

    }
}