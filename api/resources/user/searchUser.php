<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 7/30/17
 * Time: 9:50 AM
 */

function searchUser($mobile){

    $sql = "select * from users WHERE mobile = :mobile;";

    $sqlAddress = "SELECT a.id, a.`address_1`,a.`address_2`,a.`area`,a.`city_id`,b.name as city_name,a.state_id, c.name as state_name, a.country_id,d.name as country_name ,a.pin_code, a.post_office
                      FROM
                        `user_address` as a inner join
                        cities as b inner join
                        states as c inner join
                        countries as d
                    WHERE a.user_id = :userId and a.city_id = b.id and a.state_id = c.id and a.country_id = d.id ";

    $userOrders = "SELECT a.user_id, a.user_address_id, a.status, a.creation, b.product_id,b.order_id,b.quantity,c.name,c.mobile, d.city_id, d.pin_code, e.name as product FROM
`orders` as a inner join order_details as b inner join users as c inner join user_address as d
inner join products as e

WHERE a.id = b.order_id and a.user_id = c.id and a.user_address_id = d.id and b.product_id = e.id and c.mobile = :mobile";



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

        //orders
        $stmt = $db->prepare($userOrders);


        $stmt->bindParam("mobile", $mobile);

        $stmt->execute();

        $users[0]->orders= $stmt->fetchAll(PDO::FETCH_OBJ);



        $db = null;
        echo '{"response": ' . json_encode($users) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }


}