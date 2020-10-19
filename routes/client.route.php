<?php

use App\Classes\MultiSafepay;
use Carbon\Carbon as Carbon;

$app->group('/clients', function () use ($app,  $container) {
    $app->get('/betaal/{orderid}/{amount}', function ($request, $response, $args) use ($app) {
        if (!isset($args['orderid']) || $args['orderid'] == '' && !isset($args['amount']) || $args['amount'] == '') {
            return $response->withJson(['status' => 'Error', 'msg' => 'Missing data']);
            $response->getBody()->write(json_encode(['status' => 'Error', 'msg' => 'Missing data']));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(201);
        }

        $rand = rand(100, 900);
        $orderId = $args['orderid'] . '-' . $rand;
        $amount = $args['amount'];
        $msp = new MultiSafepay();

        $order = DB::queryFirstRow('select * from orders where id=%i', $args['orderid']);

        if (!$order || !isset($order['order_details'])) {
            $response->getBody()->write(json_encode(['status' => 'Error', 'msg' => 'Order is not found or missing data']));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(201);
        }
        $order['order_details'] = json_decode($order['order_details'], true);
        try {
            $order_id = time();
            $order = $msp->MSP->orders->post([
                'type' => 'redirect',
                'order_id' => $orderId,
                'currency' => 'EUR',
                'amount' => $amount,
                'description' => 'Bestelling bij 123BestDeal',
                'days_active' => '30',
                'payment_options' => [
                    'notification_url' => SITE_URL.$this->router->pathFor('client.payment.notifications'),
                    'redirect_url' => SITE_URL.$this->router->pathFor('client.payment.success'),
                    'cancel_url' => SITE_URL.$this->router->pathFor('client.payment.canceled'),
                    'close_window' => 'true',
                    'template_id' => 'beta-template'
                ],
                'customer' => [
                    'locale' => 'nl_NL',
                    'ip_address' => '127.0.0.1',
                    'forwarded_ip' => '127.0.0.1',
                    'first_name' => $order['order_details']['address']['payment']['firstname'],
                    'last_name' => $order['order_details']['address']['payment']['lastname'],
                    'address1' => $order['order_details']['address']['payment']['street'],
                    'house_number' => $order['order_details']['address']['payment']['houseNumber'] . (isset($order_data['order_details']['address']['payment']['houseNumberSupplement']) ? $order_data['order_details']['address']['payment']['houseNumberSupplement'] : ''),
                    'zip_code' => $order['order_details']['address']['payment']['zipcode'],
                    'city' => $order['order_details']['address']['payment']['city'],
                    'country' => $order['order_details']['address']['payment']['countryCode'],
                    'phone' => $order['order_details']['address']['payment']['phone'],
                    'email' => $order['order_details']['address']['payment']['email']
                ],
                'plugin' => [
                    'shop' => 'MultiSafepay Toolkit',
                    'shop_version' => TOOLKIT_VERSION,
                    'plugin_version' => TOOLKIT_VERSION,
                    'partner' => 'MultiSafepay',
                    'shop_root_url' => 'https://www.123bestdeal.nl'
                ]
            ]);

            return $response->withHeader('Location', $msp->MSP->orders->getPaymentLink());
        } catch (Exception $e) {
            echo 'Error ' . htmlspecialchars($e->getMessage());
        }
    })->setName('client.payment.registration');

    $app->get('/payment/notification', function ($request, $response, $args) use ($app) {
        // make sure this is a valid request
        if (isset($_REQUEST['transactionid'])) {
            $tmp = explode('-', $_REQUEST['transactionid']);
            $transactionId = $tmp[0];

            // check if we know this uuid
            $session = DB::queryFirstRow('SELECT * FROM orders where id=%i', $transactionId);
            if (!$session) {
                $response->getBody()->write(json_encode(['status' => false, 'msg' => 'unknown transaction id']));
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(401);
            }

            $msp = new Multisafepay;
            try {
                //get the order
                $transaction_data = $msp->MSP->orders->get($endpoint = 'orders', $_REQUEST['transactionid'], $body = [], $query_string = false);
            } catch (Exception $e) {
                echo 'Error ' . htmlspecialchars($e->getMessage());
            }
            // error at Multisafepay?
            if (!$transaction_data) {
                $response->getBody()->write(json_encode(['status' => false, 'msg' => 'missing transaction details']));
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(409);
            }
            /*
            We only accept 'completed'
            https://docs.multisafepay.com/api/#transaction-statuses
            */

            if (!isset($transaction_data->status) || $transaction_data->status != 'completed') {
                $response->getBody()->write(json_encode(['status' => false, 'msg' => 'waiting for payment']));
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(402);
            }

            // update the order note
            $amount = number_format(($transaction_data->amount / 100), 2, '.', ' ');
            $check = DB::insert('notes', [[
                'user_id' => 1, 'user_id_to' => 1,
                'order_id' => $transactionId, 'note' => 'betaald ( &euro; ' . $amount . ' ) met -betalingsverzoek- op ' . Carbon::now()->format('Y-m-d H:i:s') . ',Transactie ID: ' . $_REQUEST['transactionid'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]]);
            $response->getBody()->write(json_encode(['status' => true, 'msg' => 'De betaling is gelukt']));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        }
    })->setName('client.payment.notifications');

    $app->get('/betaling/gelukt', function ($request, $response, $args) use ($app) {
        // make sure this is a valid request
        if (isset($_REQUEST['transactionid'])) {
            $tmp = explode('-', $_REQUEST['transactionid']);
            $transactionId = $tmp[0];

            // check if we know this uuid
            $session = DB::queryFirstRow('SELECT * FROM orders where id=%i', $transactionId);
            if (!$session) {
                $response->getBody()->write(json_encode(['status' => false, 'msg' => 'unknown transaction id']));
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(401);
            }

            $msp = new Multisafepay;
            try {
                //get the order
                $transaction_data = $msp->MSP->orders->get($endpoint = 'orders', $_REQUEST['transactionid'], $body = [], $query_string = false);
            } catch (Exception $e) {
                echo 'Error ' . htmlspecialchars($e->getMessage());
            }
            // error at Multisafepay?
            if (!$transaction_data) {
                $response->getBody()->write(json_encode(['status' => false, 'msg' => 'missing transaction details']));
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(409);
            }
            /*
                We only accept 'completed'
                https://docs.multisafepay.com/api/#transaction-statuses
                */
            if (!isset($transaction_data->status) || $transaction_data->status != 'completed') {
                $response->getBody()->write(json_encode(['status' => false, 'msg' => 'waiting for payment']));
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(402);
            }
            // update the order note
            /*  $check = DB::insert('notes', array([
                  'user_id' => 1, 'user_id_to' => 1,
                  'order_id' => $transactionId, 'note' => "betaald met -betalingsverzoek- op " . Carbon::now()->format('Y-m-d H:i:s') . ",Transactie ID: " . $_REQUEST['transactionid'],
                  'created_at' => Carbon::now()->format('Y-m-d H:i:s')
              ]));*/
            $response->getBody()->write(json_encode(['status' => true, 'msg' => 'De betaling is gelukt']));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        }
    })->setName('client.payment.success');

    $app->get('/betaling/geannuleerd', function ($request, $response, $args) use ($app) {
        $response->getBody()->write(json_encode(['status' => true, 'msg' => 'De betaling is geannuleerd']));
        return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
    })->setName('client.payment.canceled');
});
