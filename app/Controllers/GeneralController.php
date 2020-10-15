<?php

namespace App\Controllers;

use Slim\View\Twig as View;
use DB;
use Carbon\Carbon;
use App\Classes\Product;
use App\Classes\Order;
use App\Classes\Mail;
use App\Classes\General;
use App\Classes\UserActivity;
use Slim\Exception\NotFoundException;
use Respect\Validation\Validator as v;
use App\Auth\Auth;

class GeneralController extends Controller
{
    /**
     * homeIndex function
     *
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return array
     */
    public function homeIndex($request, $response, $args)
    {
        $orders['newOrders'] = Order::allOrdersCount(1);
        $orders['claimedOrders'] = Order::allOrdersCount(10);
        $orders['readyForShippingOrders'] = Order::allOrdersCount(15);
        $orders['returnOrders'] = Order::allOrdersCount(5);
        $orders['returnCredite'] = Order::allOrdersCount(4);
        $orders['returnChange'] = Order::allOrdersCount(14);
        $orders['waitSupplierOrders'] = Order::allOrdersCount(9);
        $orders['waitExternalSupplierOrders'] = Order::allOrdersCount(19);
        $orders['waitCustomerOrders'] = Order::allOrdersCount(11);
        $orders['creditOrders'] = Order::allOrdersCount(16);
        $orders['waitPaymentOrders'] = Order::allOrdersCount(13);

        //latestTenDays
        $today = date('Y-m-d');
        $time = strtotime($today);
        $yesterday = date('Y-m-d', strtotime('-1 day', $time));
        $yesterday1 = date('Y-m-d', strtotime('-2 days', $time));
        $yesterday3 = date('Y-m-d', strtotime('-3 days', $time));
        $yesterday4 = date('Y-m-d', strtotime('-4 days', $time));
        $yesterday5 = date('Y-m-d', strtotime('-5 days', $time));
        $yesterday6 = date('Y-m-d', strtotime('-6 days', $time));
        $orders['nlToday'] = Order::allPaidOrdersCountByDateShop(1, $today, 'LIKE');
        $orders['comToday'] = Order::allPaidOrdersCountByDateShop(2, $today, 'LIKE');
        $orders['bolToday'] = Order::allPaidOrdersCountByDateShop(3, $today, 'LIKE');
        //yesterda
        $orders['nlYesterday'] = Order::allPaidOrdersCountByDateShop(1, $yesterday, 'LIKE');
        $orders['comYesterday'] = Order::allPaidOrdersCountByDateShop(2, $yesterday, 'LIKE');
        $orders['bolYesterday'] = Order::allPaidOrdersCountByDateShop(3, $yesterday, 'LIKE');
        //day before yesterday
        $orders['nlBeforeYesterday'] = Order::allPaidOrdersCountByDateShop(1, $yesterday1, 'LIKE');
        $orders['comBeforeYesterday'] = Order::allPaidOrdersCountByDateShop(2, $yesterday1, 'LIKE');
        $orders['bolBeforeYesterday'] = Order::allPaidOrdersCountByDateShop(3, $yesterday1, 'LIKE');
        //day before 3
        $orders['nlBefore3Yesterday'] = Order::allPaidOrdersCountByDateShop(1, $yesterday3, 'LIKE');
        $orders['comBefore3Yesterday'] = Order::allPaidOrdersCountByDateShop(2, $yesterday3, 'LIKE');
        $orders['bolBefore3Yesterday'] = Order::allPaidOrdersCountByDateShop(3, $yesterday3, 'LIKE');
        //day before 4
        $orders['nlBefore4Yesterday'] = Order::allPaidOrdersCountByDateShop(1, $yesterday4, 'LIKE');
        $orders['comBefore4Yesterday'] = Order::allPaidOrdersCountByDateShop(2, $yesterday4, 'LIKE');
        $orders['bolBefore4Yesterday'] = Order::allPaidOrdersCountByDateShop(3, $yesterday4, 'LIKE');
        //day before 5
        $orders['nlBefore5Yesterday'] = Order::allPaidOrdersCountByDateShop(1, $yesterday5, 'LIKE');
        $orders['comBefore5Yesterday'] = Order::allPaidOrdersCountByDateShop(2, $yesterday5, 'LIKE');
        $orders['bolBefore5Yesterday'] = Order::allPaidOrdersCountByDateShop(3, $yesterday5, 'LIKE');
        //day before 6
        $orders['nlBefore6Yesterday'] = Order::allPaidOrdersCountByDateShop(1, $yesterday6, 'LIKE');
        $orders['comBefore6Yesterday'] = Order::allPaidOrdersCountByDateShop(2, $yesterday6, 'LIKE');
        $orders['bolBefore6Yesterday'] = Order::allPaidOrdersCountByDateShop(3, $yesterday6, 'LIKE');
        //latestTenDays
        $tenDays = Carbon::today()->subDays(10);
        $orders['tenDaysNl'] = Order::allPaidOrdersCountByDateShop('1', $tenDays, '>=');
        $orders['tenDaysCom'] = Order::allPaidOrdersCountByDateShop('2', $tenDays, '>=');
        $orders['tenDaysBol'] = Order::allPaidOrdersCountByDateShop('3', $tenDays, '>=');

        $orders['sevenDaysCount'] = count($orders['nlToday']) + count($orders['comToday']) + 
        count($orders['nlYesterday']) + count($orders['comYesterday']) + 
        count($orders['nlBeforeYesterday']) + count($orders['comBeforeYesterday']) + count($orders['bolBeforeYesterday']) +
        count($orders['nlBefore3Yesterday']) + count($orders['comBefore3Yesterday']) + count($orders['bolBefore3Yesterday']) +
        count($orders['nlBefore4Yesterday']) + count($orders['comBefore4Yesterday']) + count($orders['bolBefore4Yesterday']) +
        count($orders['nlBefore5Yesterday']) + count($orders['comBefore5Yesterday']) + count($orders['bolBefore5Yesterday']) +
        count($orders['nlBefore6Yesterday']) + count($orders['comBefore6Yesterday']) + count($orders['bolBefore6Yesterday']);
        $orders['todayCount'] = count($orders['nlToday']) + count($orders['comToday']) + count($orders['bolToday']);
        $orders['yesterdayCount'] = count($orders['nlYesterday']) + count($orders['comYesterday']) + count($orders['bolYesterday']);
        $orders['beforeYesterdayCount'] = count($orders['nlBeforeYesterday']) + count($orders['comBeforeYesterday']) + count($orders['cbolBeforeYesterday']);

        //yearly
        $monthlyOrders = DB::query("SELECT count(*) as orders,DATE_FORMAT(created_at,'%m') as date from " . Order::$table . "
        where DATE_FORMAT(created_at,'%Y') = 2020 and ispaid =1 and status_id != 8 AND status_id != 12 AND status_id != 13
        GROUP BY DATE_FORMAT(created_at,'%m') order by DATE_FORMAT(created_at,'%m')");
        $monthlyOrdersTemp = array( '01' => 0 , '02' => 0 , '03' => 0 , '04' => 0 , '05' => 0 , '06' => 0 , '07' => 0 , '08' => 0 , '09' => 0 , '10' => 0 , '11' => 0 , '12' => 0);
        foreach ($monthlyOrders as $monthlyOrder){
            $monthlyOrdersTemp[$monthlyOrder['date']] = $monthlyOrder['orders'];
        }
        
        //latest orders 
        $orders['latestOrders'] = DB::query('SELECT ' . Order::$table . '.id,' . Order::$table . '.shop_id,' . Order::$table . '.gross_price,
        ' . Order::$table . '.status_id,JSON_UNQUOTE(' . Order::$table . '.payment) as payment,
        ' . Order::$table . ".order_details->>'$.address.payment.firstname' as firstname," . Order::$table . ".order_details->>'$.address.payment.lastname' as lastname,
        " . Order::$table . '.created_at,' . Order::$table_status . '.title as status_title, ' . Order::$table_shops . '.domain as shop_name  from ' . Order::$table . '
        LEFT JOIN ' . Order::$table_status . ' on ' . Order::$table_status . '.id=' . Order::$table . '.status_id
        LEFT JOIN ' . Order::$table_shops . ' on ' . Order::$table_shops . '.id=' . Order::$table . '.shop_id
        order by created_at DESC');

        //Products
        $someInStock = DB::query("SELECT id,sku,JSON_UNQUOTE(JSON_EXTRACT(contents, '$." . language . ".title')) as title FROM product WHERE stocklevel = 2 AND location != '0,0,0'");
        $soonDeliver = DB::query("SELECT id,sku,delivery_at,JSON_UNQUOTE(JSON_EXTRACT(contents, '$." . language . ".title')) as title FROM product WHERE stocklevel = 3 AND location != '0,0,0'");
        $outStock = DB::query("SELECT id,sku,JSON_UNQUOTE(JSON_EXTRACT(contents, '$." . language . ".title')) as title FROM product WHERE stocklevel = 4 AND location != '0,0,0' AND `location` != 'x,x,x'");
        $notGranted = DB::query("SELECT id,sku,JSON_UNQUOTE(JSON_EXTRACT(contents, '$." . language . ".title')) as title FROM product WHERE location != '0,0,0' AND location != 'x,x,x' AND location != ',,' AND active=0 ORDER BY sku ASC");
        
        //weekly revenue
        $weeklyRevenue = DB::query("SELECT CONCAT('week ',WEEK(created_at)) weeks,SUM(gross_price) as gross_price
        FROM orders 
        WHERE created_at > DATE_SUB(NOW(), INTERVAL 10 WEEK)
        GROUP BY weeks
        ORDER BY weeks ASC;");

        //select template based on user
        if ($this->auth->super()) {
            $template = 'home/index-deluxe.tpl';
        }
        else {
            $template = 'home/index.tpl';
        }

        return $this->view->render($response, $template, ['page_title' => 'Dashboard',
        'monthlyOrders' => $monthlyOrdersTemp,'someInStock' => $someInStock, 'soonDeliver' => $soonDeliver,
        'outStock' => $outStock, 'notGranted' => $notGranted, 'weeklyRevenue' => $weeklyRevenue,'orders' => $orders]);
    }

    /**************************************************************************************************************************************************
     *******************************************************( Get Products Data From ID )**************************************************************
     **************************************************************************************************************************************************/
    public function getProductDataById($request, $response, $args)
    {
        $product = Product::getProduct($args['id']);
        $attributes = Product::getAttributesKeys($args['id']);
        if (isset($attributes['products']['1'])) {
            $product['colors'] = $attributes['products']['1'];
        } else {
            $product['colors'] = '';
        }
        if (isset($attributes['products']['2'])) {
            $product['sizes'] = $attributes['products']['2'];
        } else {
            $product['sizes'] = '';
        }
        if (isset($product['images']) && count($product['images']) > 0) {
            $product['image'] = $product['images'][0];
        } else {
            $product['image'] = '';
        }
        unset($product['images']);
        return $response->withJson(['product' => $product], 201);
    }

    /**************************************************************************************************************************************************
     **************************************************************(Main Live Search)******************************************************************
     **************************************************************************************************************************************************/
    public function mainLiveSearch($request, $response, $args)
    {
        $products = '';
        $order = '';
        $s = strtolower($request->getParam('search'));
        $products = DB::query("select id ,sku, JSON_UNQUOTE(JSON_EXTRACT(contents, '$.nl.title')) as title from product
                                where LOWER(JSON_UNQUOTE(JSON_EXTRACT(contents, '$.nl.title'))) like %s
                                or sku like %s
                                or id like %s
                                limit  10", $s . '%', $s . '%', $s . '%');
        $orders = DB::query(
            "select orders.id,
                             JSON_UNQUOTE(JSON_EXTRACT(order_details, '$.address.payment.firstname')) as firstname,
                             JSON_UNQUOTE(JSON_EXTRACT(order_details, '$.address.payment.lastname')) as lastname,
                             title
                             from orders
                             left join order_status on orders.status_id = order_status.id
                             where LOWER(JSON_UNQUOTE(JSON_EXTRACT(orders.order_details, '$.address.payment.firstname'))) like %s
                             OR LOWER(JSON_UNQUOTE(JSON_EXTRACT(orders.order_details, '$.address.payment.lastname'))) like %s
                             OR LOWER(JSON_UNQUOTE(JSON_EXTRACT(orders.order_details, '$.address.payment.zipcode'))) like %s
                             OR LOWER(JSON_UNQUOTE(JSON_EXTRACT(orders.order_details, '$.address.payment.company'))) like %s
                             OR LOWER(JSON_UNQUOTE(JSON_EXTRACT(orders.order_details, '$.address.shipping.firstname'))) like %s
                             OR LOWER(JSON_UNQUOTE(JSON_EXTRACT(orders.order_details, '$.address.shipping.lastname'))) like %s
                             OR LOWER(JSON_UNQUOTE(JSON_EXTRACT(orders.order_details, '$.address.shipping.zipcode'))) like %s
                             OR LOWER(JSON_UNQUOTE(JSON_EXTRACT(orders.order_details, '$.address.shipping.company'))) like %s
                             OR LOWER(JSON_UNQUOTE(JSON_EXTRACT(orders.order_details, '$.customerEmail'))) like %s
                             OR LOWER (orders.invoicenr) like %s
                             OR LOWER(JSON_UNQUOTE(JSON_EXTRACT(orders.payment, '$.payment_identifier'))) like %s
                             OR orders.id like %s
                             limit  10",
            $s . '%',
            $s . '%',
            $s . '%',
            $s . '%',
            $s . '%',
            $s . '%',
            $s . '%',
            $s . '%',
            $s . '%',
            $s . '%',
            $s . '%',
            $s . '%'
        );
        return $response->withJson(['products' => $products, 'orders' => $orders]);
    }

    /**************************************************************************************************************************************************
     ***************************************************************( BarcodeGet )*********************************************************************
     **************************************************************************************************************************************************/
    public function barcodeGet($request, $response, $args)
    {
        return $this->view->render($response, 'barcode/index.tpl', ['active_menu' => 'barcode', 'page_title' => 'barcode']);
    }

    public function barcodeRead($request, $response, $args)
    {
        $validation = $this->validator->validate($request, ['barcode' => v::notEmpty(), ]);
        if ($validation->failed()) {
            return $response->withJson(['return' => 'Error', 'msg' => 'The Barcode is required']);
        }

        $barcode = $request->getParam('barcode');

        if (stristr($barcode, '3SBDL') !== false) {
            $order = str_replace('3SBDL1', '', $barcode);
            $keeporder = $order;
            $order = DB::queryFirstRow('SELECT * FROM orders WHERE tracktrace=%s', $barcode);
            if (!$order) {
                return $response->withJson(['return' => 'Error', 'msg' => 'Deze order is niet gevonden!']);
            }
            $link = $this->router->pathFor('OrdersGetSingle', ['id' => $order['id']]);
            if ($order['status_id'] == '3') {
                return $response->withJson(['return' => 'Error', 'msg' => 'Deze order staat al gemarkeerd als Verzonden!' . " <a href='" . $link . "' target='_blank' style='text-decoration: underline'>Klik hier om naar de bestelling te gaan</a>"]);
            } else {
                Order::ChangeStatus($order['id'], '3', true);
                // $order->GenerateInvoice();
                $firstpart = substr($order['shop_id'], 0, 2);
                if ($firstpart == 20) {
                    // bol
                    // include('/data/www/i12cover.nl/public_html/home/inc/bol_plaza_api/orders.v2.process.php');
                } else {
                }
                return $response->withJson(['return' => 'True', 'msg' => 'Selektvracht Verzendlabel' . " <a href='" . $link . "' target='_blank' style='text-decoration: underline'>Klik hier om naar de bestelling te gaan</a>"]);
            }
        } else {
            $order = DB::queryFirstRow('SELECT * FROM orders WHERE id=%s', $barcode);
            if (!$order) {
                return $response->withJson(['return' => 'Error', 'msg' => 'Deze order is niet gevonden!']);
            }
            $link = $this->router->pathFor('OrdersGetSingle', ['id' => $order['id']]);
            if ($order['status_id'] == '3') {
                return $response->withJson(['return' => 'Error', 'msg' => 'Deze order staat al gemarkeerd als Verzonden!' . " <a href='" . $link . "' target='_blank' style='text-decoration: underline'>Klik hier om naar de bestelling te gaan</a>"]);
            } else {
                Order::ChangeStatus($order['id'], '3', true);
                // $order->GenerateInvoice();
                $firstpart = substr($order['id'], 0, 2);
                if ($firstpart == 20) {
                    // bol
                    // include('/data/www/i12cover.nl/public_html/home/inc/bol_plaza_api/orders.v2.process.php');
                } else {
                }
                return $response->withJson(['return' => 'True', 'msg' => 'Selektvracht Verzendlabel' . " <a href='" . $link . "' target='_blank' style='text-decoration: underline'>Klik hier om naar de bestelling te gaan</a>"]);
            }
        }
        return $response->withJson(['return' => 'Error', 'msg' => 'Nothing']);
    }

    /**************************************************************************************************************************************************
     *********************************************************(Controle Postcode Get page)*************************************************************
     **************************************************************************************************************************************************/
    public function postCodeController($request, $response, $args)
    { // View order
        //  $statuses = Status::all();
        //  return view('general.postcode_control', compact('statuses'));
    }

    /**************************************************************************************************************************************************
     *********************************************************(Controle Postcode Get List)*************************************************************
     **************************************************************************************************************************************************/
    public function postCodeControllerList($request, $response, $args)
    {
    }

    //B2B part//
    /**************************************************************************************************************************************************
     ***************************************************************(i12Customers Index Get)***********************************************************
     **************************************************************************************************************************************************/
    public function i12CustomersGetIndex($request, $response, $args)
    {
        return $this->view->render($response, 'i12_customers/index.tpl', ['active_menu' => 'b2b','page_title' => 'B2B Klanten i12Cover']);
    }

    /**************************************************************************************************************************************************
     **************************************************************(i12Customers Get Data)*********************************************************
     **************************************************************************************************************************************************/
    public function i12CustomersGetIndexData($request, $response, $args)
    {
        $customers = DB::query("
        SELECT id,created_at,details->>'$.company' as company,
        details->>'$.addresses.payment.zipcode' as zipcode
        ,details->>'$.addresses.payment.city' as city
        ,details->>'$.addresses.payment.address' as address
        ,details->>'$.addresses.payment.country' as country
        FROM i12_customers ORDER BY details->>'$.company'");
        $returndata = ['draw' => null, 'cached' => null, 'recordsTotal' => count($customers), 'recordsFiltered' => count($customers), 'data' => $customers];
        return json_encode($returndata);
    }

    /**************************************************************************************************************************************************
     ************************************************************(i12Customers Get Single)*********************************************************
     **************************************************************************************************************************************************/
    public function i12CustomersGetSingle($request, $response, $args)
    {
        $validation = $this->validator->validateGet($args, ['id' => v::notEmpty(), ]);
        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('i12Customers.GetIndex'));
        }
        $customer = DB::queryFirstRow('SELECT * FROM i12_customers WHERE id=%i', $args['id']);
        if (!$customer) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('i12Customers.GetIndex'));
        }
        $customer['details'] = json_decode($customer['details'], true);
        //notes
        $notes = DB::query('SELECT * FROM i12_customers_notes WHERE customer_id=%i order by created_at DESC', $customer['id']);
        $customer['notes'] = $notes;
        //orders
        $orders = DB::query('SELECT id,status_id,updated_at,created_at FROM orders WHERE i12_customer_id=%i and shop_id=%i and b2b!=0', $customer['id'], 4);
        $customer['orders'] = $orders;
        //status
        $status = General::getStatus();
        $user = Auth::all();
        return $this->view->render($response, 'i12_customers/customer_single.tpl', ['customer' => $customer,
        'users' => $user, 'orderStatus' => $status, 'active_menu' => 'b2b','page_title' => $customer['user_id']]);
    }

    /**************************************************************************************************************************************************
     ************************************************************(i12Customers Get Single)*********************************************************
     **************************************************************************************************************************************************/
    public function i12CustomersUpdateSingle($request, $response, $args)
    {
        $validation = $this->validator->validate($request, ['id' => v::notEmpty(), ]);
        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            if ($request->getParam('id')) {
                return $response->withRedirect($this->router->pathFor('i12Customers.GetSingle', ['id' => $request->getParam('id')]));
            } else {
                return $response->withRedirect($this->router->pathFor('i12Customers.GetIndex'));
            }
        }
        $customer = DB::queryFirstRow('SELECT user_id,details FROM i12_customers WHERE id=%i', $request->getParam('id'));
        if (!$customer) {
            $this->container->flash->addMessage('warning', 'we konden geen klant vinden met deze id ' . $request->getParam('id'));
            if ($request->getParam('id')) {
                return $response->withRedirect($this->router->pathFor('i12Customers.GetSingle', ['id' => $request->getParam('id')]));
            } else {
                return $response->withRedirect($this->router->pathFor('i12Customers.GetIndex'));
            }
        }
        $details = json_decode($customer['details'], true);
        foreach ($request->getParam('details') as $key => $content) {
            if (is_array($content)) {
                foreach ($content as $key_s_1 => $content_s_1) {
                    if (is_array($content_s_1)) {
                        foreach ($content_s_1 as $key_s_2 => $content_s_2) {
                            $details[$key][$key_s_1][$key_s_2] = $content_s_2;
                        }
                    } else {
                        $details[$key][$key_s_1] = $content_s_1;
                    }
                }
            } else {
                $details[$key] = $content;
            }
        }
        $check = DB::update('i12_customers', ['user_id' => $request->getParam('account_owner', 1), 'details' => json_encode($details), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')], 'id=%i', $request->getParam('id'));
        if ($check) {
            $this->container->flash->addMessage('success', 'Klant is bijgewerkt');
            UserActivity::Record('Update', $request->getParam('id'), 'i12Customers');
            return $response->withRedirect($this->router->pathFor('i12Customers.GetSingle', ['id' => $request->getParam('id')]));
        } else {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('i12Customers.GetSingle', ['id' => $request->getParam('id')]));
        }
    }

    /**************************************************************************************************************************************************
     *************************************************************( i12Customers Get Add )*************************************************************
     **************************************************************************************************************************************************/
    public function i12CustomersAddGet($request, $response, $args)
    {
        LangToDefault();
        $user = Auth::all();
        return $this->view->render($response, 'i12_customers/customer_add.tpl', ['users' => $user, 'active_menu' => 'b2b','page_title' => 'Klant toevoegen']);
    }

    /**************************************************************************************************************************************************
     **************************************************************( i12Customers Post New )***********************************************************
     **************************************************************************************************************************************************/
    public function i12CustomersAddPost($request, $response, $args)
    {
        $details = [];
        dd($request->getParam('details'));
        foreach ($request->getParam('details') as $key => $content) {
            if (is_array($content)) {
                foreach ($content as $key_s_1 => $content_s_1) {
                    if (is_array($content_s_1)) {
                        foreach ($content_s_1 as $key_s_2 => $content_s_2) {
                            $details[$key][$key_s_1][$key_s_2] = $content_s_2;
                        }
                    } else {
                        $details[$key][$key_s_1] = $content_s_1;
                    }
                }
            } else {
                $details[$key] = $content;
            }
        }
        $check = DB::insert('i12_customers', ['user_id' => $request->getParam('account_owner', 1), 'details' => json_encode($details), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]);

        if ($check) {
            $id = DB::insertId();
            $this->container->flash->addMessage('success', 'Klant is toegevoegd');
            UserActivity::Record('Add', $id, 'i12Customers');
            return $response->withRedirect($this->router->pathFor('i12Customers.GetSingle', ['id' => $id]));
        } else {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('i12Customers.GetAdd'));
        }
    }

