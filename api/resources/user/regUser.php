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

    $sqlUser = "select id from users WHERE mobile = :mobile;";


    $sql = "INSERT INTO `users`(`name`, `mobile`, `email`, `password`, `gender`, `type`, `dob`, `education`, `occupation`)
              VALUES (:name,:mobile,:email,'',:gender,'',:dob,:education,:occupation);";

    $sqlAddress = "INSERT INTO `user_address`(`user_id`, `address_1`, `address_2`, `area`, `city_id`, `state_id`, `country_id`, `pin_code`, `post_office`)
                        VALUES (:userId,:address_1,:address_2,:area,:city,:state,:country,:pin_code,:post_office)";



    try {
        $db = getDB();

        //refusers

        $stmt = $db->prepare($sqlUser);


        $stmt->bindParam("mobile", $user->ref_mobile);

        $stmt->execute();

        $refUsers = $stmt->fetchAll(PDO::FETCH_OBJ);


        //insert
        $stmt = $db->prepare($sql);


        $stmt->bindParam("name", $user->name);
        $stmt->bindParam("mobile", $user->mobile);
        $stmt->bindParam("ref_id", $refUsers[0]->id);
        $stmt->bindParam("email", $user->email);

        $stmt->bindParam("gender", $user->gender);
        $stmt->bindParam("dob", $user->dob);
        $stmt->bindParam("education", $user->education);
        $stmt->bindParam("occupation", $user->occupation);

        $stmt->execute();

        $user->id = $db->lastInsertId();

        if($user->id){
            $stmt = $db->prepare($sqlAddress);


            $stmt->bindParam("userId", $user->id);
            $stmt->bindParam("address_1", $user->address_1);
            $stmt->bindParam("address_2", $user->address_2);

            $stmt->bindParam("area", $user->area);
            $stmt->bindParam("city", $user->city);
            $stmt->bindParam("state", $user->state);
            $stmt->bindParam("country", $user->country);
            $stmt->bindParam("pin_code", $user->pin_code);
            $stmt->bindParam("post_office", $user->post_office);

            $stmt->execute();

        }


        $db = null;
        echo '{"response": ' . json_encode($user) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}