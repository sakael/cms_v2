<?php
//error_reporting(E_ALL);
//ini_set('display_errors',true);

require __DIR__ . '/../../config.inc.php';
require 'classes/bol.com.class.php';

$accounts = array(
    201 => array(
        'id' => '7b4a5885-d14a-448c-8e9a-24a3483956e9',
        'secret' => 'E4LUXew7yJbvIV1ik9k3havLtMJuUHeKDkQFOHGhNDAlfJYhQ0bbt1yS7YAq8T0I582pFi9uD48qBb5pHxsAzg'
    )
);

foreach ($accounts as $a_id => $account){

    $bol = new Bol($account['id'],$account['secret']);
    $orders = $bol->getOrders();
    $known_orders = DB::queryOneColumn('orderId','SELECT * FROM orders WHERE shop_id=%i', $a_id);

    foreach ($orders as $order){

        // if we do not know the id, fetch all the order data
        if (in_array($order['orderId'], $known_orders)){
            // Check for cancellation? Maybe later
            continue;
        }

        // add the order
        $orderdata = json_decode($bol->getOrder($order['orderId']),true);
        print_r($orderdata);

        $gross_value = 0;
        $orderitems = array();
        foreach ($orderdata['orderItems'] as $orderitem){
            $gross_value += $orderitem['quantity'] * $orderitem['offerPrice'];

            // get the actual product + variant
            $variant = DB::queryFirstRow("SELECT * FROM EAN WHERE EAN=%s", $orderitem['ean']);
            if (!$variant){
                // now what?
                $variant = array('product_id' => 0);
            }

            print_r($variant);

            $orderitems[] = array(
                'product_id' => $variant['product_id'],
                'shop_id' => $a_id,
                'product_name' => $orderitem['title'],
                'count' => $orderitem['quantity'],
                'price' => $orderitem['offerPrice'],
                'totalprice' => ($orderitem['offerPrice'] * $orderitem['quantity'])
            );

        }
        $net_value = round(($gross_value / 121) * 100,2);

        $insertable_data = array(
            'orderId' => $orderdata['orderId'],
            'shop_id' => $a_id,
            'order_details' => json_encode(array(
                'address' => array(
                    'payment' => array(
                        'gender' => ($orderdata['customerDetails']['billingDetails']['salutationCode'] == 01 ? 'M' : 'F'),
                        'company' => @$orderdata['customerDetails']['billingDetails']['company'],
                        'firstname' => $orderdata['customerDetails']['billingDetails']['firstName'],
                        'lastname' => $orderdata['customerDetails']['billingDetails']['surName'],
                        'street' => $orderdata['customerDetails']['billingDetails']['streetName'],
                        'houseNumber' => $orderdata['customerDetails']['billingDetails']['houseNumber'],
                        'houseNumberSupplement' => $orderdata['customerDetails']['billingDetails']['houseNumberSupplement'],
                        'zipcode' => $orderdata['customerDetails']['billingDetails']['zipCode'],
                        'city' => $orderdata['customerDetails']['billingDetails']['city'],
                        'countryCode' => $orderdata['customerDetails']['billingDetails']['countryCode'],
                    ),
                    'shipping' => array(
                        'gender' => ($orderdata['customerDetails']['shipmentDetails']['salutationCode'] == 01 ? 'M' : 'F'),
                        'company' => @$orderdata['customerDetails']['shipmentDetails']['company'],
                        'firstname' => $orderdata['customerDetails']['shipmentDetails']['firstName'],
                        'lastname' => $orderdata['customerDetails']['shipmentDetails']['surName'],
                        'street' => $orderdata['customerDetails']['shipmentDetails']['streetName'],
                        'houseNumber' => $orderdata['customerDetails']['shipmentDetails']['houseNumber'],
                        'houseNumberSupplement' => $orderdata['customerDetails']['shipmentDetails']['houseNumberSupplement'],
                        'zipcode' => $orderdata['customerDetails']['shipmentDetails']['zipCode'],
                        'city' => $orderdata['customerDetails']['shipmentDetails']['city'],
                        'countryCode' => $orderdata['customerDetails']['shipmentDetails']['countryCode'],
                    )
                ),
                'customerEmail' => $orderdata['customerDetails']['billingDetails']['email'],
                'customerPhone' => null,
            )),
            'gross_price' => $gross_value,
            'net_price' => $net_value,
            'vat' => round($gross_value - $net_value,2),
            'shipping_cost' => null,
            'ispaid' => 1,
            'created_at' => date('Y-m-d H:i:s', strtotime($orderdata['dateTimeOrderPlaced'])),
        );

        DB::insert('orders',$insertable_data);
        $order_id = DB::insertId();

        // add the orderitems
        foreach($orderitems as $item){
            $item['order_id'] = $order_id;
            DB::insert('order_item', $item);
        }

        die('ok');

    }
}
