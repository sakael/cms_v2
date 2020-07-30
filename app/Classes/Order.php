<?php

namespace App\Classes;

use DB;
use Carbon\Carbon as Carbon;
use App\Auth\Auth;
use App\Classes\Product;
use App\Classes\Attribute;
use App\Classes\MultiSafepay;
use App\Classes\Mail;
use App\Classes\Pdf;
use App\Classes\Event;
use Mpdf\Output\Destination;

class order
{
    public static $table = 'orders';
    public static $table_product = 'product';
    public static $table_order_items = 'order_item';
    public static $table_price = 'product_pricing';
    public static $table_status = 'order_status';
    public static $table_shops = 'shop';
    public static $table_meta = 'product_meta';
    public static $table_attribute = 'attribute';
    public static $table_item_attribute = 'order_item_attribute';
    public static $table_notes = 'notes';
    public static $table_users = 'users';
    public $id;
    public $shop_id;
    public $status_id;
    public $user_id;
    public $shipping_method_id = '1';
    public $discount_code_id = '0';
    public $order_details = [];
    public $payment = [];
    public $invoicenr = null;
    public $bol = null;
    public $gross_price = '0.00';
    public $net_price = '0.00';
    public $vat = '0.00';
    public $company_ref = null;
    public $returncode = null;
    public $paylater = '0';
    public $ispaid = '0';
    public $fbc_invitation = '0';
    public $labelPrinted = '0';
    public $prodreview_invitation = '0';
    public static $productsLocation;
    public static $colors;
    public static $sizes;
    private $pdf;

    /**************************************************************************************************************************************************
     *******************************************************************(SetProductData)***************************************************************
     **************************************************************************************************************************************************/
    public static function SetOrderData()
    {
        $temp = DB::query('SELECT id,sku,location from ' . self::$table_product);
        foreach ($temp as $product) {
            self::$productsLocation[$product['id']] = $product;
        }
    }

    /**************************************************************************************************************************************************
     *******************************************************************(Get All Colors)****************************************************************
     **************************************************************************************************************************************************/
    public static function SetColorsData()
    {
        $tempColors = Attribute::AllinAttributeGroup(1);
        $colors = [];
        foreach ($tempColors as $color) {
            $colors[$color['id']] = $color;
        }
        self::$colors = $colors;
    }

    /**************************************************************************************************************************************************
     *******************************************************************(Get All Sizes)****************************************************************
     **************************************************************************************************************************************************/
    public static function SetSizesData()
    {
        $tempSizes = Attribute::AllinAttributeGroup(2);
        $sizes = [];
        foreach ($tempSizes as $size) {
            $sizes[$size['id']] = $size;
        }
        self::$sizes = $sizes;
    }

