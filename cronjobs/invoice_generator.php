<?php

/**
 * Cron file to generate invoices.
 */

require __DIR__ . '/../config.inc.php';

$orders = DB::query(
    "select id,shop_id,created_at from orders where 
    ((ispaid = 1 AND (status_id = 3 OR status_id = 12)) 
    OR (ispaid = 0 AND paylater = 1 AND status_id = 3))
    AND (invoicenr IS NULL OR invoicenr = '1') AND shop_id != 5
    ORDER BY id ASC"
);

foreach ($orders as $order) {
    echo $order['id'].'/'.$order['created_at'];
    $invoice = generateInvoice($order['id'], $order['shop_id']);
    if ($invoice) {
        DB::update('orders', ['invoicenr' => $invoice], 'id=%i', $order['id']);
        echo '/'.$invoice;
    }
    echo "\n";
}