    /**************************************************************************************************************************************************
     **************************************************************( i12Customers Post New Note )******************************************************
     **************************************************************************************************************************************************/
    public function i12CustomersAddPostNote($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'customer_id' => v::notEmpty(),
            'message' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Notitie data klopt niet !!']);
        }
        $check = DB::insert('i12_customers_notes', ['user_id' => Auth::user_id(), 'customer_id' => $request->getParam('customer_id'), 'note' => $request->getParam('message'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]);
        if ($check) {
            $id = DB::insertId();
            UserActivity::Record('Add', $id, 'i12Customers Note added');
            $result = DB::queryFirstRow('SELECT * FROM i12_customers_notes where id=%i', $id);
            return $response->withJson(['status' => 'true', 'msg' => 'De notitie is toegevoegd !!', 'note' => $result]);
        } else {
            return $response->withJson(['status' => 'false', 'msg' => 'De notitie is niet toegevoegd !!']);
        }
    }

    /**************************************************************************************************************************************************
     **********************************************************( i12Customers Get Invoice Data )*******************************************************
     **************************************************************************************************************************************************/
    public function i12CustomersGetInvoiceData($request, $response, $args)
    {
        $orders = DB::query("
        SELECT orders.id,orders.transaction_id,orders.ispaid,orders.created_at,i12_customers.details->'$.company' as company FROM orders
        left join i12_customers
        on i12_customers.id=orders.i12_customer_id
        where shop_id=4 and invoicenr ='' and status_id=3 and b2b !='0' and ispaid !=1
        ORDER BY i12_customers.details->'$.company'");

        $tmpOrder = [];
        foreach ($orders as $key => $order) {
            if ($order['ispaid']) {
                $pstatus = 'Order is betaald';
            } else {
                $dateCreated = new Carbon($order['created_at']);
                $now = Carbon::now();
                $diff = $dateCreated->diffInDays($now);
                $pstatus = 'Niet betaald - dagen open: ' . $diff;
            }
            $tmpOrder[$key]['payment'] = $pstatus;
            $tmpOrder[$key]['id'] = $order['id'];
            $tmpOrder[$key]['created_at'] = $order['created_at'];
            $tmpOrder[$key]['company'] = $order['company'];
            $tmpOrder[$key]['transaction_id'] = $order['transaction_id'];
        }
        $returndata = ['draw' => null, 'cached' => null, 'recordsTotal' => count($tmpOrder), 'recordsFiltered' => count($tmpOrder), 'data' => $tmpOrder];
        return json_encode($returndata);
    }

    /**************************************************************************************************************************************************
     ***************************************************************( SentPackages )*******************************************************************
     **************************************************************************************************************************************************/
    public function sentPackagesGet($request, $response, $args)
    {
        return $this->view->render($response, 'diverse/sent_packages.tpl', ['active_menu' => 'diverse','page_title' => 'Verstuurde pakketten']);
    }

    /**************************************************************************************************************************************************
     ************************************************************( SentPackages Ajax )*****************************************************************
     **************************************************************************************************************************************************/
    public function sentPackagesGetData($request, $response, $args)
    {
        $dateFrom = $request->getParam('date_from');
        $dateTo = $request->getParam('date_to');

        $b = '0';
        $p = '0';
        $h = '0';
        $t = '0';
        $results = DB::query(
            'SELECT * FROM selektvracht_waybills
                  INNER JOIN selektvracht ON selektvracht.shipment = selektvracht_waybills.id
                  WHERE selektvracht_waybills.created_at BETWEEN %s AND %s',
            $dateFrom,
            $dateTo
        );
        foreach ($results as $result) {
            if ($result['verlader'] == '39099104') {
                $b++;
            } elseif ($result['verlader'] == '79099100') {
                $p++;
            } elseif ($result['verlader'] == '79099101') {
                $h++;
            }
            $t++;
        }
        return $response->withJson(['status' => 'true', 'mailbox' => $b, 'packages' => $p, 'packages_s' => $h, 'total' => $t]);
    }

    /**************************************************************************************************************************************************
     ************************************************************( Status Get All )********************************************************************
     **************************************************************************************************************************************************/
    public function statusIndex($request, $response, $args)
    {
        $status = General::getStatus();
        return $this->view->render($response, 'status/all.tpl', ['orderStatus' => $status, 'templates' => $templates,
        'active_menu' => 'diverse','page_title' => 'Alle bestelstatussen']);
    }

    public function statusAll($request, $response, $args)
    {
        $status = DB::query("select id,title,
         template_id->>'$.nl' as template_id_nl,
         template_id->>'$.en' as template_id_en
         from order_status order by id");
        $returndata = [
            'draw' => null,
            'cached' => null,
            'recordsTotal' => count($status),
            'recordsFiltered' => count($status),
            'data' => $status
        ];
        return json_encode($returndata);
    }

    /**************************************************************************************************************************************************
     ************************************************************( Status Get Single )*****************************************************************
     **************************************************************************************************************************************************/
    public function statusGetSingle($request, $response, $args)
    {
        $validation = $this->validator->validateGet($args, ['id' => v::notEmpty(), ]);
        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('status.index'));
        }
        $status = DB::queryFirstRow("SELECT *,JSON_UNQUOTE(JSON_EXTRACT(name, '$." . language . "')) as name,JSON_UNQUOTE(JSON_EXTRACT(email_subject, '$." . language . "')) as email_subject,
        JSON_UNQUOTE(JSON_EXTRACT(email_content, '$." . language . "')) as email_content, 
        JSON_UNQUOTE(JSON_EXTRACT(template_id, '$." . language . "')) as template_id FROM order_status WHERE id=%i", $args['id']);
        if (!$status) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('status.index'));
        }
        //status
        $statuses = General::getStatus();
        $mail = new Mail;
        //$templates = $mail->getTemplates();
        $templates = '';
        return $this->view->render($response, 'status/status_single.tpl', ['templates' => $templates, 'orderStatus' => $status,
        'statuses' => $statuses, 'active_menu' => 'diverse','page_title' => $status['title'] . ' - ' . $status['id']]);
    }

    /**************************************************************************************************************************************************
     ************************************************************(i12Customers Get Single)*********************************************************
     **************************************************************************************************************************************************/
    public function statusUpdateSingle($request, $response, $args)
    {
        $validation = $this->validator->validate($request, ['id' => v::notEmpty(), ]);
        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            if ($request->getParam('id')) {
                return $response->withRedirect($this->router->pathFor('status.Single', ['id' => $request->getParam('id')]));
            } else {
                return $response->withRedirect($this->router->pathFor('status.index'));
            }
        }
        $status = DB::queryFirstRow('SELECT * FROM order_status WHERE id=%i', $request->getParam('id'));
        if (!$status) {
            $this->container->flash->addMessage('warning', 'we konden geen status vinden met deze id ' . $request->getParam('id'));
            if ($request->getParam('id')) {
                return $response->withRedirect($this->router->pathFor('status.Single', ['id' => $request->getParam('id')]));
            } else {
                return $response->withRedirect($this->router->pathFor('status.index'));
            }
        }

        $templateId = json_decode($status['template_id'], true);
        //$templateId[language] = $request->getParam('template', '');
        $name = json_decode($status['name'], true);
        $name[language] = $request->getParam('name', '');
        $emailSubject = json_decode($status['email_subject'], true);
        $emailSubject[language] =$request->getParam('email_subject', '');
        $emailContent = json_decode($status['email_content'], true);
        $emailContent[language] =$request->getParam('email_content', '');
        $check = DB::update('order_status', ['name' => json_encode($name), 'email_subject' => json_encode($emailSubject),
        'email_content' => json_encode($emailContent),
         'template_id' => json_encode($templateId)], 'id=%i', $request->getParam('id'));
        if ($check) {
            $this->container->flash->addMessage('success', 'Status is bijgewerkt');
            UserActivity::Record('Update', $request->getParam('id'), 'Status');
            return $response->withRedirect($this->router->pathFor('status.Single', ['id' => $request->getParam('id')]));
        } else {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('status.Single', ['id' => $request->getParam('id')]));
        }
    }

    //////////////-----------------------------
    /**************************************************************************************************************************************************
     ***************************************************************(Coupons Index Get)***********************************************************
     **************************************************************************************************************************************************/
    public function CouponsGetIndex($request, $response, $args)
    {
        return $this->view->render($response, 'coupons/index.tpl', ['active_menu' => 'diverse','page_title' => 'Kortingscodes']);
    }

    /**************************************************************************************************************************************************
     **************************************************************(Coupons Get Data)*********************************************************
     **************************************************************************************************************************************************/
    public function CouponsGetIndexData($request, $response, $args)
    {
        $coupons = DB::query('
            SELECT discount_codes.*,shop.domain from discount_codes
            left join shop
            on shop.id=discount_codes.shop_id
            ');
        $returndata = ['draw' => null, 'cached' => null, 'recordsTotal' => count($coupons), 'recordsFiltered' => count($coupons), 'data' => $coupons];
        return json_encode($returndata);
    }

    /**************************************************************************************************************************************************
     ************************************************************(Coupons Get Single)*********************************************************
     **************************************************************************************************************************************************/
    public function CouponsGetSingle($request, $response, $args)
    {
        $validation = $this->validator->validateGet($args, ['id' => v::notEmpty(), ]);
        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Coupons.GetIndex'));
        }
        $coupon = DB::queryFirstRow('SELECT * FROM discount_codes WHERE id=%i', $args['id']);
        if (!$coupon) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Coupons.GetIndex'));
        }

        $shops = General::getShops();
        if ($coupon['discount_amount'] == 0 && $coupon['discount_perc'] != 0) {
            $coupon['discount_amount'] = $coupon['discount_perc'] . '%';
        }

        return $this->view->render($response, 'coupons/coupon_single.tpl', ['coupon' => $coupon,
        'shops' => $shops, 'active_menu' => 'diverse','page_title' => 'Kortingscode -' . $coupon['id']]);
    }

    /**************************************************************************************************************************************************
     ************************************************************(Coupons Get Single)*********************************************************
     **************************************************************************************************************************************************/
    public function CouponsUpdateSingle($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty(),
            'code' => v::notEmpty(),
            'shop_id' => v::notEmpty(),
            'min_amount' => v::notEmpty(),
            'max_amount' => v::notEmpty(),
            'discount' => v::notEmpty(),
            'valid_from' => v::notEmpty(),
            'valid_until' => v::notEmpty()
        ]);
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            if ($request->getParam('id')) {
                return $response->withRedirect($this->router->pathFor('Coupons.GetSingle', ['id' => $request->getParam('id')]));
            } else {
                return $response->withRedirect($this->router->pathFor('Coupons.GetIndex'));
            }
        }
        $coupon = DB::queryFirstRow('SELECT id FROM discount_codes WHERE id=%i', $request->getParam('id'));
        if (!$coupon) {
            $this->container->flash->addMessage('warning', 'we konden geen kortingscode vinden met deze id ' . $request->getParam('id'));
            if ($request->getParam('id')) {
                return $response->withRedirect($this->router->pathFor('Coupons.GetSingle', ['id' => $request->getParam('id')]));
            } else {
                return $response->withRedirect($this->router->pathFor('Coupons.GetIndex'));
            }
        }
        $discountAmount = $request->getParam('discount');
        $discountPerc = 0;
        if (preg_match('/%/', $discountAmount)) {
            $discountPerc = str_replace('%', '', $discountAmount);
            $discountAmount = 0;
        }
        $check = DB::update('discount_codes', [
            'user_id' => Auth::user_id(), 'shop_id' => $request->getParam('shop_id'),
            'code' => $request->getParam('code'), 'text' => $request->getParam('text', ''), 'valid_from' => $request->getParam('valid_from'),
            'valid_until' => $request->getParam('valid_until'), 'once' => $request->getParam('once', 0), 'min_amount' => $request->getParam('min_amount'),
            'max_amount' => $request->getParam('max_amount'), 'discount_amount' => $discountAmount, 'discount_perc' => $discountPerc,
            'used' => $request->getParam('used', 0), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ], 'id=%i', $request->getParam('id'));
        if ($check) {
            $this->container->flash->addMessage('success', 'De kortingscode is bijgewerkt');
            UserActivity::Record('Update', $request->getParam('id'), 'Coupons');
            return $response->withRedirect($this->router->pathFor('Coupons.GetSingle', ['id' => $request->getParam('id')]));
        } else {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Coupons.GetSingle', ['id' => $request->getParam('id')]));
        }
    }

    /**************************************************************************************************************************************************
     *************************************************************( Coupons Get Add )*************************************************************
     **************************************************************************************************************************************************/
    public function CouponsAddGet($request, $response, $args)
    {
        LangToDefault();
        $shops = General::getShops();
        $code = $this->generateDiscountCode();
        return $this->view->render($response, 'coupons/coupon_add.tpl', ['shops' => $shops, 'code' => $code,
        'active_menu' => 'diverse','page_title' => 'Nieuwe kortingscode']);
    }

    private function generateDiscountCode()
    {
        $codeLength = 10;
        $code = '';
        $charset = 'abcdefgijklmnopqrstuvwxyz1234567890';
        while (1 == 1) {
            for ($x = 1; $x <= $codeLength; $x++) {
                $rand = rand() % strlen($charset);
                $temp = substr($charset, $rand, 1);
                $code .= $temp;
            }
            $codeChecking = DB::queryFirstRow('SELECT code FROM discount_codes WHERE code=%s', $code);
            if (!$codeChecking) {
                break;
            }
        }
        return $code;
    }

    /**************************************************************************************************************************************************
     **************************************************************( Coupons Post New )***********************************************************
     **************************************************************************************************************************************************/
    public function CouponsAddPost($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'code' => v::notEmpty(),
            'shop_id' => v::notEmpty(),
            'min_amount' => v::notEmpty(),
            'max_amount' => v::notEmpty(),
            'discount' => v::notEmpty(),
            'valid_from' => v::notEmpty(),
            'valid_until' => v::notEmpty()
        ]);

        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Coupons.GetAdd'));
        }
        $codeChecking = DB::queryFirstRow('SELECT code FROM discount_codes WHERE code=%s', $request->getParam('code'));
        if ($codeChecking) {
            $this->container->flash->addMessage('error', 'De kortingscode bestaat al');
            return $response->withRedirect($this->router->pathFor('Coupons.GetAdd'));
        }
        $discountAmount = $request->getParam('discount');
        $discountPerc = 0;
        if (preg_match('/%/', $discountAmount)) {
            $discountPerc = str_replace('%', '', $discountAmount);
            $discountAmount = 0;
        }

        $check = DB::insert('discount_codes', [
            'user_id' => Auth::user_id(), 'shop_id' => $request->getParam('shop_id'),
            'code' => $request->getParam('code'), 'text' => $request->getParam('text', ''), 'valid_from' => $request->getParam('valid_from'),
            'valid_until' => $request->getParam('valid_until'), 'once' => $request->getParam('once', 0), 'min_amount' => $request->getParam('min_amount'),
            'max_amount' => $request->getParam('max_amount'), 'discount_amount' => $discountAmount, 'discount_perc' => $discountPerc,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        if ($check) {
            $id = DB::insertId();
            $this->container->flash->addMessage('success', 'Kortingscode is toegevoegd');
            UserActivity::Record('Add', $id, 'Coupons');
            return $response->withRedirect($this->router->pathFor('Coupons.GetSingle', ['id' => $id]));
        } else {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Coupons.GetAdd'));
        }
    }

    /**************************************************************************************************************************************************
     *******************************************************( PDF Generator for order Get Add )********************************************************
     **************************************************************************************************************************************************/
    public function pdfGeneratorOrder($request, $response, $args)
    {
        $validation = $this->validator->validateGet($args, [
            'id' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            throw new NotFoundException($request, $response);
        }
        $order = Order::GetSingle($args['id']);
        if (!$order['id']) {
            throw new NotFoundException($request, $response);
        }
        $orderNew = new Order();
        $pdf = $orderNew->pdfGeneratorOrder($order, $order['id']);
        if ($pdf) {
            dd($pdf->pdfGeneratorOrderLink($order['id']));
        } else {
            throw new NotFoundException($request, $response);
        }
    }
}
