<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/14/17
 * Time: 12:13 PM
 */

function storeFeedback($id){
    $request = \Slim\Slim::getInstance()->request();


    $feed = json_decode($request->getBody());
    if (is_null($feed)){
        echo '{"error":{"text":"Invalid Json"}}';
        die();
    }


    $sql = "INSERT INTO `store_ratings`(`rating`, `mobile`, `bb_id`)
              VALUES (:rating, :mobile, :bbId);";



    try {
        $db = getDB();

        $stmt = $db->prepare($sql);


        $stmt->bindParam("rating", $feed->rating);
        $stmt->bindParam("mobile", $feed->mobile);
        $stmt->bindParam("bbId", $id);


        $stmt->execute();

        $feed->id = $db->lastInsertId();

        $message = "Thanks for spending time with The Drunk House !\n Please visit again! and enjoy next level services on your next visit \nfor complete feedback\n http://shatkonlabs.com";

        if($feed->id)
            sendSMS($feed->mobile, $message);


        $db = null;
        echo '{"response": ' . json_encode($feed) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}