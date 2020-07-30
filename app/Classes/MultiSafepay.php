<?php

namespace App\Classes;

use DB;
use Carbon\Carbon as Carbon;
use App\Auth\Auth as Auth;
use MultiSafepayAPI\Client;

class MultiSafepay
{
    public $MSP;
    public function __construct()
    {
        $this->MSP = new Client;
        $this->MSP->setApiKey(API_KEY);
        $this->MSP->setApiUrl(API_URL);
    }
    /**
     * Notify after payment function
     *
     * @param int $transaction_id
     * @param int $orderID
     * @param string $zipcode
     * @param string $country
     * @return void
     */
    public function payAfterNoty($transaction_id = null, $orderID, $zipcode = '', $country = '')
    {
        $endpoint = 'orders/' . $transaction_id;
        $track = DB::queryFirstRow("select * from selektvracht WHERE order_id=%i order by id DESC", $orderID);
        if ($track) {
            $tracktrace_code = $track['barcode'];
        } else {
            $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $tracktrace_code  = '';
            for ($i = 0; $i < 9; $i++) {
                $tracktrace_code .= $characters[rand(0, $charactersLength - 1)];
            }
        }
        $url = "https://www.dhlparcel.nl/nl/volg-uw-zending?tc=" . $tracktrace_code . "&pc=" . $zipcode . "&lc=nl-NL";
        try {
            $order = $this->MSP->orders->patch(array(
                "status" => 'shipped',
                "tracktrace_code" => $tracktrace_code,
                "carrier" => 'DHL',
                "tracktrace_url" => $url,
                "ship_date" => date('Y-m-d H:i:s'),
                "reason" => 'Shipped'
            ), $endpoint);
        } catch (Exception $e) {
            echo "Error " . htmlspecialchars($e->getMessage());
        }
    }
}