    /**************************************************************************************************************************************************
     *******************************************************************(Get All Orders)***************************************************************
     **************************************************************************************************************************************************/
    public static function All($Status = null, $userId = null, $options = null)
    {
        //Order status not present select every order
        $paid = ' ';
        if (isset($options['paid']) && $options['paid'] == 1) {
            $paid = ' and ispaid = 1 ';
        }

        if ($Status == null) {
            $query = 'SELECT ' . self::$table . '.id,' . self::$table . '.shop_id,' .
                self::$table . '.status_id,JSON_UNQUOTE(' . self::$table . '.payment) as payment,
            JSON_UNQUOTE(' . self::$table . '.order_details) as order_details,' . self::$table . '.gross_price,' . self::$table . '.shipping_cost,' . self::$table . '.created_at,' . self::$table_status . '.title as status_title, ' . self::$table_shops . '.domain as shop_name  from ' . self::$table . '
            LEFT JOIN ' . self::$table_status . ' on ' . self::$table_status . '.id=' . self::$table . '.status_id
            LEFT JOIN ' . self::$table_shops . ' on ' . self::$table_shops . '.id=' . self::$table . '.shop_id';

            //check if need to get orders which pointed to specific user
            if ($userId != null) {
                $query .= ' where ' . self::$table . '.user_id=%i' . $paid;
            } elseif (isset($options['paid']) && $options['paid'] == 1) {
                $query .= ' where ispaid = 1 ';
            }

            $ordersTemp = DB::query($query, $Status, $userId);

            $orderIds = [];
            $orders = [];
            foreach ($ordersTemp as $key => $order) {
                $orderIds[] = $order['id'];
                $orders[$order['id']] = $order;
            }

            $order_items = DB::query('SELECT * from ' . self::$table_order_items . '
            where ' . self::$table_order_items . ".order_id IN ('" . implode("','", $orderIds) . "')
                                                    ");
            foreach ($order_items as $item) {
                $product = Product::getProduct($item['product_id']);
                $item['product'] = $product;
                $itemAttributes = DB::query(
                    'SELECT ' . self::$table_item_attribute . '.* , ' . self::$table_attribute . '.attribute_group_id, ' . self::$table_attribute . ".name->>'$." . language . "' as title from " . self::$table_item_attribute . '
                   left join ' . self::$table_attribute . ' on ' . self::$table_item_attribute . '.attribute_id = ' . self::$table_attribute . '.id
                   where ' . self::$table_item_attribute . '.order_item_id=%i',
                    $item['id']
                );

                foreach ($itemAttributes as $itemAttribute) {
                    $item['attributes'][] = $itemAttribute;
                }

                $orders[$item['order_id']]['order_items'][] = $item;
            }
            //Order status presents select every order in specific
        } else {
            $query = 'SELECT ' . self::$table . '.id,' . self::$table . '.shop_id,' . self::$table .
                '.status_id,JSON_UNQUOTE(' . self::$table . '.payment) as payment,JSON_UNQUOTE(' . self::$table . '.order_details) as order_details,' . self::$table . '.gross_price,' . self::$table . '.shipping_cost,' . self::$table . '.created_at,' . self::$table_status . '.title as status_title, ' . self::$table_shops . '.domain as shop_name  from ' . self::$table . '
            LEFT JOIN ' . self::$table_status . ' on ' . self::$table_status . '.id=' . self::$table . '.status_id
            LEFT JOIN ' . self::$table_shops . ' on ' . self::$table_shops . '.id=' . self::$table . '.shop_id
            where ' . self::$table . '.status_id=%i ' . $paid;

            //check if need to get orders which pointed to specific user
            if ($userId != null) {
                $query .= ' and ' . self::$table . '.user_id=%i';
            }

            $ordersTemp = DB::query($query, $Status, $userId);

            $orderIds = [];
            $orders = [];
            foreach ($ordersTemp as $key => $order) {
                $orderIds[] = $order['id'];
                $orders[$order['id']] = $order;
            }
            $order_items = DB::query('SELECT * from ' . self::$table_order_items . '
            where ' . self::$table_order_items . ".order_id IN ('" . implode("','", $orderIds) . "')
                                                    ");
            foreach ($order_items as $item) {
                $product = Product::getProduct($item['product_id']);
                $item['product'] = $product;
                $itemAttributes = DB::query(
                    'SELECT ' . self::$table_item_attribute . '.* , ' . self::$table_attribute . '.attribute_group_id, ' . self::$table_attribute . ".name->>'$." . language . "' as title from " . self::$table_item_attribute . '
                   left join ' . self::$table_attribute . ' on ' . self::$table_item_attribute . '.attribute_id = ' . self::$table_attribute . '.id
                   where ' . self::$table_item_attribute . '.order_item_id=%i',
                    $item['id']
                );

                foreach ($itemAttributes as $itemAttribute) {
                    $item['attributes'][] = $itemAttribute;
                }

                $orders[$item['order_id']]['order_items'][] = $item;
            }
        }
        return $orders;
    }

    /**************************************************************************************************************************************************
     *******************************************************************(Get All Orders)***************************************************************
     **************************************************************************************************************************************************/
    public static function AllOld($Status = null, $userId = null)
    {
        if ($Status == null) {
            $query = 'SELECT ' . self::$table . '.id,' . self::$table . '.shop_id,' . self::$table .
                '.status_id,JSON_UNQUOTE(' . self::$table . '.payment) as payment,' . self::$table . '.created_at,' . self::$table_status . '.title as status_title, ' . self::$table_shops . '.domain as shop_name  from ' . self::$table . '
            LEFT JOIN ' . self::$table_status . ' on ' . self::$table_status . '.id=' . self::$table . '.status_id
            LEFT JOIN ' . self::$table_shops . ' on ' . self::$table_shops . '.id=' . self::$table . '.shop_id';

            //check if need to get orders which pointed to specific user
            if ($userId != null) {
                $query .= ' wehre ' . self::$table . '.user_id=%i';
            }

            $orders = DB::query($query, $userId);

            //Select all order_items
            foreach ($orders as $key => $order) {
                $orderId = $order['id'];
                $order['order_items'] = [];
                $order_items = DB::query('
                                        SELECT * from ' . self::$table_order_items . '
                                        LEFT join ' . self::$table_product . '  on ' . self::$table_order_items . '.product_id = ' . self::$table_product . '.id
                                        LEFT join ' . self::$table_price . ' ON ' . self::$table_price . '.product_id = ' . self::$table_product . '.id
                                        LEFT join ' . self::$table_meta . '  on ' . self::$table_meta . '.product_id = ' . self::$table_product . '.id
                                        where ' . self::$table_order_items . ".order_id=$orderId and  " . self::$table_meta . '.main=1
                                        ');
                $order['order_items'] = $order_items;
                $orders[$key] = $order;
            }
        } else {
            $query = 'SELECT ' . self::$table . '.id,' . self::$table . '.shop_id,' . self::$table .
                '.status_id,JSON_UNQUOTE(' . self::$table . '.payment) as payment,' . self::$table . '.created_at,' . self::$table_status . '.title as status_title, ' . self::$table_shops . '.domain as shop_name  from ' . self::$table . '
            LEFT JOIN ' . self::$table_status . ' on ' . self::$table_status . '.id=' . self::$table . '.status_id
            LEFT JOIN ' . self::$table_shops . ' on ' . self::$table_shops . '.id=' . self::$table . '.shop_id
            where ' . self::$table . '.status_id=%i';

            //check if need to get orders which pointed to specific user
            if ($userId != null) {
                $query .= ' and ' . self::$table . '.user_id=%i';
            }

            $orders = DB::query($query, $Status, $userId);

            foreach ($orders as $key => $order) {
                $orderId = $order['id'];
                $order['order_items'] = [];
                $order_items = DB::query('
                                        SELECT * from ' . self::$table_order_items . '
                                        LEFT join ' . self::$table_product . '  on ' . self::$table_order_items . '.product_id = ' . self::$table_product . '.id
                                        LEFT JOIN ' . self::$table_price . ' ON ' . self::$table_price . '.product_id = ' . self::$table_product . '.id
                                        LEFT join ' . self::$table_meta . '  on ' . self::$table_meta . '.product_id = ' . self::$table_product . '.id
                                        where ' . self::$table_order_items . '.order_id=' . $orderId . ' and  ' . self::$table_meta . '.main=1
                                        ');
                $order['order_items'] = $order_items;
                $orders[$key] = $order;
            }
        }

        return $orders;
    }

    /**************************************************************************************************************************************************
     *******************************************************(Get All Orders Without Order Items)********************************************************
     **************************************************************************************************************************************************/
    public static function AllWithoutOrderItems($Status = null, $userId = null)
    {
        if ($Status == null) {
            $query = 'SELECT ' . self::$table . '.id,' . self::$table . '.shop_id,' . self::$table .
                '.status_id,JSON_UNQUOTE(' . self::$table . '.payment) as payment,' . self::$table . '.created_at,' . self::$table_status . '.title as status_title, ' . self::$table_shops . '.domain as shop_name  from ' . self::$table . '
            LEFT JOIN ' . self::$table_status . ' on ' . self::$table_status . '.id=' . self::$table . '.status_id
            LEFT JOIN ' . self::$table_shops . ' on ' . self::$table_shops . '.id=' . self::$table . '.shop_id';

            //check if need to get orders which pointed to specific user
            if ($userId != null) {
                $query .= ' wehre ' . self::$table . '.user_id=%i';
            }

            $orders = DB::query($query, $userId);
        } else {
            $query = 'SELECT ' . self::$table . '.id,' . self::$table . '.shop_id,' .
                self::$table . '.status_id,JSON_UNQUOTE(' . self::$table . '.payment) as payment,' . self::$table . '.created_at,' . self::$table_status . '.title as status_title, ' . self::$table_shops . '.domain as shop_name  from ' . self::$table . '
            LEFT JOIN ' . self::$table_status . ' on ' . self::$table_status . '.id=' . self::$table . '.status_id
            LEFT JOIN ' . self::$table_shops . ' on ' . self::$table_shops . '.id=' . self::$table . '.shop_id
            where ' . self::$table . '.status_id=%i';

            //check if need to get orders which pointed to specific user
            if ($userId != null) {
                $query .= ' and ' . self::$table . '.user_id=%i';
            }

            $orders = DB::query($query, $Status, $userId);
        }

        return $orders;
    }

    /**************************************************************************************************************************************************
     *********************************************************(Get All Orders Items in an Order)********************************************************
     **************************************************************************************************************************************************/
    public static function AllOrderItems($order)
    {
        $order_items = DB::query('
                                SELECT * from ' . self::$table_order_items . '
                                LEFT join ' . self::$table_product . '  on ' . self::$table_order_items . '.product_id = ' . self::$table_product . '.id
                                LEFT JOIN ' . self::$table_price . ' ON ' . self::$table_price . '.product_id = ' . self::$table_product . '.id
                                LEFT join ' . self::$table_meta . '  on ' . self::$table_meta . '.product_id = ' . self::$table_product . '.id
                                where ' . self::$table_order_items . '.order_id=' . $order . ' and  ' . self::$table_meta . '.main=1
                                ');
        return $order_items;
    }

    /**************************************************************************************************************************************************
     *******************************************************************(Update Status)****************************************************************
     **************************************************************************************************************************************************/
    public static function ChangeStatus($id, $status_id, $inform = false)
    {
        $status = DB::queryFirstRow('SELECT * FROM ' . self::$table_status . ' where id=%i', $status_id);
        $order = '';

        //check order if seleced by payafter no needs to reselect it
        if ($inform || $status_id == 3) {
            $inform = true;
            $order = self::GetSingle($id);
        }
        if ($status_id == 3) {
            if ($order == '') {
                $order = self::GetSingle($id);
            }
            $paymentTitle = strtolower($order['payment']['type']);
            if ($paymentTitle == 'payafter') {
                $orderDetails = $order['order_details'];
                $multiSafepay = new MultiSafepay();
                $multiSafepay->payAfterNoty($order['transaction_id'], $id, $orderDetails['address']['shipping']['zipcode'], $orderDetails['address']['shipping']['countryCode']);
            }
        }
        //check if user inform is on
        $udpatedAt = Carbon::now()->format('Y-m-d H:i:s');
        $check = DB::update(self::$table, [
            'status_id' => $status_id,
            'user_id' => Auth::user_id(),
            'updated_at' => $udpatedAt], 'id=%s', $id);

        UserActivity::Record('Change Status to ' . $status['title'] . ' (' . $status_id . ')', $id, 'Orders');

        //event call
        $event = new Event();
        $event->orderStatusBol($id);
        $event->orderStatusChanged($id, $status_id, $inform);
        if ($check) {
            return $udpatedAt;
        } else {
            return $check;
        }
        
    }

    /**************************************************************************************************************************************************
     *******************************************************************(Update Status)****************************************************************
     **************************************************************************************************************************************************/
    //not in use, moved to event cclass
    public static function notifyMail($order, $status_id)
    {
        $mail = new Mail();
        $status = DB::query("select name->>'$." . language . "' as name, template_id->>'$." . language . "' as template_id  from order_status where id =%i limit 1", $status_id);

        if ($status) {
            $template = $status[0]['template_id'];
            $title = $status[0]['name'];
        } else {
            $template = 1;
            $title = '';
        }
        $data = [];
        $data['replyTo'] = 'sam@123bestdeal.nl';
        $data['tags'] = ['order', 'status_' . $status_id];
        switch ($status_id) {
            case 3:
                $mail->prepareOrderSentMailData($order, $data);
                break;
            default:
                $mail->prepareOrderSentMailData($order, $data);
                break;
        }
        return $mail->sendMailTemplate($template);
    }

    /**************************************************************************************************************************************************
     *******************************************************************(Get Single )******************************************************************
     **************************************************************************************************************************************************/

    public static function GetSingle($id)
    {
        //Select Order
        $order = DB::queryFirstRow('SELECT ' . self::$table . '.*,JSON_UNQUOTE(' . self::$table . '.order_details) as order_details,JSON_UNQUOTE(' . self::$table . '.payment) as payment,
        ' . self::$table_shops . '.domain
         from ' . self::$table . '
         LEFT JOIN  ' . self::$table_shops . ' on  ' . self::$table . '.shop_id = ' . self::$table_shops . '.id
         WHERE ' . self::$table . '.id=%i ', $id);

        //Select OrderItems
        $order_items = DB::query('SELECT ' . self::$table_order_items . '.* from ' . self::$table_order_items . '
                                  where ' . self::$table_order_items . '.order_id=%i
                                 ', $id);

        foreach ($order_items as $key => $order_item) {
            $product = Product::getProduct($order_item['product_id']);
            $order_items[$key]['product'] = $product;
            $itemAttributes = DB::query(
                'SELECT ' . self::$table_item_attribute . '.* , ' . self::$table_attribute . '.attribute_group_id, ' . self::$table_attribute . ".name->>'$." . language . "' as title from " . self::$table_item_attribute . '
               left join ' . self::$table_attribute . ' on ' . self::$table_item_attribute . '.attribute_id = ' . self::$table_attribute . '.id
               where ' . self::$table_item_attribute . '.order_item_id=%i
                                     ',
                $order_item['id']
            );

            foreach ($itemAttributes as $itemAttribute) {
                $order_items[$key]['attributes'][] = $itemAttribute;
            }
        }
        $dhl = DB::queryFirstRow('select * from selektvracht where order_id =%i order by id', $id);
        if ($dhl) {
            $order['dhl'] = $dhl;
        }
        $order['order_items'] = $order_items;
        $notes = DB::query('SELECT ' . self::$table_notes . '.* , ' . self::$table_users . '. name as user_from_name from ' . self::$table_notes . '
            LEFT JOIN ' . self::$table_users . ' on ' . self::$table_notes . '.user_id=' . self::$table_users . '.id
            where ' . self::$table_notes . '.order_id=%i order by created_at DESC', $id);
        //$order['notes']=$notes;
        $count = DB::count();
        $users = Auth::All();

        foreach ($notes as $key => $note) {
            if (array_key_exists($note['user_id_to'], $users)) {
                $notes[$key]['user_to'] = $users[$note['user_id_to']];
            }
        }

        $order['notes'] = $notes;
        $order['notes_count'] = $count;

        $order['order_details'] = json_decode($order['order_details'], true);
        $order['payment'] = json_decode($order['payment'], true);

        //client old orders
        $clinetsOrders = DB::query(
            'SELECT ' . self::$table . '.id,' . self::$table . '.status_id,' . self::$table_shops . '.domain,' . self::$table . '.created_at
         from ' . self::$table . '
         LEFT JOIN  ' . self::$table_shops . ' on  ' . self::$table . '.shop_id = ' . self::$table_shops . '.id
         WHERE  ' . self::$table . '.id != %i
          and ((' . self::$table . ".order_details->>'$.address.payment.zipcode'= %s
         and " . self::$table . ".order_details->>'$.address.payment.houseNumber'= %s
         and " . self::$table . ".order_details->>'$.address.payment.street'= %s)
         or (" . self::$table . ".order_details->>'$.customerEmail' = %s))",
            $id,
            $order['order_details']['address']['payment']['zipcode'],
            $order['order_details']['address']['payment']['houseNumber'],
            $order['order_details']['address']['payment']['street'],
            $order['order_details']['customerEmail']
        );
        $order['clinetsOrders'] = $clinetsOrders;
        return $order;
    }

    /**************************************************************************************************************************************************
     *******************************************************************( Update )*********************************************************************
     **************************************************************************************************************************************************/
    public function Update($id)
    {
        UserActivity::Record('Update Order', $id, 'Orders');
        $order = DB::queryFirstRow('SELECT order_details  FROM ' . self::$table . ' where id=%i', $id);

        $check = DB::update(self::$table, [['order_details' => json_encode($this->order_details), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')]], 'id=%i', $id);
        if ($check) {
            return true;
        } else {
            false;
        }
    }

    /**************************************************************************************************************************************************
     ***************************************************************( Get Orders Ids )*****************************************************************
     **************************************************************************************************************************************************/
    public static function getAllOrdersIds($search = null)
    {
        if ($search == null) {
            return DB::queryOneColumn('id', 'SELECT * FROM ' . self::$table);
        } else {
            return DB::queryOneColumn('id', 'SELECT * FROM ' . self::$table . ' where id like %s', $search . '%');
        }
    }

    /**************************************************************************************************************************************************
     **********************************************************(Get Orders Count by Status )***********************************************************
     **************************************************************************************************************************************************/
    public static function allOrdersCount($Status = null)
    {
        if ($Status == null) {
            $query = 'SELECT ' . self::$table . '.id,' . self::$table . '.shop_id,' . self::$table .
                '.status_id,JSON_UNQUOTE(' . self::$table . ".payment) as payment,
            JSON_UNQUOTE(JSON_EXTRACT(order_details, '$.address.payment.firstname')) as firstname,JSON_UNQUOTE(JSON_EXTRACT(order_details, '$.address.payment.lastname')) as lastname,
            JSON_UNQUOTE(" . self::$table . '.payment) as payment,' . self::$table . '.gross_price,
            ' . self::$table . '.created_at,' . self::$table_status . '.title as status_title, ' . self::$table_shops . '.domain as shop_name  from ' . self::$table . '
            LEFT JOIN ' . self::$table_status . ' on ' . self::$table_status . '.id=' . self::$table . '.status_id
            LEFT JOIN ' . self::$table_shops . ' on ' . self::$table_shops . '.id=' . self::$table . '.shop_id order by ' . self::$table . '.id DESC';
            $orders = DB::query($query);
        } else {
            $query = 'SELECT ' . self::$table . '.id,' . self::$table . '.shop_id,' .
                self::$table . '.status_id,JSON_UNQUOTE(' . self::$table . '.payment) as payment,' . self::$table . ".gross_price,
              JSON_UNQUOTE(JSON_EXTRACT(order_details, '$.address.payment.firstname')) as firstname,JSON_UNQUOTE(JSON_EXTRACT(order_details, '$.address.payment.lastname')) as lastname,
            " . self::$table . '.created_at,' . self::$table_status . '.title as status_title, ' . self::$table_shops . '.domain as shop_name  from ' . self::$table . '
            LEFT JOIN ' . self::$table_status . ' on ' . self::$table_status . '.id=' . self::$table . '.status_id
            LEFT JOIN ' . self::$table_shops . ' on ' . self::$table_shops . '.id=' . self::$table . '.shop_id
            where ' . self::$table . '.status_id=%i order by ' . self::$table . '.id DESC';
            $orders = DB::query($query, $Status);
        }
        
        return $orders;
    }

    /**************************************************************************************************************************************************
     ****************************************************(Get Orders Count by Status and Date )********************************************************
     **************************************************************************************************************************************************/
    public static function allPaidOrdersCountByDateShop($shop, $date, $operator = 'LIKE')
    {
        return DB::query('select id from ' . self::$table . ' where ispaid=1 and shop_id=' . $shop . ' and created_at ' . $operator . " '" . $date . "%' AND status_id != 8 AND status_id != 12 AND status_id != 13");
    }

    /**************************************************************************************************************************************************
     *******************************************************( PDF Generator for order invoice )********************************************************
     **************************************************************************************************************************************************/
    public function pdfGeneratorOrder($order = '', $id)
    {
        if ($order == '') {
            $order = Order::GetSingle($id);
        }
        if (!$order['id']) {
            return false;
        }
        $html = '
        <html>
        <head>
        <style>
        body {font-family: sans-serif;
            font-size: 10pt;
        }
        p {	margin: 0pt; }
        table.items {
            border: 0.1mm solid #000000;
        }
        td { vertical-align: top; }
        .items td {
            border-left: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
        }
        table thead td { background-color: #EEEEEE;
            text-align: center;
            border: 0.1mm solid #000000;
            font-variant: small-caps;
        }
        .items td.blanktotal {
            background-color: #EEEEEE;
            border: 0.1mm solid #000000;
            background-color: #FFFFFF;

            border-top: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
        }
        .items td.totals {
            text-align: right;
            border: 0.1mm solid #000000;
        }
        .items td.cost {
            text-align: "." center;
        }
        .date{
            margin-bottom:20px;
        }
        table.items,table.address{
            margin-top:50px;
        }
        .image{
            width:100%;
            text-align:center;
            width:300px;
            margin:0 auto;
            margin-bottom:30px;
        }
        </style>
        </head>
        <body>
        <!--mpdf
        <htmlpageheader name="myheader">
        <div class="image"><img src="https://www.123bestdeal.nl/assets/images/frontpage/123_clean.png"/></div>
        <table width="100%"><tr>
        <td width="50%" style="color:#0000BB; "><span style="font-weight: bold; font-size: 14pt;">123BestDeal BV </span><br />Molenstraat 24<br />7491BG Delden<br /><span style="font-family:dejavusanscondensed;">&#9742;</span> 0031 74 201 3490
        <br>KVK: 58214534<br>
        BTW: NL852927113.B01</td>
        <td width="50%" style="text-align: right;">Order No.<br /><span style="font-weight: bold; font-size: 12pt;">';
        $html .= $order['id'];
        $html .= '</span></td>
        </tr></table>
        </htmlpageheader>
        <htmlpagefooter name="myfooter">
        <div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
        pagina {PAGENO} van {nb}
        </div>
        </htmlpagefooter>
        <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
        <sethtmlpagefooter name="myfooter" value="on" />
        mpdf-->
        <div style="text-align: right" class="date">Date:';
        $html .= $order['created_at'];
        $html .= '</div>
        <table width="100%" style="font-family: serif;" cellpadding="10" class="address"><tr>
        <td width="45%" style="border: 0.1mm solid #888888; "><span style="font-size: 7pt; color: #555555; font-family: sans;">VERKOCHT AAN:</span><br /><br />';
        $address_1 = $order['order_details']['address']['payment']['firstname'] . ' ' . $order['order_details']['address']['payment']['lastname'] . '<br>' .
        $order['order_details']['address']['payment']['street'] . ' ' . $order['order_details']['address']['payment']['houseNumber'] . ' ' . $order['order_details']['address']['payment']['houseNumberSupplement'] .
        '<br>' . $order['order_details']['address']['payment']['zipcode'] . ' ' . $order['order_details']['address']['payment']['city'];

        $address_2 = $order['order_details']['address']['shipping']['firstname'] . ' ' . $order['order_details']['address']['shipping']['lastname'] . '<br>' .
        $order['order_details']['address']['shipping']['street'] . ' ' . $order['order_details']['address']['shipping']['houseNumber'] . ' ' . $order['order_details']['address']['shipping']['houseNumberSupplement'] .
        '<br>' . $order['order_details']['address']['shipping']['zipcode'] . ' ' . $order['order_details']['address']['shipping']['city'];

        $html .= $address_1 . '</td>
        <td width="10%">&nbsp;</td>
        <td width="45%" style="border: 0.1mm solid #888888;"><span style="font-size: 7pt; color: #555555; font-family: sans;">Verzend naar:</span><br /><br />';
        $html .= $address_2 . '</td>
        </tr></table>
        <br />
        <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
        <thead>
        <tr>
        <td width="10%">aantal</td>
        <td width="15%">artikelnr</td>
        <td width="45%">omschrijving</td>
        <td width="15%">prijs /st</td>
        <td width="15%">totaal</td>
        </tr>
        </thead>
        <tbody>
        ';
        foreach ($order['order_items'] as $item) {
            $html .= '<tr>';
            $html .= '<td align="center">';
            $html .= $item['count'];
            $html .= '</td>';
            $html .= '<td align="center">';
            $html .= $item['product']['sku'];
            $html .= '</td>';
            $html .= '<td>';
            $html .= $item['product_name'];
            $html .= '</td>';
            $html .= '<td class="cost">&euro; ';
            $html .= $item['price'];
            $html .= '</td>';
            $html .= '<td class="cost">&euro; ';
            $html .= $item['totalprice'];
            $html .= '</td>';
            $html .= '</tr>';
        }

        $html .= '
        <tr>
        <td class="blanktotal" colspan="3" rowspan="6"></td>
        <td class="totals">Subtotaal:</td>
        <td class="totals cost">&euro; ';
        $html .= $order['net_price'];
        $html .= '</td>
        </tr>
        <tr>
        <td class="totals">BTW 21%:</td>
        <td class="totals cost">&euro; ';
        $html .= $order['vat'];
        $html .= '</td>
        </tr>';
        /* <tr>
         <td class="totals">Verzendkosten:</td>
         <td class="totals cost">&euro; ';$html.=$order['shipping_cost'];$html.='</td>
         </tr>*/
        $html .= ' <tr>
        <td class="totals"><b>Totaal:</b></td>
        <td class="totals cost"><b>&euro; ';
        $html .= $order['gross_price'];
        $html .= '</b></td>
        </tr>
        </tbody>
        </table>
        </body>
        </html>
        ';

        $this->pdf = new Pdf();
        $this->pdf->mpdf->SetProtection(['print']);
        $this->pdf->mpdf->SetTitle('123BestDeal BV - factuur ' . $order['id']);
        $this->pdf->mpdf->SetAuthor('123BestDeal BV');
        if ($order['ispaid'] == 1) {
            // $this->pdf->mpdf->SetWatermarkText("Betaald");
        } else {
            // $this->pdf->mpdf->SetWatermarkText("Onbetaald");
        }
        $this->pdf->mpdf->showWatermarkText = true;
        $this->pdf->mpdf->watermark_font = 'DejaVuSansCondensed';
        $this->pdf->mpdf->watermarkTextAlpha = 0.07;
        $this->pdf->mpdf->SetDisplayMode('fullpage');
        $this->pdf->mpdf->WriteHTML($html);
        return $this->pdf;
    }

    /**************************************************************************************************************************************************
     *******************************************************( PDF Generator for order invoice )********************************************************
     **************************************************************************************************************************************************/
    public function pdfGeneratorOrderInline($orderId)
    {
        $this->pdf->mpdf->Output('factuur_' . $orderId . '.pdf', Destination::INLINE);
    }
}
