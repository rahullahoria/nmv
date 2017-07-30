<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 7/30/17
 * Time: 9:50 AM
 */

function searchUser($mobile){

    $sql = "select * from users WHERE mobile = :mobile;";



    try {
        $db = getDB();

        $stmt = $db->prepare($sql);


        $stmt->bindParam("mobile", $mobile);

        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_OBJ);


        $db = null;
        echo '{"response": ' . json_encode($users) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }


}