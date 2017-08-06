<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 8/6/17
 * Time: 2:57 PM
 */

function takeOrder(){

    $request = \Slim\Slim::getInstance()->request();


    $order = json_decode($request->getBody());
    if (is_null($order)){
        echo '{"error":{"text":"Invalid Json"}}';
        die();
    }

    $sqlOrder = "INSERT INTO `orders`(`user_id`, `user_address_id`, `status`, `creation`)
                  VALUES (:userId,:userAddresssId,'in-process',:createD)";

    $sqlOrderDetails = "INSERT INTO `order_details`(`order_id`, `product_id`, `quantity`, `creation`)
                                VALUES (:orderId, :productId, :quantity, :createD)";



    try {
        $db = getDB();

        $stmt = $db->prepare($sqlOrder);


        $stmt->bindParam("userId", $order->user_id);
        $stmt->bindParam("userAddresssId", $order->user_add_id);
        $stmt->bindParam("createD", date("Y-m-d H:i:s"));

        $stmt->execute();

        $order->id = $db->lastInsertId();

        if($user->id){
            $stmt = $db->prepare($sqlOrderDetails);


            $stmt->bindParam("orderId", $order->id);
            $stmt->bindParam("productId", $user->product_id);
            $stmt->bindParam("quantity", $user->quantity);
            $stmt->bindParam("createD", date("Y-m-d H:i:s"));

            $stmt->execute();

        }


        $db = null;
        echo '{"response": ' . json_encode($user) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}