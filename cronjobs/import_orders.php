<?php

/**
 * Cron file to import new orders.
 */

require __DIR__ . '/../config.inc.php';

use App\Classes\Order;
use App\Classes\Product;
use App\Classes\Event;
use Carbon\Carbon as Carbon;

$orders = DB::query("select * from webshop_cart where date_paid != '' and processed=0 limit 100");
$arraIDS = [];
foreach ($orders as $keyOrder => $order) {
    $arraIDS[] = $order['id'];
    $orderItems = DB::query('select * from webshop_cart_item where webshop_cart_id=%i', $order['id']);
    foreach ($orderItems as $key => $orderItem) {
        $orderItemAttributes = DB::query(
            "select attribute.name->>'$.nl' as attribute,attribute.id
            from webshop_cart_item_attribute
            left join product_attribute on product_attribute.id = webshop_cart_item_attribute.product_attribute_id
            left join attribute on product_attribute.attribute_id= attribute.id
            where webshop_cart_item_id=%i",
            $orderItem['id']
        );
        foreach ($orderItemAttributes as $orderItemAttribute) {
            $orderItem['attributes'][] = $orderItemAttribute;
        }
        $order['order-items'][$key] = $orderItem;
    }
    $order['order_data'] = json_decode($order['order_data'], true);
    $order['transaction_data'] = json_decode($order['transaction_data'], true);
    $orders[$keyOrder] = $order;
}
if (count($arraIDS) > 0) {
    DB::query('update webshop_cart SET processed=99 where id IN (' . implode(',', array_map('intval', $arraIDS)) . ')');
}
foreach ($orders as $order) {
    $orderChecking = DB::queryFirstRow('select webshop_cart_id from ' . Order::$table . '
    where webshop_cart_id=%i', $order['id']);
    if ($orderChecking) {
        echo 'exist';
        continue;
    }
    $order_details = [];
    $order_details['session_id'] = $order['session_id'];
    $order_details['customerPhone'] = $order['order_data']['delivery']['phone'];
    $order_details['customerEmail'] = $order['order_data']['customer']['email'];
    $order_details['address'] = [];
    $order_details['address']['payment'] = [];
    if (!isset($order['order_data']['delivery'])) {
        $order['order_data']['delivery'] = $order['order_data']['customer'];
    }
    $order_details['address']['payment']['gender'] = $order['order_data']['customer']['payment_sex'];
    $order_details['address']['payment']['firstname'] = $order['order_data']['customer']['firstname'];
    $order_details['address']['payment']['lastname'] = $order['order_data']['customer']['lastname'];
    $order_details['address']['payment']['street'] = $order['order_data']['customer']['address'];
    $order_details['address']['payment']['houseNumber'] = $order['order_data']['customer']['housenumber'];
    $order_details['address']['payment']['houseNumberSupplement'] =
    $order['order_data']['customer']['housenumber_addition'];
    $order_details['address']['payment']['zipcode'] = $order['order_data']['customer']['zipcode'];
    $order_details['address']['payment']['email'] = $order['order_data']['customer']['email'];
    $order_details['address']['payment']['phone'] = $order['order_data']['customer']['phone'];
    $order_details['address']['payment']['city'] = $order['order_data']['customer']['city'];
    $order_details['address']['payment']['ip_address'] = $order['order_data']['customer']['ip_address'];
    $order_details['address']['payment']['countryCode'] = ($order['order_data']['customer']['country']) ?
    $order['order_data']['customer']['country'] : 'non';

    $order_details['address']['shipping']['gender'] = $order['order_data']['delivery']['payment_sex'];
    $order_details['address']['shipping']['firstname'] = $order['order_data']['delivery']['firstname'];
    $order_details['address']['shipping']['lastname'] = $order['order_data']['delivery']['lastname'];
    $order_details['address']['shipping']['street'] = $order['order_data']['delivery']['address'];
    $order_details['address']['shipping']['houseNumber'] = $order['order_data']['delivery']['housenumber'];
    $order_details['address']['shipping']['houseNumberSupplement'] =
    $order['order_data']['customer']['housenumber_addition'];
    $order_details['address']['shipping']['zipcode'] = $order['order_data']['delivery']['zipcode'];
    $order_details['address']['shipping']['email'] = $order['order_data']['delivery']['email'];
    $order_details['address']['shipping']['phone'] = $order['order_data']['delivery']['phone'];
    $order_details['address']['shipping']['city'] = $order['order_data']['delivery']['city'];
    $order_details['address']['shipping']['ip_address'] = $order['order_data']['delivery']['ip_address'];
    $order_details['address']['shipping']['countryCode'] = ($order['order_data']['delivery']['country']) ?
    $order['order_data']['delivery']['country'] : 'non';

    if ($order_details['address']['payment']['countryCode'] != 'none') {
        $arr = explode("/", $order_details['address']['payment']['countryCode'], 2);
        $order_details['address']['payment']['countryCode'] = $arr[0];
    }
    if ($order_details['address']['shipping']['countryCode'] != 'none') {
        $arr = explode("/", $order_details['address']['shipping']['countryCode'], 2);
        $order_details['address']['shipping']['countryCode'] = $arr[0];
    }

    $shippingCost = 0;

    foreach ($order['transaction_data']['data']['shopping_cart']['items'] as $item) {
        if ('shipping' == $item['merchant_item_id']) {
            $shippingCost = $item['unit_price'];
        }
    }
    $tmpAmount = $order['transaction_data']['data']['amount'] / 100;
    $prices = [
        'gross_price' => round($tmpAmount, 2),
        'net_price' => round($tmpAmount, 2) - round($tmpAmount - (($tmpAmount / 121) * 100), 2),
        'vat' => round($tmpAmount - (($tmpAmount / 121) * 100), 2),
    ];

    //Payment
    $payment = [];
    $payment['type'] = $order['transaction_data']['data']['payment_details']['type'];
    $payment['payment_bic'] = $order['transaction_data']['data']['payment_details']['account_bic'];
    $payment['payment_fullname'] = $order['transaction_data']['data']['payment_details']['account_holder_name'];
    $payment['payment_iban'] = $order['transaction_data']['data']['payment_details']['account_iban'];
    $payment['payment_zipcode'] = $order['transaction_data']['data']['payment_details']['payment_zipcode'];
    $payment['payment_identifier'] = $order['transaction_data']['data']['order_id'];
    $payment['date'] = $order['transaction_data']['data']['modified'];
    $payment['amount'] = $order['transaction_data']['data']['amount'] / 100;

    //get invoice number
    $invoice = DB::queryFirstRow('select orderpre,last_invoice from invoices where shop_id=%i', $order['shop_id']);
    if ($invoice) {
        ++$invoice['last_invoice'];
        DB::update('invoices', ['last_invoice' => $invoice['last_invoice']], 'shop_id=%i', $order['shop_id']);
        $invoice = $invoice['orderpre'] . $invoice['last_invoice'];
    } else {
        $invoice = '';
    }
    $check = DB::insert('orders', [
        'shop_id' => $order['shop_id'], 'status_id' => 1,
        'transaction_id' => $order['transaction_data']['data']['order_id'],
        'gross_price' => $prices['gross_price'], 'ispaid' => 1,
        'order_details' => json_encode($order_details), 'payment' => json_encode($payment),
        'net_price' => $prices['net_price'], 'shipping_cost' => $shippingCost,
        'vat' => $prices['vat'], 'invoicenr' => $invoice, 'webshop_cart_id' => $order['id'],
        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
    ]);

    if ($check) {
        $orderID = DB::insertId();
        DB::update('webshop_cart', ['processed' => '1'], 'id=%i', $order['id']);

        foreach ($order['order-items'] as $item) {
            $product = Product::getProduct($item['product_id']);
            DB::insert(Order::$table_order_items, [
                'product_id' => $item['product_id'], 'product_name' => $product['contents']['title'],
                'order_id' => $orderID, 'shop_id' => $order['shop_id'], 'count' => $item['product_quantity'],
                'price' => $product['prices']['price'][$order['shop_id']]['price'],
                'totalprice' => ($product['prices']['price'][$order['shop_id']]['price'] * $item['product_quantity']),
                'combo' => 0, 'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
            $orderItemId = DB::insertId();
            //checkCombo
            if ($item['product_combo_id']) {
                $product = Product::getProductFromComboId($item['product_combo_id']);
                if ($product) {
                    if ($product['prices']['price'][$order['shop_id']]['price_combo']) {
                        $price = $product['prices']['price'][$order['shop_id']]['price_combo'];
                    } else {
                        $price = 0;
                    }
                    DB::insert(Order::$table_order_items, [
                        'product_id' => $product['id'],
                        'product_name' => 'COMBO->' . $product['contents']['title'],
                        'order_id' => $orderID, 'shop_id' => $order['shop_id'],
                        'count' => $item['product_quantity'], 'price' => $price,
                        'totalprice' => $price, 'combo' => 1,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    ]);
                }
            }
            if ($orderItemId) {
                if (isset($item['attributes'])) {
                    foreach ($item['attributes'] as $attribute) {
                        DB::insert(Order::$table_item_attribute, ['order_item_id' => $orderItemId,
                            'attribute_id' => $attribute['id']]);
                    }
                }
            }
        }
        //check shipping item
        if ($shippingCost != 0) {
            $product = Product::getProduct(99999999);
            DB::insert(Order::$table_order_items, [
                'product_id' => 99999999, 'product_name' => $product['contents']['title'],
                'order_id' => $orderID, 'shop_id' => $order['shop_id'], 'count' => 1,
                'price' => $shippingCost,
                'totalprice' => $shippingCost,
                'combo' => 0, 'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }
        //order imported event function
        $event = new Event();
        $event->orderImported($orderID);
    }
}
