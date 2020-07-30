<?php

class Bol {

    private $account_id;
    private $account_secret;
    private $access_token;

    private $open_orders = array();

    public function __construct($id, $secret)
    {
        $this->account_id = $id;
        $this->account_secret = $secret;

        // Get the oAUTH token
        $this->getAccessToken();
    }

    private function getAccessToken()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://login.bol.com/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials&client_id=".$this->account_id."&client_secret=".$this->account_secret);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded','Accept: application/json'));
        $result=curl_exec($ch);

        if (!$result)
            return false;

        $result = json_decode($result,true);
        if (is_array($result) && array_key_exists('access_token',$result))
            $this->access_token = $result['access_token'];

        print_r(" * Retrieved access_token\n");
    }

    public function getOrders()
    {
        $orders = [];
        $headers = array(
            'Authorization: Bearer '.$this->access_token,
            'Content-Type: application/vnd.retailer.v3+json',
            'Accept: application/vnd.retailer.v3+json'
        );

        $page = 1;
        $fulfillmentMethod = 'FBR';
        while (true){
            print_r("Checking ".$fulfillmentMethod." orders, page ".$page."\n");
            $parameters = '?fulfilment-method='.$fulfillmentMethod.'&page='.$page;
            $o = json_decode($this->curl('/orders', $headers, $parameters),true);
            if (!$o || count($o['orders']) < 1){
                print_r(" - counted 0 orders\n");
                break;
            }

            print_r(" - counted ".count($o['orders'])." orders\n");
            foreach ($o['orders'] as $order){
                $order['fulfillmentMethod'] = $fulfillmentMethod;
                $orders[] = $order;
            }
            $page++;
        }

        $page = 1;
        $fulfillmentMethod = 'FBB';
        while (true){
            print_r("Checking ".$fulfillmentMethod." orders, page ".$page."\n");
            $parameters = '?fulfilment-method='.$fulfillmentMethod.'&page='.$page;
            $o = json_decode($this->curl('/orders', $headers, $parameters),true);
            if (!$o || count($o['orders']) < 1){
                print_r(" - counted 0 orders\n");
                break;
            }

            print_r(" - counted ".count($o['orders'])." orders\n");
            foreach ($o['orders'] as $order){
                $order['fulfillmentMethod'] = $fulfillmentMethod;
                $orders[] = $order;
            }
            $page++;
        }

        return $orders;
    }

    public function getOrder($id)
    {
        $headers = array(
            'Authorization: Bearer '.$this->access_token,
            'Content-Type: application/vnd.retailer.v3+json',
            'Accept: application/vnd.retailer.v3+json'
        );

        return $this->curl('/orders/'.$id,$headers);
    }

    public function shipOrderItem($order_id, $orderitem_id, $transporter, $tracktrace)
    {
        $headers = array(
            'Authorization: Bearer '.$this->access_token,
            'Content-Type: application/vnd.retailer.v3+json',
            'Accept: application/vnd.retailer.v3+json'
        );

        /*

        {
            "shipmentReference": "B321SR",
            "shippingLabelCode": "PLR00000002",
            "transport":
            {
                "transporterCode": "TNT",
                "trackAndTrace": "3SBOL0987654321"
            }
        }

        */

        $postdata = array(
            'shipmentReference' => 'Item ' . $orderitem_id . ' van order '. $order_id .' via ' .$transporter,
            'shippingLabelCode' => $tracktrace,
            'transport' => array(
                'transporterCode' => $transporter,
                'trackAndTrace' => $tracktrace
            )
        );

        print_r($postdata);
        //return $postdata;

        return $this->curl('/orders/'.$orderitem_id.'/shipment', $headers, null, json_encode($postdata));
    }




    public function curl($uri,$headers,$parameters=null,$postdata=false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.bol.com/retailer".$uri.$parameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_VERBOSE, 1);
        if ($postdata){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result=curl_exec($ch);

        return ($result ? $result : false);
    }

}
