<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 7/30/17
 * Time: 9:50 AM
 */

function searchUser($mobile){

    $sql = "select * from users WHERE mobile = :mobile;";

    $sqlAddress = "SELECT a.`address_1`,a.`address_2`,a.`area`,a.`city_id`,b.name,a.state_id, c.name, a.country_id,d.name,a.pin_code, a.post_office FROM `user_address` as a inner join cities as b inner join states as c inner join countries as d WHERE a.user_id = :userIdand a.city_id = b.id and a.state_id = c.id and a.country_id = d.id ";



    try {
        $db = getDB();

        $stmt = $db->prepare($sql);


        $stmt->bindParam("mobile", $mobile);

        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = $db->prepare($sqlAddress);


        $stmt->bindParam("userId", $users[0]->id);

        $stmt->execute();

        $users[0]->addresses = $stmt->fetchAll(PDO::FETCH_OBJ);



        $db = null;
        echo '{"response": ' . json_encode($users) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }


}