<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 9/13/17
 * Time: 4:07 PM
 */

function getAllStates($country_id){
    $sql = "SELECT * FROM `States` WHERE country_id = :country_id ";



    try {
        $db = getDB();

        //get all service providers of the id
        $stmt = $db->prepare($sql);

        $stmt->bindParam("country_id", $country_id);

        $stmt->execute();
        $countries = $stmt->fetchAll(PDO::FETCH_OBJ);


        $db = null;
        echo '{"states": ' . json_encode($countries) . '}';

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}