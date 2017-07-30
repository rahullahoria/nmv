<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 5/18/17
 * Time: 11:41 AM
 */

//there could be 2 cases
// this user is first user
// this user is share key user.
function regUser(){
    $request = \Slim\Slim::getInstance()->request();


    $user = json_decode($request->getBody());
    if (is_null($user)){
        echo '{"error":{"text":"Invalid Json"}}';
        die();
    }


    $sql = "INSERT INTO `users`(`full_name`, `mobile`, `dob`, `gender`, `otp`, `router_id`)
              VALUES (:fullName, :mobile, :dob, :gender, :otp, :routerId );";



    try {
        $db = getDB();

        $stmt = $db->prepare($sql);
        $status = "active";
        //$service_provider->status = "new";

        $stmt->bindParam("fullName", $user->full_name);
        $stmt->bindParam("mobile", $user->mobile);
        $stmt->bindParam("dob", $user->dob);
        $stmt->bindParam("gender", $user->gender);
        $stmt->bindParam("otp", $user->otp);
        $stmt->bindParam("routerId", $user->router_id);

        $stmt->execute();

        $user->id = $db->lastInsertId();


        $db = null;
        echo '{"response": ' . json_encode($user) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}