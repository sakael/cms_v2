<?php

require __DIR__ . '/../../config.inc.php';
require __DIR__ . '/config.php';
require 'classes/bol.com.class.php';

/*

This script will check if Bol orders have been scanned/shipped and sends the trackingcode to Bol

*/

$shipments = DB::query("SELECT * FROM bol_shipment WHERE processed=0");
foreach ($shipments as $shipment){

    // Get the Bol order
    $order = DB::queryFirstRow("SELECT * FROM BOL_TEMP_ORDERS WHERE ordernr=%s AND V3=1", $shipment['order']);

    if (!$order){
        DB::delete('bol_shipment', 'id=%i', $shipment['id']);
        continue;
    }

    print_r($order);

    // Get the orderrows
    $orderrows = DB::query("SELECT * FROM BOL_TEMP_ORDERROWS WHERE owner=%i", $order['id']);

    /*

    Array
    (
        [0] => Array
            (
                [id] => 332693
                [owner] => 326180
                [quantity] => 1
                [ean] => 8719828049987
                [prodnr] => 2785649
                [prodname] => Premium Tempered Glass Screen Protector voor de Samsung Galaxy S9
                [price] => 13.95
                [bol_prod_id] => 12fe9c36-9070-4df7-bfee-79133056658e
                [trx_fee] => 2.57
                [order_type] => FBR
            )

    )

    */

    $bol_account_id = substr($shipment['order'],0,3);
    $bol = new Bol($accounts[$bol_account_id]['id'],$accounts[$bol_account_id]['secret']);

    foreach ($orderrows as $r){

        // track&trace
        $transporter = 'BRIEFPOST';
        $tracktrace = null;

        if (stristr($shipment['tracktrace'], '3S') !== FALSE){
            $transporter = 'TNT';
            $tracktrace = null;
        }

        if (stristr($shipment['tracktrace'], '3SBDL') !== FALSE){
            $transporter = 'DHL';
            $tracktrace = $shipment['tracktrace'];
        }

        $result = $bol->shipOrderItem($order['ordernr'], $r['bol_prod_id'], $transporter, $tracktrace);
        print_r($result); die();
    }

}
