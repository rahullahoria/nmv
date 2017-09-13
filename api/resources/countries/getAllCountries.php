<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 9/13/17
 * Time: 4:06 PM
 */

function getAllCountries(){
    $sql = "SELECT * FROM `countries` WHERE 1 ";



    try {
        $db = getDB();

        //get all service providers of the id
        $stmt = $db->prepare($sql);


        $stmt->execute();
        $countries = $stmt->fetchAll(PDO::FETCH_OBJ);


        $db = null;
        echo '{"countries": ' . json_encode($countries) . '}';

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}