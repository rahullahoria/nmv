<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 9/13/17
 * Time: 4:07 PM
 */

function getAllCities($country_id,$state_id){
    $sql = "SELECT * FROM `cities` WHERE state_id = :state_id ";



    try {
        $db = getDB();

        //get all service providers of the id
        $stmt = $db->prepare($sql);

        $stmt->bindParam("state_id", $state_id);

        $stmt->execute();
        $cities = $stmt->fetchAll(PDO::FETCH_OBJ);


        $db = null;
        echo '{"cities": ' . json_encode($cities) . '}';

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}