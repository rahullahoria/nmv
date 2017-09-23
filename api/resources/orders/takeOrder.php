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

    $sqlOrder = "INSERT INTO `orders`(`user_id`, `user_address_id`, `status`, `payed`,`creation`)
                  VALUES (:userId,:userAddresssId,'in-process',:payed, :createD)";

    $sqlOrderDetails = "INSERT INTO `order_details`(`order_id`, `product_id`, `quantity`, `creation`)
                                VALUES (:orderId, :productId, :quantity, :createD)";

    $sqlSearchUser = "select * from users WHERE mobile = :mobile;";

    $sqlInsertUser = "INSERT INTO `users`(`name`, `mobile`, `ref_id`, `email`, `password`, `gender`, `type`, `dob`, `education`, `occupation`)
              VALUES (:name,:mobile, :ref_id, :email,'',:gender,'',:dob,:education,:occupation);";

    $sqlInsertAddress = "INSERT INTO `user_address`(`user_id`, `address_1`, `address_2`, `area`, `city_id`, `state_id`, `country_id`, `pin_code`, `post_office`)
                        VALUES (:userId,:address_1,:address_2,:area,:city,:state,:country,:pin_code,:post_office)";

    $checkSub = "SELECT * FROM `magazine_sub` WHERE `user_id` = :userId and TIMESTAMPDIFF(MONTH, `creation`, now()) < `months`";

    $sqlSubUpdate = "UPDATE `magazine_sub` SET months= months+:month WHERE `user_id`= :userId";

    $sqlInsertSub = "INSERT INTO `magazine_sub`(`user_id`, `address`, `from`, `months`, `creation`)
                      VALUES (:userId,:addressId,:from,:month,:creation)";

    try {
        $db = getDB();

        $stmt = $db->prepare($sqlOrder);


        $stmt->bindParam("userId", $order->user_id);
        $stmt->bindParam("userAddresssId", $order->user_add_id);
        $stmt->bindParam("payed", $order->payed);
        $stmt->bindParam("createD", date("Y-m-d H:i:s"));

        $stmt->execute();

        $order->id = $db->lastInsertId();


        if($order->id){
            foreach($order->products as $p) {
                $stmt = $db->prepare($sqlOrderDetails);


                $stmt->bindParam("orderId", $order->id);
                $stmt->bindParam("productId", $p->id);
                $stmt->bindParam("quantity", $p->quantity);
                $stmt->bindParam("createD", date("Y-m-d H:i:s"));

                $stmt->execute();
            }
            foreach($order->subscribers as $s) {

                $stmt = $db->prepare($sqlSearchUser);
                $stmt->bindParam("mobile", $s->mobile);
                $stmt->execute();
                $sUser = $stmt->fetchAll(PDO::FETCH_OBJ);

                if(!count($sUser)){

                    $stmt = $db->prepare($sqlInsertUser);


                    $stmt->bindParam("name", $s->name);
                    $stmt->bindParam("mobile", $s->mobile);
                    $stmt->bindParam("ref_id", $order->user_id);
                    $stmt->bindParam("email", $s->email);

                    $stmt->bindParam("gender", $s->gender);
                    $stmt->bindParam("dob", $s->dob);
                    $stmt->bindParam("education", $s->education);
                    $stmt->bindParam("occupation", $s->occupation);

                    $stmt->execute();

                    $s->id = $db->lastInsertId();


                }
                else {
                    $s->id = $sUser[0]->id;


                }

                if($s->id){
                    //var_dump($s);
                    $stmt = $db->prepare($sqlInsertAddress);


                    $stmt->bindParam("userId", $s->id);
                    $stmt->bindParam("address_1", $s->address_1);
                    $stmt->bindParam("address_2", $s->address_2);

                    $stmt->bindParam("area", $s->area);
                    $stmt->bindParam("city", $s->city);
                    $stmt->bindParam("state", $s->state);
                    $stmt->bindParam("country", $s->country);
                    $stmt->bindParam("pin_code", $s->pin_code);
                    $stmt->bindParam("post_office", $s->post_office);

                    $stmt->execute();
                    $s->address_id = $db->lastInsertId();

                }


                //checking sub
                $stmt = $db->prepare($checkSub);


                $stmt->bindParam("userId", $s->id);

                $stmt->execute();
                $sUserSub = $stmt->fetchAll(PDO::FETCH_OBJ);

                if(!count($sUserSub)){
                    //insert new sub

                    $stmt = $db->prepare($sqlInsertSub);

                    $stmt->bindParam("userId", $s->id);
                    $stmt->bindParam("addressId", $s->address_id);
                    $stmt->bindParam("from", $s->from);
                    $stmt->bindParam("month", $s->months);
                    $stmt->bindParam("creation", date("Y-m-d H:i:s"));

                    $stmt->execute();
                }
                else {
                    //update old

                    $stmt = $db->prepare($sqlSubUpdate);

                    $stmt->bindParam("userId", $s->user_id);
                    $stmt->bindParam("month", $s->month);

                    $stmt->execute();


                }




                //user id

            }


        }

        $db = null;

        echo '{"response": ' . json_encode($order) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}