<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 8/6/17
 * Time: 2:57 PM
 */

function get10DayOrders(){

    $sql = "SELECT a.user_id, a.user_address_id, a.status, a.creation, b.product_id,b.order_id,b.quantity,c.name,c.mobile, d.city_id, d.pin_code, e.name FROM
`orders` as a inner join order_details as b inner join users as c inner join user_address as d
inner join products as e

WHERE a.id = b.order_id and a.user_id = c.id and a.user_address_id = d.id and b.product_id = e.id";

    try{
        $db = getDB();

        $stmt = $db->prepare($sql);



        $stmt->execute();
        $locks = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"locks": ' . json_encode($locks) . '}';
    }
    catch (Exception $e){
        echo '{"error":{"text":' . $e->getMessage() . '}}';

    }
}