<?php

require __DIR__ . '/../../config.inc.php';
require __DIR__ . '/config.php';
require 'classes/bol.com.class.php';

foreach ($accounts as $a_id => $account){

    $bol = new Bol($account['id'],$account['secret']);
    $orders = $bol->getOrders();

    foreach ($orders as $order){

        // if we do not know the id, fetch all the order data
        $e = DB::queryFirstRow("SELECT * FROM i12cover_crm.BOL_TEMP_ORDERS WHERE bol=%s AND order_pre=%s", $order['orderId'], $a_id);
        if ($e) continue;

        // add the order
        $orderdata = json_decode($bol->getOrder($order['orderId']),true);
        //print_r($orderdata); die();

        $gross_value = 0;
        $orderitems = array();
        foreach ($orderdata['orderItems'] as $orderitem){
            $gross_value += $orderitem['quantity'] * $orderitem['offerPrice'];

            // get the actual product + variant
            $variant = DB::queryFirstRow("
                SELECT ean.variant, prod.id, prod.webshopID FROM 123_EAN ean
                LEFT JOIN 123_prod prod ON prod.id = ean.owner
                WHERE ean.EAN=%s", $orderitem['ean']);

            if (!$variant){
                // now what?
                die('EAN not found');
            }

            $orderitems[] = array(
                'product_id' => $variant['id'],
                'webshopId' => $variant['webshopID'],
                'offerId' => $orderitem['offerId'],
                'ean' => $orderitem['ean'],
                'shop_id' => $a_id,
                'product_name' => $orderitem['title'],
                'count' => $orderitem['quantity'],
                'price' => $orderitem['offerPrice'],
                'totalprice' => ($orderitem['offerPrice'] * $orderitem['quantity']),
                'trx_fee' => $orderitem['transactionFee']
            );

        }
        $net_value = round(($gross_value / 121) * 100,2);

        if (array_key_exists('houseNumberSupplement', $orderdata['customerDetails']['billingDetails']))
            $orderdata['customerDetails']['billingDetails']['houseNumber'] .= $orderdata['customerDetails']['billingDetails']['houseNumberSupplement'];

        if (array_key_exists('houseNumberSupplement', $orderdata['customerDetails']['shipmentDetails']))
            $orderdata['customerDetails']['shipmentDetails']['houseNumber'] .= $orderdata['customerDetails']['shipmentDetails']['houseNumberSupplement'];

        $insertable_data = array(
            'V3' => 1,
            'order_type' => $order['fulfillmentMethod'],
            'date' => date('Y-m-d H:i:s', strtotime($orderdata['dateTimeOrderPlaced'])),
            'firstname' => $orderdata['customerDetails']['billingDetails']['firstName'],
            'lastname' => $orderdata['customerDetails']['billingDetails']['surName'],
            'address' => $orderdata['customerDetails']['billingDetails']['streetName'] . ' ' . $orderdata['customerDetails']['billingDetails']['houseNumber'],
            'zipcode' => $orderdata['customerDetails']['billingDetails']['zipCode'],
            'city' => $orderdata['customerDetails']['billingDetails']['city'],
            'country' => $orderdata['customerDetails']['billingDetails']['countryCode'],
            'email' => $orderdata['customerDetails']['billingDetails']['email'],

            'sh_name' => $orderdata['customerDetails']['shipmentDetails']['firstName'] . ' ' . $orderdata['customerDetails']['shipmentDetails']['surName'],
            'sh_address' => $orderdata['customerDetails']['shipmentDetails']['streetName'] . ' ' . $orderdata['customerDetails']['shipmentDetails']['houseNumber'],
            'sh_zipcode' => $orderdata['customerDetails']['shipmentDetails']['zipCode'],
            'sh_city' => $orderdata['customerDetails']['shipmentDetails']['city'],
            'sh_country' => $orderdata['customerDetails']['shipmentDetails']['countryCode'],

            'company' => (array_key_exists('company', $orderdata['customerDetails']['billingDetails']) ? $orderdata['customerDetails']['billingDetails']['company'] : ''),
            'gender' => ($orderdata['customerDetails']['billingDetails']['salutationCode'] == 01 ? 'm' : 'f'),

            'bol' => $orderdata['orderId'],
            'order_pre' => $a_id,

        );

        DB::insert('BOL_TEMP_ORDERS',$insertable_data);
        $order_id = DB::insertId();

        // add the orderitems
        foreach($orderitems as $item){
            $insertable_data = array(
                'owner' => $order_id,
                'quantity' => $item['count'],
                'ean' => $item['ean'],
                'prodnr' => $item['webshopId'],
                'prodname' => $item['product_name'],
                'price' => $item['price'],
                'bol_prod_id' => $item['offerId'],
                'trx_fee' => $item['trx_fee'],
                'order_type' => $order['fulfillmentMethod']
            );
            DB::insert('BOL_TEMP_ORDERROWS',$insertable_data);
        }

        die('added an order, please check result');
    }
}
