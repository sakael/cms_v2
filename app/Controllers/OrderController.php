<?php

namespace App\Controllers;

use Slim\View\Twig as View;
use App\Classes\AttributeGroup;
use App\Classes\Attribute;
use App\Classes\UserActivity;
use App\Classes\Product;
use App\Classes\Brand;
use App\Classes\Type;
use App\Classes\Order;
use App\Classes\Note;
use App\Classes\General;
use DB;
use Respect\Validation\Validator as v;
use Carbon\Carbon as Carbon;
use App\Auth\Auth;
use Slim\Exception\NotFoundException;

class OrderController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////                        Order Single GET & Post Part                           ////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
    * Order Single Get function
    *
    * @param [type] $request
    * @param [type] $response
    * @param [type] $args
    * @return void
    */
    public function getSingle($request, $response, $args)
    {
        $validation = $this->validator->validateGet($args, [
            'id' => v::notEmpty(),
        ]);
        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('OrdersIndex'));
        }

        $order = Order::GetSingle($args['id']);
        if (!$order['id']) {
            $id = $args['id'];
            $this->container->flash->addMessage('error', 'Een bestelling met dit (' . $id . ') nummer is niet gevonden');
            throw new NotFoundException($request, $response);
        }

        $products = DB::query('select id,sku from product where active=1');
        $status = General::getStatus();
        Order::SetColorsData();
        $colors = Order::$colors;
        Order::SetSizesData();
        $sizes = Order::$sizes;
        $users = Auth::all();
        $history = UserActivity::All('Orders', $args['id']);
        $allOrderChanges = UserActivity::AllOrderChanges($args['id']);
        //Checking route if it is popup or single order page
        $route = $request->getAttribute('route');
        $name = $route->getName();
        if ($name == 'OrdersGetSinglePopup') {
            $template = 'orders/order_popup.tpl';
            $popup = false;
        } else {
            $template = 'orders/single/index.tpl';
            $popup = true;
        }
  
        return $this->view->render($response, $template, [
            'order' => $order, 'active_menu' => 'orders', 'products' => $products,
            'users' => $users, 'activities' => $history, 'orderStatus' => $status, 'colors' => $colors,
            'allOrderChanges' => $allOrderChanges,
            'sizes' => $sizes, 'popup' => $popup, 'page_title' => $order['id']
        ]);
    }
    /**
    * getSingleEdit function
    *
    * @param [type] $request
    * @param [type] $response
    * @param [type] $args
    * @return void
    */
    public function getSingleEdit($request, $response, $args)
    {
        $validation = $this->validator->validateGet($args, [
             'id' => v::notEmpty(),
         ]);
        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('OrdersIndex'));
        }
 
        $order = Order::GetSingle($args['id']);
        if (!$order['id']) {
            $id = $args['id'];
            $this->container->flash->addMessage('error', 'Een bestelling met dit (' . $id . ') nummer is niet gevonden');
            throw new NotFoundException($request, $response);
        }
 
        $products = DB::query('select id,sku from product where active=1');
        $status = General::getStatus();
        Order::SetColorsData();
        $colors = Order::$colors;
        Order::SetSizesData();
        $sizes = Order::$sizes;
        $users = Auth::all();
        $history = UserActivity::All('Orders', $args['id']);
        $allOrderChanges = UserActivity::AllOrderChanges($args['id']);
        //Checking route if it is popup or single order page
        $route = $request->getAttribute('route');
        $name = $route->getName();
        if ($name == 'OrdersGetSinglePopup') {
            $template = 'orders/order_popup.tpl';
            $popup = false;
        } else {
            $template = 'orders/single/index.tpl';
            $popup = true;
        }
   
        return $this->view->render($response, 'orders/single/edit.tpl', [
             'order' => $order, 'active_menu' => 'orders', 'products' => $products,
             'users' => $users, 'activities' => $history, 'orderStatus' => $status, 'colors' => $colors,
             'allOrderChanges' => $allOrderChanges,
             'sizes' => $sizes, 'popup' => $popup, 'page_title' => $order['id']
         ]);
    }
 
    /**************************************************************************************************************************************************
     *****************************************************************(Order Single Post)**************************************************************
     **************************************************************************************************************************************************/
    public function postUpdateSingle($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty(),
            'customer_email' => v::notEmpty()->email(),
            'payment_firstname' => v::notEmpty(),
            'payment_lastname' => v::notEmpty(),
            'payment_gender' => v::notEmpty(),
            'payment_street' => v::notEmpty(),
            'payment_houseNumber' => v::notEmpty(),
            'payment_zipcode' => v::notEmpty(),
            'payment_city' => v::notEmpty(),
            'payment_countryCode' => v::notEmpty()
        ]);
        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('OrdersGetSingleEdit', ['id' => $request->getParam('id')]));
        }
        $order_details = [];
        //$order_details['orderNumber'] = '4268604240';
        //$order_details['orderDate'] = $order->created_at;
        $order_details['customerPhone'] = $request->getParam('customer_phone');
        ;
        $order_details['customerEmail'] = $request->getParam('customer_email');
        ;
        $order_details['address'] = [];
        $order_details['address']['payment'] = [];
        $order_details['address']['payment']['company'] = '';
        $order_details['address']['payment']['gender'] = $request->getParam('payment_gender');
        $order_details['address']['payment']['firstname'] = $request->getParam('payment_firstname');
        $order_details['address']['payment']['lastname'] = $request->getParam('payment_lastname');
        $order_details['address']['payment']['street'] = $request->getParam('payment_street');
        $order_details['address']['payment']['houseNumber'] = $request->getParam('payment_houseNumber');
        $order_details['address']['payment']['houseNumberSupplement'] = $request->getParam('payment_houseNumberSupplement');
        $order_details['address']['payment']['zipcode'] = $request->getParam('payment_zipcode');
        $order_details['address']['payment']['city'] = $request->getParam('payment_city');
        $order_details['address']['payment']['countryCode'] = $request->getParam('payment_countryCode');
        $order_details['address']['shipping'] = [];
        $order_details['address']['shipping']['company'] = '';
        $order_details['address']['shipping']['gender'] = $request->getParam('shipping_gender');
        $order_details['address']['shipping']['firstname'] = $request->getParam('shipping_firstname');
        $order_details['address']['shipping']['lastname'] = $request->getParam('shipping_lastname');
        $order_details['address']['shipping']['street'] = $request->getParam('shipping_street');
        $order_details['address']['shipping']['houseNumber'] = $request->getParam('shipping_houseNumber');
        $order_details['address']['shipping']['houseNumberSupplement'] = $request->getParam('shipping_houseNumberSupplement');
        $order_details['address']['shipping']['zipcode'] = $request->getParam('shipping_zipcode');
        $order_details['address']['shipping']['city'] = $request->getParam('shipping_city');
        $order_details['address']['shipping']['countryCode'] = $request->getParam('shipping_countryCode');
        $order = new Order;
        $order->order_details = $order_details;

        if ($order->Update($request->getParam('id'))) {
            $this->container->flash->addMessage('success', 'Order is bijgewerkt');
            UserActivity::Record('Update', $request->getParam('id'), 'Orders');
            return $response->withRedirect($this->router->pathFor('OrdersGetSingle', ['id' => $request->getParam('id')]));
        } else {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('OrdersGetSingle', ['id' => $request->getParam('id')]));
        }
    }

    /**************************************************************************************************************************************************
     *************************************************************(Order Items Post Update)************************************************************
     **************************************************************************************************************************************************/
    public function orderItemsUpdate($request, $response, $args)
    {
        //Edit order
        $total_rows_price = 0;
        $check = false;
        if ($request->getParam('product_ids') && $request->getParam('order_id')) {
            $order_id = $request->getParam('order_id');
            $product_ids = $request->getParam('product_ids');
            $counts = $request->getParam('count');
            $combochecks = $request->getParam('combo_check');
            $product_colors = $request->getParam('product_color');
            $product_sizes = $request->getParam('product_size');
            $product_prices = $request->getParam('product_price');
            $order_item_id = $request->getParam('order_item_id');
            $combo_ids = $request->getParam('combo_id');
            $shop_id = $request->getParam('shop_id');
            $orderitems = [];
            $checking = false;
            foreach ($product_ids as $key => $product_id) {
                $product = Product::getProduct($product_id);

                if (!$product || !$product_prices[$key] || $product_prices[$key] == '') {
                    $checking = true;
                    break;
                }
                if ($product['id']) {
                    $orderitems[$key] = [];
                    $orderitems[$key]['product_id'] = $product_id;
                    $orderitems[$key]['order_item_id'] = $order_item_id[$key];
                    $orderitems[$key]['color_id'] = $product_colors[$key];
                    $orderitems[$key]['size_id'] = $product_sizes[$key];
                    $orderitems[$key]['product_name'] = $product['contents']['title'];
                    $orderitems[$key]['order_id'] = $order_id;
                    $orderitems[$key]['shop_id'] = $shop_id;
                    $orderitems[$key]['count'] = $counts[$key];
                    $orderitems[$key]['price'] = round($product_prices[$key], 2);
                    $orderitems[$key]['totalprice'] = round((($product_prices[$key]) * $counts[$key]), 2);
                    $orderitems[$key]['combo'] = $combochecks[$key];
                    $orderitems[$key]['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');
                    $orderitems[$key]['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
                    $total_rows_price += $orderitems[$key]['price'] * $orderitems[$key]['count'];
                }
            }

            if ($checking == false) {
                // DB::delete(Order::$table_order_items, "order_id=%i", $order_id);
                foreach ($orderitems as $item) {
                    if ($item['order_item_id'] == 0) {
                        DB::insert(Order::$table_order_items, [
                            'product_id' => $item['product_id'], 'product_name' => $item['product_name'],
                            'order_id' => $item['order_id'], 'shop_id' => $item['shop_id'], 'count' => $item['count'], 'price' => $item['price'],
                            'totalprice' => $item['totalprice'], 'combo' => $item['combo'], 'created_at' => $item['created_at']
                        ]);
                        $id = DB::insertId();
                        if ($id) {
                            if ($item['size_id']) {
                                $attribute = DB::queryFirstRow('select id,attribute_group_id from ' . Order::$table_attribute . ' where id=%i', $item['size_id']);
                                if ($attribute) {
                                    DB::insert(Order::$table_item_attribute, ['order_item_id' => $id, 'attribute_id' => $item['size_id']]);
                                }
                            }
                            if ($item['color_id']) {
                                $attribute = DB::queryFirstRow('select id,attribute_group_id from ' . Order::$table_attribute . ' where id=%i', $item['color_id']);
                                if ($attribute) {
                                    DB::insert(Order::$table_item_attribute, ['order_item_id' => $id, 'attribute_id' => $item['color_id']]);
                                }
                            }
                        }
                        UserActivity::Record('Insert New Order Row', $id, 'OrderItems');
                    } elseif ($item['order_item_id'] != 0 or $item['order_item_id'] != '') {
                        $orderItem = DB::queryFirstRow('select id from ' . Order::$table_order_items . ' where id=%i', $item['order_item_id']);
                        if ($orderItem['id']) {
                            DB::update(Order::$table_order_items, [['count' => $item['count'], 'totalprice' => $item['totalprice'], 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')]], 'id=%i', $orderItem['id']);
                            UserActivity::Record('Updated Order Row', $orderItem['id'], 'OrderItems');
                        }
                    }
                }
            } else {
                print_r($product);
                echo $product_id . '-' . $key . '-' . $product_prices[$key];
                die();
                return $response->withJson(['status' => 'false', 'msg' => 'De bestellingsitems zijn niet bijgewerkt, missende data']);
            }

            //$shippingCost=DB::queryOneField('shipping_cost', "SELECT shipping_cost FROM ".Order::$table." WHERE id=%i", $order_id);

            $prices = [
                'gross_price' => round($total_rows_price, 2),
                'net_price' => round($total_rows_price, 2) - round($total_rows_price - (($total_rows_price / 121) * 100), 2),
                'vat' => round($total_rows_price - (($total_rows_price / 121) * 100), 2),
            ];

            UserActivity::Record('Update', $order_id, 'Orders');
            $check = DB::update(Order::$table, [['gross_price' => $prices['gross_price'], 'net_price' => $prices['net_price'], 'vat' => $prices['vat'], 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')]], 'id=%i', $order_id);
        }
        if ($check) {
            return $response->withJson(['status' => 'true', 'msg' => 'De bestellingsitems zijn bijgewerkt']);
        } else {
            return $response->withJson(['status' => 'false', 'msg' => 'De bestellingsitems zijn niet bijgewerkt, missende data']);
        }
        return $response->withJson(['status' => 'false', 'msg' => 'De bestellingsitems zijn niet bijgewerkt']);
    }

    /**************************************************************************************************************************************************
     *************************************************************(Order Items Post Update)************************************************************
     **************************************************************************************************************************************************/
    public function orderItemDelete($request, $response, $args)
    {
        if ($request->getParam('id')) {
            $check = DB::delete(Order::$table_order_items, 'id=%i', $request->getParam('id'));
            if ($check) {
                UserActivity::Record('Order Item Deleted', $request->getParam('id'), 'OrderItem');
                return $response->withJson(['status' => 'true', 'msg' => 'De orderartikel is verwijderd']);
            }
        }
        return $response->withJson(['status' => 'false', 'msg' => 'De orderartikel is niet verwijderd']);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////                        Orders Index Page                           ///////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
    * Orders Main Index Get function
    *
    * @param [type] $request
    * @param [type] $response
    * @param [type] $args
    * @return view
    */
    public function ordersGetIndex($request, $response, $args)
    {
        if ($request->getParam('orderTab')) {
            $orderTab = $request->getParam('orderTab');
        } else {
            $orderTab = '';
        }
        return $this->view->render($response, 'orders/main_index.tpl', ['active_menu' => 'orders',
            'page_title' => 'Main Orders', 'orderTab' => $orderTab]);
    }

    /**
    * Orders other tabs Index Get function
    *
    * @param [type] $request
    * @param [type] $response
    * @param [type] $args
    * @return view
    */
    public function ordersGetOtherIndex($request, $response, $args)
    {
        if ($request->getParam('orderTab')) {
            $orderTab = $request->getParam('orderTab');
        } else {
            $orderTab = '';
        }
        return $this->view->render($response, 'orders/other_tabs_index.tpl', ['active_menu' => 'orders',
            'page_title' => 'Resterende Orders Tabs', 'orderTab' => $orderTab]);
    }
    public function ordersGetBol($request, $response, $args)
    {
        die('w00t');
    }

    /**************************************************************************************************************************************************
     **********************************************************(Orders Get New Orders Data Get)********************************************************
     **************************************************************************************************************************************************/
    public function getAllNew($request, $response, $args)
    {
        $paid = $request->getParam('showPaidOrders', 0);
        if ($paid == 1) {
            $paid = " AND ispaid = '0'";
        } else {
            $paid = " AND ispaid != '0'";
        }

        $query = 'SELECT ' . Order::$table . '.id,' . Order::$table . '.shop_id,' . Order::$table .
            '.status_id,JSON_UNQUOTE(' . Order::$table . '.payment) as payment,JSON_UNQUOTE(' . Order::$table . '.order_details) as order_details,' . Order::$table . '.gross_price,' . Order::$table . '.shipping_cost,' . Order::$table . '.created_at,' . Order::$table_status . '.title as status_title, ' . Order::$table_shops . '.domain as shop_name  from ' . Order::$table . '
        LEFT JOIN ' . Order::$table_status . ' on ' . Order::$table_status . '.id=' . Order::$table . '.status_id
        LEFT JOIN ' . Order::$table_shops . ' on ' . Order::$table_shops . '.id=' . Order::$table . '.shop_id
        where ' . Order::$table . '.status_id=%i ' . $paid;

        $ordersTemp = DB::query($query, 1);

        $orderIds = [];
        $orders = [];
        foreach ($ordersTemp as $key => $order) {
            $orderIds[] = $order['id'];
            $orders[$order['id']] = $order;
        }
        $order_items = DB::query('SELECT * from ' . Order::$table_order_items . '
        where ' . Order::$table_order_items . ".order_id IN ('" . implode("','", $orderIds) . "')
                                                ");
        foreach ($order_items as $item) {
            $product = Product::getProduct($item['product_id']);
            $item['product'] = $product;
            $itemAttributes = DB::query(
                'SELECT ' . Order::$table_item_attribute . '.* , ' . Order::$table_attribute . '.attribute_group_id, ' . Order::$table_attribute . ".name->>'$." . language . "' as title from " . Order::$table_item_attribute . '
               left join ' . Order::$table_attribute . ' on ' . Order::$table_item_attribute . '.attribute_id = ' . Order::$table_attribute . '.id
               where ' . Order::$table_item_attribute . '.order_item_id=%i',
                $item['id']
            );

            foreach ($itemAttributes as $itemAttribute) {
                $item['attributes'][] = $itemAttribute;
            }

            $orders[$item['order_id']]['order_items'][] = $item;
        }
        $index = 0;
        $ordersTemp = $orders;
        $orders = [];
        Order::SetColorsData();
        $colors = Order::$colors;

        foreach ($ordersTemp as $key => $order) {
            ////Adding Colors to order list
            $orders[$index] = $order;
            $index++;
        }
        $returndata = [
            'draw' => null,
            'cached' => null,
            'recordsTotal' => count($orders),
            'recordsFiltered' => count($orders),
            'data' => $orders
        ];
        return json_encode($returndata, JSON_INVALID_UTF8_IGNORE);
    }

    /**************************************************************************************************************************************************
     ********************************************************(Orders Get Claimed Orders Data Get)******************************************************
     **************************************************************************************************************************************************/
    public function getAllClaimed($request, $response, $args)
    {
        $index = 0;
        $Orders = [];
        $byOthers = $request->getParam('byOthers', 0);

        //check if should show all order or just user orders
        if ($byOthers) {
            $userId = null;
        } else {
            $userId = Auth::user_id();
        }

        $OrdersTemp = Order::All(10, $userId);

        Order::SetColorsData();
        $colors = Order::$colors;

        foreach ($OrdersTemp as $key => $Order) {
            ////Adding products to order list
            $Orders[$index] = $Order;
            $Orders[$index]['products'] = '';
            $Orders[$index]['action'] = '';
            $index++;
        }
        $returndata = [
            'draw' => null,
            'cached' => null,
            'recordsTotal' => count($Orders),
            'recordsFiltered' => count($Orders),
            'data' => $Orders
        ];
        return json_encode($returndata);
    }

    /**************************************************************************************************************************************************
     ****************************************************(Orders Get Ready For Shipping Orders Data Get)***********************************************
     **************************************************************************************************************************************************/
    public function getAllReadyForShipping($request, $response, $args)
    {
        $byOthers = $request->getParam('byOthers', 0);
        if ($byOthers) {
            $userId = '';
        } else {
            $userId = ' AND ' . Order::$table . '.user_id=%i';
        }
        $paid = $request->getParam('showPaidOrders', 0);
        if ($paid == 1) {
            $paid = " AND ispaid = '0'";
        } else {
            $paid = " AND ispaid != '0'";
        }

        $query = 'SELECT ' . Order::$table . '.id,' . Order::$table . '.shop_id,' . Order::$table .
            '.status_id,JSON_UNQUOTE(' . Order::$table . '.payment) as payment,JSON_UNQUOTE(' . Order::$table . '.order_details) as order_details,' . Order::$table . '.created_at,' . Order::$table_status . '.title as status_title, ' . Order::$table_shops . '.domain as shop_name  from ' . Order::$table . '
        LEFT JOIN ' . Order::$table_status . ' on ' . Order::$table_status . '.id=' . Order::$table . '.status_id
        LEFT JOIN ' . Order::$table_shops . ' on ' . Order::$table_shops . '.id=' . Order::$table . '.shop_id
        where ' . Order::$table . '.status_id=%i ' . $userId;

        $ordersTemp = DB::query($query, 15, Auth::user_id());

        $orderIds = [];
        $orders = [];
        foreach ($ordersTemp as $key => $order) {
            $orderIds[] = $order['id'];
            $orders[$order['id']] = $order;
        }

        $order_items = DB::query('SELECT * from ' . Order::$table_order_items . '
        where ' . Order::$table_order_items . ".order_id IN ('" . implode("','", $orderIds) . "')");
        foreach ($order_items as $item) {
            $product = Product::getProduct($item['product_id']);
            $item['product'] = $product;
            $itemAttributes = DB::query(
                'SELECT ' . Order::$table_item_attribute . '.* , ' . Order::$table_attribute . '.attribute_group_id, ' . Order::$table_attribute . ".name->>'$." . language . "' as title from " . Order::$table_item_attribute . '
               left join ' . Order::$table_attribute . ' on ' . Order::$table_item_attribute . '.attribute_id = ' . Order::$table_attribute . '.id
               where ' . Order::$table_item_attribute . '.order_item_id=%i',
                $item['id']
            );

            foreach ($itemAttributes as $itemAttribute) {
                $item['attributes'][] = $itemAttribute;
            }

            $orders[$item['order_id']]['order_items'][] = $item;
        }
        $index = 0;
        $ordersTemp = $orders;
        $orders = [];
        Order::SetColorsData();
        $colors = Order::$colors;
        foreach ($ordersTemp as $key => $order) {
            $orders[$index] = $order;
            $index++;
        }
        $returndata = [
            'draw' => null,
            'cached' => null,
            'recordsTotal' => count($orders),
            'recordsFiltered' => count($orders),
            'data' => $orders
        ];
        return json_encode($returndata);
    }

    /**************************************************************************************************************************************************
     ********************************************************(Orders Get Return Orders Data Get)*******************************************************
     **************************************************************************************************************************************************/
    public function getAllReturn($request, $response, $args)
    {
        $paid = $request->getParam('showPaidOrders', 0);
        if ($paid == 1) {
            $paid = " AND ispaid = '0'";
        } else {
            $paid = " AND ispaid != '0'";
        }

        $query = 'SELECT ' . Order::$table . '.id,' . Order::$table . '.shop_id,' . Order::$table .
            '.status_id,JSON_UNQUOTE(' . Order::$table . '.payment) as payment,JSON_UNQUOTE(' . Order::$table . '.order_details) as order_details,' . Order::$table . '.created_at,' . Order::$table_status . '.title as status_title, ' . Order::$table_shops . '.domain as shop_name  from ' . Order::$table . '
        LEFT JOIN ' . Order::$table_status . ' on ' . Order::$table_status . '.id=' . Order::$table . '.status_id
        LEFT JOIN ' . Order::$table_shops . ' on ' . Order::$table_shops . '.id=' . Order::$table . '.shop_id
        where ' . Order::$table . '.status_id=%i ' . $paid;

        $ordersTemp = DB::query($query, 5);

        $orderIds = [];
        $orders = [];
        foreach ($ordersTemp as $key => $order) {
            $orderIds[] = $order['id'];
            $orders[$order['id']] = $order;
        }
        $order_items = DB::query('SELECT * from ' . Order::$table_order_items . '
        where ' . Order::$table_order_items . ".order_id IN ('" . implode("','", $orderIds) . "')
                                                ");
        foreach ($order_items as $item) {
            $product = Product::getProduct($item['product_id']);
            $item['product'] = $product;
            $itemAttributes = DB::query(
                'SELECT ' . Order::$table_item_attribute . '.* , ' . Order::$table_attribute . '.attribute_group_id, ' . Order::$table_attribute . ".name->>'$." . language . "' as title from " . Order::$table_item_attribute . '
               left join ' . Order::$table_attribute . ' on ' . Order::$table_item_attribute . '.attribute_id = ' . Order::$table_attribute . '.id
               where ' . Order::$table_item_attribute . '.order_item_id=%i',
                $item['id']
            );

            foreach ($itemAttributes as $itemAttribute) {
                $item['attributes'][] = $itemAttribute;
            }

            $orders[$item['order_id']]['order_items'][] = $item;
        }
        $index = 0;
        $ordersTemp = $orders;
        $orders = [];
        Order::SetColorsData();
        $colors = Order::$colors;

        foreach ($ordersTemp as $key => $order) {
            ////Adding Colors to order list
            $orders[$index] = $order;
            $index++;
        }
        $returndata = [
            'draw' => null,
            'cached' => null,
            'recordsTotal' => count($orders),
            'recordsFiltered' => count($orders),
            'data' => $orders
        ];
        return json_encode($returndata);
    }

    /**************************************************************************************************************************************************
     ****************************************************(Orders Get Wait Supplier Orders Data Get)****************************************************
     **************************************************************************************************************************************************/
    public function getAllWaitSupplier($request, $response, $args)
    {
        $paid = $request->getParam('showPaidOrders', 0);
        if ($paid == 1) {
            $paid = " AND ispaid = '0'";
        } else {
            $paid = " AND ispaid != '0'";
        }

        $query = 'SELECT ' . Order::$table . '.id,' . Order::$table . '.shop_id,' . Order::$table .
            '.status_id,JSON_UNQUOTE(' . Order::$table . '.payment) as payment,JSON_UNQUOTE(' . Order::$table . '.order_details) as order_details,' . Order::$table . '.created_at,' . Order::$table_status . '.title as status_title, ' . Order::$table_shops . '.domain as shop_name  from ' . Order::$table . '
        LEFT JOIN ' . Order::$table_status . ' on ' . Order::$table_status . '.id=' . Order::$table . '.status_id
        LEFT JOIN ' . Order::$table_shops . ' on ' . Order::$table_shops . '.id=' . Order::$table . '.shop_id
        where ' . Order::$table . '.status_id=%i ' . $paid;

        $ordersTemp = DB::query($query, 9);

        $orderIds = [];
        $orders = [];
        foreach ($ordersTemp as $key => $order) {
            $orderIds[] = $order['id'];
            $orders[$order['id']] = $order;
        }
        $order_items = DB::query('SELECT * from ' . Order::$table_order_items . '
        where ' . Order::$table_order_items . ".order_id IN ('" . implode("','", $orderIds) . "')
                                                ");
        foreach ($order_items as $item) {
            $product = Product::getProduct($item['product_id']);
            $item['product'] = $product;
            $itemAttributes = DB::query(
                'SELECT ' . Order::$table_item_attribute . '.* , ' . Order::$table_attribute . '.attribute_group_id, ' . Order::$table_attribute . ".name->>'$." . language . "' as title from " . Order::$table_item_attribute . '
               left join ' . Order::$table_attribute . ' on ' . Order::$table_item_attribute . '.attribute_id = ' . Order::$table_attribute . '.id
               where ' . Order::$table_item_attribute . '.order_item_id=%i',
                $item['id']
            );

            foreach ($itemAttributes as $itemAttribute) {
                $item['attributes'][] = $itemAttribute;
            }

            $orders[$item['order_id']]['order_items'][] = $item;
        }
        $index = 0;
        $ordersTemp = $orders;
        $orders = [];
        Order::SetColorsData();
        $colors = Order::$colors;

        foreach ($ordersTemp as $key => $order) {
            ////Adding Colors to order list
            $orders[$index] = $order;
            $index++;
        }
        $returndata = [
            'draw' => null,
            'cached' => null,
            'recordsTotal' => count($orders),
            'recordsFiltered' => count($orders),
            'data' => $orders
        ];
        return json_encode($returndata);
    }

    /**************************************************************************************************************************************************
     ************************************************(Orders Get Wait External Supplier Orders Data Get)***********************************************
     **************************************************************************************************************************************************/
    public function getAllWaitExternalSupplier($request, $response, $args)
    {
        $paid = $request->getParam('showPaidOrders', 0);
        if ($paid == 1) {
            $paid = " AND ispaid = '0'";
        } else {
            $paid = " AND ispaid != '0'";
        }

        $query = 'SELECT ' . Order::$table . '.id,' . Order::$table . '.shop_id,' . Order::$table .
            '.status_id,JSON_UNQUOTE(' . Order::$table . '.payment) as payment,JSON_UNQUOTE(' . Order::$table . '.order_details) as order_details,' . Order::$table . '.created_at,' . Order::$table_status . '.title as status_title, ' . Order::$table_shops . '.domain as shop_name  from ' . Order::$table . '
        LEFT JOIN ' . Order::$table_status . ' on ' . Order::$table_status . '.id=' . Order::$table . '.status_id
        LEFT JOIN ' . Order::$table_shops . ' on ' . Order::$table_shops . '.id=' . Order::$table . '.shop_id
        where ' . Order::$table . '.status_id=%i ' . $paid;

        $ordersTemp = DB::query($query, 19);

        $orderIds = [];
        $orders = [];
        foreach ($ordersTemp as $key => $order) {
            $orderIds[] = $order['id'];
            $orders[$order['id']] = $order;
        }
        $order_items = DB::query('SELECT * from ' . Order::$table_order_items . '
        where ' . Order::$table_order_items . ".order_id IN ('" . implode("','", $orderIds) . "')
                                                ");
        foreach ($order_items as $item) {
            $product = Product::getProduct($item['product_id']);
            $item['product'] = $product;
            $orders[$item['order_id']]['order_items'][] = $item;
        }
        $index = 0;
        $ordersTemp = $orders;
        $orders = [];
        Order::SetColorsData();
        $colors = Order::$colors;

        foreach ($ordersTemp as $key => $order) {
            ////Adding Colors to order list
            $orders[$index] = $order;
            $orders[$index]['colors'] = $colors;
            $index++;
        }
        $returndata = [
            'draw' => null,
            'cached' => null,
            'recordsTotal' => count($orders),
            'recordsFiltered' => count($orders),
            'data' => $orders
        ];
        return json_encode($returndata);
    }

    /**************************************************************************************************************************************************
     *****************************************************(Orders Get Wait Customer Orders Data Get)***************************************************
     **************************************************************************************************************************************************/
    public function getAllWaitCustomer($request, $response, $args)
    {
        $paid = $request->getParam('showPaidOrders', 0);
        if ($paid == 1) {
            $paid = " AND ispaid = '0'";
        } else {
            $paid = " AND ispaid != '0'";
        }

        $query = 'SELECT ' . Order::$table . '.id,' . Order::$table . '.shop_id,' . Order::$table .
            '.status_id,JSON_UNQUOTE(' . Order::$table . '.payment) as payment,JSON_UNQUOTE(' . Order::$table . '.order_details) as order_details,' . Order::$table . '.created_at,' . Order::$table_status . '.title as status_title, ' . Order::$table_shops . '.domain as shop_name  from ' . Order::$table . '
      LEFT JOIN ' . Order::$table_status . ' on ' . Order::$table_status . '.id=' . Order::$table . '.status_id
      LEFT JOIN ' . Order::$table_shops . ' on ' . Order::$table_shops . '.id=' . Order::$table . '.shop_id
      where ' . Order::$table . '.status_id=%i ' . $paid;

        $ordersTemp = DB::query($query, 11);

        $orderIds = [];
        $orders = [];
        foreach ($ordersTemp as $key => $order) {
            $orderIds[] = $order['id'];
            $orders[$order['id']] = $order;
        }
        $order_items = DB::query('SELECT * from ' . Order::$table_order_items . '
      where ' . Order::$table_order_items . ".order_id IN ('" . implode("','", $orderIds) . "')
                                              ");
        foreach ($order_items as $item) {
            $product = Product::getProduct($item['product_id']);
            $item['product'] = $product;
            $orders[$item['order_id']]['order_items'][] = $item;
        }
        $index = 0;
        $ordersTemp = $orders;
        $orders = [];
        Order::SetColorsData();
        $colors = Order::$colors;

        foreach ($ordersTemp as $key => $order) {
            ////Adding Colors to order list
            $orders[$index] = $order;
            $orders[$index]['colors'] = $colors;
            $index++;
        }
        $returndata = [
            'draw' => null,
            'cached' => null,
            'recordsTotal' => count($orders),
            'recordsFiltered' => count($orders),
            'data' => $orders
        ];
        return json_encode($returndata);
    }

    /**************************************************************************************************************************************************
     *********************************************************(Orders Get Credit Orders Data Get)******************************************************
     **************************************************************************************************************************************************/
    public function getAllCredit($request, $response, $args)
    {
        $paid = $request->getParam('showPaidOrders', 0);
        if ($paid == 1) {
            $paid = " AND ispaid = '0'";
        } else {
            $paid = " AND ispaid != '0'";
        }

        $query = 'SELECT ' . Order::$table . '.id,' . Order::$table . '.shop_id,' . Order::$table .
            '.status_id,JSON_UNQUOTE(' . Order::$table . '.payment) as payment,JSON_UNQUOTE(' . Order::$table . '.order_details) as order_details,' . Order::$table . '.created_at,' . Order::$table_status . '.title as status_title, ' . Order::$table_shops . '.domain as shop_name  from ' . Order::$table . '
      LEFT JOIN ' . Order::$table_status . ' on ' . Order::$table_status . '.id=' . Order::$table . '.status_id
      LEFT JOIN ' . Order::$table_shops . ' on ' . Order::$table_shops . '.id=' . Order::$table . '.shop_id
      where ' . Order::$table . '.status_id=%i ' . $paid;

        $ordersTemp = DB::query($query, 16);

        $orderIds = [];
        $orders = [];
        foreach ($ordersTemp as $key => $order) {
            $orderIds[] = $order['id'];
            $orders[$order['id']] = $order;
        }
        $order_items = DB::query('SELECT * from ' . Order::$table_order_items . '
      where ' . Order::$table_order_items . ".order_id IN ('" . implode("','", $orderIds) . "')
                                              ");
        foreach ($order_items as $item) {
            $product = Product::getProduct($item['product_id']);
            $item['product'] = $product;
            $orders[$item['order_id']]['order_items'][] = $item;
        }
        $index = 0;
        $ordersTemp = $orders;
        $orders = [];
        Order::SetColorsData();
        $colors = Order::$colors;

        foreach ($ordersTemp as $key => $order) {
            ////Adding Colors to order list
            $orders[$index] = $order;
            $orders[$index]['colors'] = $colors;
            $index++;
        }
        $returndata = [
            'draw' => null,
            'cached' => null,
            'recordsTotal' => count($orders),
            'recordsFiltered' => count($orders),
            'data' => $orders
        ];
        return json_encode($returndata);
    }

    /**
     * claimSingle function, Claim order by id
     *
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return [object] json object (status,msg)
     */
    public function claimSingle($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Order is niet geclaimt !!']);
        }

        $check = Order::ChangeStatus($request->getParam('id'), 10);
        if ($check) {
            return $response->withJson(['status' => 'true', 'msg' => 'Is geclaimt  !!']);
        }
        return $response->withJson(['status' => 'false', 'msg' => 'Order is niet geclaimt !!']);
    }

    /**
     * claimSingle function, Claim order by id
     *
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return [json] json object (status,msg)
     */
    public function claimOrdersByProduct($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'sku' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Order is niet geclaimt !!']);
        }
        $sku = $request->getParam('sku');

        $product = Product::getProductBySku($sku);
        if (!$product) {
            return $response->withJson(['status' => 'false', 'msg' => 'Product is niet gevonden !!']);
        }

        $orders = DB::query('select order_id from order_item where product_id=%i', $product['id']);
        foreach ($orders as $order) {
            Order::ChangeStatus($order['order_id'], 10);
        }
        if (count($orders) > 0) {
            return $response->withJson(['status' => 'true', 'msg' => count($orders) . ' orders zijn/is geclaimt  !!']);
        }
        return $response->withJson(['status' => 'false', 'msg' => 'Geen order is geclaimt !!']);
    }
    /**************************************************************************************************************************************************
     *******************************************************( Orders Claiming by stelling )********************************************************
     **************************************************************************************************************************************************/
    public function orderClaimByStelling($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'value' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Orders zijn niet geclaimt !!']);
        }
        $value = $request->getParam('value');
        $orders = DB::query('select ' . Order::$table . ' .id from ' . Order::$table . " where `status_id` = '1'
                  and exists (select * from " . Order::$table_order_items . ' where ' . Order::$table . '.`id` = ' . Order::$table_order_items . '.`order_id`
                  and exists (select * from `product` where ' . Order::$table_order_items . '.`product_id` = `product`.`id` and `product`.`location` LIKE %s ))', $value . '%');
        foreach ($orders as $order) {
            Order::ChangeStatus($order['id'], 10);
        }
        if ($orders) {
            return $response->withJson(['status' => 'true']);
        } else {
            return $response->withJson(['status' => 'false', 'msg' => 'Nothing']);
        }
    }

    /**************************************************************************************************************************************************
     *******************************************************( Orders Claiming by Productgroup )********************************************************
     **************************************************************************************************************************************************/
    public function orderClaimByProductgroup($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'value' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Orders zijn niet geclaimt !!']);
        }
        $value = $request->getParam('value');

        $orders = DB::query('select ' . Order::$table . ' .id from ' . Order::$table . "  where `status_id` = '1'
                  and exists (select * from " . Order::$table_order_items . '  where ' . Order::$table . ' .`id` = ' . Order::$table_order_items . ' .`order_id`
                  and exists (select * from `product` where ' . Order::$table_order_items . ' .`product_id` = `product`.`id` and `product`.`location` LIKE %s ))', $value . '%');

        foreach ($orders as $order) {
            Order::ChangeStatus($order['id'], 10);
        }
        if ($orders) {
            return $response->withJson(['status' => 'true']);
        } else {
            return $response->withJson(['status' => 'false', 'msg' => 'Nothing']);
        }
    }

    /**************************************************************************************************************************************************
     ********************************************************( Orders Claiming by Buitenland)**********************************************************
     **************************************************************************************************************************************************/
    public function orderClaimByBuitenland($request, $response, $args)
    {
        $orders = DB::query('select ' . Order::$table . '.id from ' . Order::$table . " where `status_id` = '1' and `ispaid` = '1' and order_details->'$.address.shipping.countryCode' <> 'NL'");
        foreach ($orders as $order) {
            Order::ChangeStatus($order['id'], 10);
        }
        if ($orders) {
            return $response->withJson(['status' => 'true']);
        } else {
            return $response->withJson(['status' => 'false', 'msg' => 'Nothing']);
        }
    }

    /**************************************************************************************************************************************************
     **************************************************************( Orders Claiming by B2B)***********************************************************
     **************************************************************************************************************************************************/
    public function orderClaimByb2b($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'value' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Orders zijn niet geclaimt !!']);
        }
        $value = $request->getParam('value');
        $orders = DB::query('select id from ' . Order::$table . " where status_id = '1' and paylater = '1'");
        foreach ($orders as $order) {
            Order::ChangeStatus($order['id'], 10);
        }
        if ($orders) {
            return $response->withJson(['status' => 'true']);
        } else {
            return $response->withJson(['status' => 'false', 'msg' => 'Nothing']);
        }
    }

    /**************************************************************************************************************************************************
     ***************************************************************(Order Status update)**************************************************************
     **************************************************************************************************************************************************/

    public function orderStatusUpdate($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty(),
            'status_id' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'De status van de Order is niet gewijzigd !!']);
        }
        $inform = $request->getParam('inform', 0);
        $check = Order::ChangeStatus($request->getParam('id'), $request->getParam('status_id'), $inform);
        if ($check) {
            $status = General::getStatus();
            $status = $status[$request->getParam('status_id')]['title'];
            $user = Auth::user();
            $user = $user['name'] . ' ' . $user['lastname'];
            return $response->withJson(['status' => 'true', 'msg' => 'De status van de Order is gewijzigd !!', 'user' => $user, 'statusOrder' => $status , 'at' => $check ]);
        }
        return $response->withJson(['status' => 'false', 'msg' => 'De status van de Order is niet gewijzigd !!']);
    }

    /**************************************************************************************************************************************************
     **************************************************************( Order Duplicate )*****************************************************************
     **************************************************************************************************************************************************/

    public function orderDuplicate($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'De ID van de Order wordt niet gevonden !!']);
        }
        $order = DB::queryFirstRow('select shop_id, status_id, user_id,transaction_id, shipping_method_id, discount_code_id,shipping_cost, order_details, payment, invoicenr, orderId, gross_price, net_price, vat, company_ref, returnCode, paylater, ispaid, fbc_invitation, labelPrinted, tracktrace, prodreview_invitation
from ' . Order::$table . '
where id = %i', $request->getParam('id'));
        $check = false;
        if ($order) {
            $check = DB::insert(Order::$table, [
                'shop_id' => $order['shop_id'],
                'orderId' => $order['orderId'],
                'status_id' => $order['status_id'],
                'user_id' => $order['user_id'],
                'transaction_id' => $order['transaction_id'],
                'shipping_method_id' => $order['shipping_method_id'],
                'discount_code_id' => $order['discount_code_id'],
                'order_details' => $order['order_details'],
                'payment' => $order['payment'],
                'invoicenr' => $order['invoicenr'],
                'gross_price' => $order['gross_price'],
                'net_price' => $order['net_price'],
                'vat' => $order['vat'],
                'company_ref' => $order['company_ref'],
                'shipping_cost' => $order['shipping_cost'],
                'returnCode' => $order['returnCode'],
                'paylater' => $order['paylater'],
                'ispaid' => $order['ispaid'],
                'fbc_invitation' => $order['fbc_invitation'],
                'labelPrinted' => $order['labelPrinted'],
                'tracktrace' => $order['tracktrace'],
                'prodreview_invitation' => $order['prodreview_invitation']
            ]);
        }
        if ($check) {
            $newOrderId = DB::insertId();
            DB::update(Order::$table, [['status_id' => 1, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]], 'id=%i', $newOrderId);

            $note = new Note();
            $note->user_id = Auth::user_id();
            $note->order_id = $newOrderId;
            $note->note = 'Gedupliceerd van <a href="/orders/order/' . $request->getParam('id') . '">' . $request->getParam('id') . '</a>';
            $note->Create();

            $note->user_id = Auth::user_id();
            $note->order_id = $request->getParam('id');
            $note->note = 'Gedupliceerd naar <a href="/orders/order/' . $newOrderId . '">' . $newOrderId . '</a>';
            $note->Create();

            $orderItems = DB::query('select id,order_id,product_id, shop_id, product_name, count, price, totalprice from ' . Order::$table_order_items . ' where order_id=%i', $request->getParam('id'));
            foreach ($orderItems as $orderItem) {
                DB::insert(Order::$table_order_items, [
                    'product_id' => $orderItem['product_id'],
                    'shop_id' => $orderItem['shop_id'],
                    'product_name' => $orderItem['product_name'],
                    'count' => $orderItem['count'],
                    'price' => $orderItem['price'],
                    'totalprice' => $orderItem['totalprice'],
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'order_id' => $newOrderId
                ]);
                $newOrderItemId = DB::insertId();
                $orderItems = DB::query('select * from ' . Order::$table_item_attribute . ' where order_item_id=%i', $orderItem['id']);
                foreach ($orderItems as $orderItem) {
                    DB::insert(Order::$table_item_attribute, [
                        'order_item_id' => $newOrderItemId,
                        'attribute_id' => $orderItem['attribute_id']
                    ]);
                }
            }
            $url = '/orders/order/' . $newOrderId;
            $this->container->flash->addMessage('success', 'De order wordt gedupliceerd !!');
            return $response->withJson(['status' => 'true', 'msg' => 'De order wordt gedupliceerd !! ', 'url' => $url]);
        }
        return $response->withJson(['status' => 'false', 'msg' => 'De status van de Order is niet gewijzigd !!' . $request->getParam('id')]);
    }

    /**************************************************************************************************************************************************
     ******************************************************************( Order Add New )***************************************************************
     **************************************************************************************************************************************************/
    public function addNew($request, $response, $args)
    {
        $shops = General::getShops();
        return $this->view->render($response, 'orders/order_add.tpl', ['active_menu' => 'orders',
            'shops' => $shops, 'page_title' => 'Niewe Order']);
    }

    /**************************************************************************************************************************************************
     **************************************************************( Order Add New Create )************************************************************
     **************************************************************************************************************************************************/
    public function addNewPost($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'shop' => v::notEmpty(),
        ]);
        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Orders.GetNew'));
        }

        DB::insert(Order::$table, ['shop_id' => $request->getParam('shop'), 'user_id' => Auth::user_id(), 'status_id' => 1, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]);
        $id = DB::insertId();
        if ($id) {
            return $response->withRedirect($this->router->pathFor('OrdersGetSingle', ['id' => $id]));
        } else {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Orders.GetNew'));
        }
    }

    /**************************************************************************************************************************************************
     ******************************************************************( Check Postcode )**************************************************************
     **************************************************************************************************************************************************/
    public function checkPostcode($request, $response, $args)
    {
        $orders = Order::All(1, '', ['paid' => '1']);
        $wrongAddresses = [];
        foreach ($orders as $index => $order) {
            $type = '';
            $order['order_details'] = json_decode($order['order_details'], true);
            $orders[$index]['order_details'] = $order['order_details'];
            $shippingAddress = $order['order_details']['address']['shipping'];
            $zipcode = '';
            if ($shippingAddress['zipcode']) {
                $zipcode = str_replace(' ', '', strtoupper($order['order_details']['address']['shipping']['zipcode']));
            }
            $showError = '0';
            if (strlen($zipcode) != 6 && $order['order_details']['address']['shipping']['countryCode'] == 'NL') {
                $showError = '1';
                $type = '1';
            } elseif (strlen($zipcode) < 4 && $order['order_details']['address']['shipping']['countryCode'] == 'BE') {
                $showError = '1';
                $type = '2';
            }
            if ($showError == '1') {
                $order['type'] = $type;
                $wrongAddresses[] = $order;
            }
        }

        $doubledAddresses = [];
        $postcodes = [];
        foreach ($orders as $order) {
            $org_zip = $order['order_details']['address']['shipping']['zipcode'];
            $zipcode = str_replace(' ', '', strtoupper($order['order_details']['address']['shipping']['zipcode']));
            if (in_array($zipcode, $postcodes)) {
                $query = 'SELECT ' . Order::$table . '.id,JSON_UNQUOTE(' . Order::$table . '.order_details) as order_details
            from ' . Order::$table . '
            where ' . Order::$table . ".status_id=%i and ispaid=%i and order_details->>'$.address.payment.zipcode'=%s";
                $ordersTemp = DB::query($query, 1, 1, $org_zip);
                foreach ($ordersTemp as $orderTmp) {
                    $orderTmp['order_details'] = json_decode($orderTmp['order_details'], true);
                    array_push($doubledAddresses, $orderTmp);
                }
            }
            array_push($postcodes, $zipcode);
        }
        return $this->view->render($response, 'orders/popups/postcode.tpl', ['wrongAddresses' => $wrongAddresses, 'doubledAddresses' => $doubledAddresses]);
    }

    public function importOrders($request, $response, $args)
    {
        $orders = DB::query("select * from webshop_cart where date_paid != '' and processed=0 limit 100");

        $arraIDS = [];
        foreach ($orders as $keyOrder => $order) {
            $arraIDS[] = $order['id'];
            $orderItems = DB::query('select * from webshop_cart_item where webshop_cart_id=%i', $order['id']);
            foreach ($orderItems as $key => $orderItem) {
                $orderItemAttributes = DB::query("select attribute.name->>'$.nl' as attribute,attribute.id from webshop_cart_item_attribute
            left join product_attribute on product_attribute.id = webshop_cart_item_attribute.product_attribute_id
            left join attribute on product_attribute.attribute_id= attribute.id
            where webshop_cart_item_id=%i", $orderItem['id']);
                foreach ($orderItemAttributes as $orderItemAttribute) {
                    $orderItem['attributes'][] = $orderItemAttribute;
                    ;
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
            $orderChecking = DB::queryFirstRow('select webshop_cart_id from ' . Order::$table . ' where webshop_cart_id=%i', $order['id']);
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
            $order_details['address']['payment']['houseNumberSupplement'] = $order['order_data']['customer']['housenumber_addition'];
            $order_details['address']['payment']['zipcode'] = $order['order_data']['customer']['zipcode'];
            $order_details['address']['payment']['email'] = $order['order_data']['customer']['email'];
            $order_details['address']['payment']['phone'] = $order['order_data']['customer']['phone'];
            $order_details['address']['payment']['city'] = $order['order_data']['customer']['city'];
            $order_details['address']['payment']['ip_address'] = $order['order_data']['customer']['ip_address'];
            $order_details['address']['payment']['countryCode'] = $order['order_data']['customer']['country'];

            $order_details['address']['shipping']['gender'] = $order['order_data']['customer']['payment_sex'];
            $order_details['address']['shipping']['firstname'] = $order['order_data']['customer']['firstname'];
            $order_details['address']['shipping']['lastname'] = $order['order_data']['customer']['lastname'];
            $order_details['address']['shipping']['street'] = $order['order_data']['customer']['address'];
            $order_details['address']['shipping']['houseNumber'] = $order['order_data']['customer']['housenumber'];
            $order_details['address']['shipping']['houseNumberSupplement'] = $order['order_data']['customer']['housenumber_addition'];
            $order_details['address']['shipping']['zipcode'] = $order['order_data']['customer']['zipcode'];
            $order_details['address']['shipping']['email'] = $order['order_data']['customer']['email'];
            $order_details['address']['shipping']['phone'] = $order['order_data']['customer']['phone'];
            $order_details['address']['shipping']['city'] = $order['order_data']['customer']['city'];
            $order_details['address']['shipping']['ip_address'] = $order['order_data']['customer']['ip_address'];
            $order_details['address']['shipping']['countryCode'] = $order['order_data']['customer']['country'];

            $shippingCost = 0;

            foreach ($order['transaction_data']['data']['shopping_cart']['items'] as $item) {
                if ($item['merchant_item_id'] == 'shipping') {
                    $shippingCost = $item['unit_price'];
                }
            }
            $tmpAmount = $order['transaction_data']['data']['amount'] / 100 - $shippingCost;
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
                $invoice['last_invoice']++;
                DB::update('invoices', ['last_invoice' => $invoice['last_invoice']], 'shop_id=%i', $order['shop_id']);
                $invoice = $invoice['orderpre'] . $invoice['last_invoice'];
            } else {
                $invoice = '';
            }
            $check = DB::insert('orders', [
                'shop_id' => $order['shop_id'], 'status_id' => 1, 'transaction_id' => $order['transaction_data']['data']['order_id'],
                'gross_price' => $prices['gross_price'], 'ispaid' => 1, 'order_details' => json_encode($order_details), 'payment' => json_encode($payment),
                'net_price' => $prices['net_price'], 'shipping_cost' => $shippingCost,
                'vat' => $prices['vat'], 'invoicenr' => $invoice, 'webshop_cart_id' => $order['id'], 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            if ($check) {
                $orderID = DB::insertId();
                DB::update('webshop_cart', ['processed' => '1'], 'id=%i', $order['id']);

                foreach ($order['order-items'] as $item) {
                    $product = Product::getProduct($item['product_id']);
                    DB::insert(Order::$table_order_items, [
                        'product_id' => $item['product_id'], 'product_name' => $product['contents']['title'],
                        'order_id' => $orderID, 'shop_id' => $order['shop_id'], 'count' => $item['product_quantity'], 'price' => $product['prices']['price'][$order['shop_id']]['price'],
                        'totalprice' => ($product['prices']['price'][$order['shop_id']]['price'] * $item['product_quantity']), 'combo' => 0, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
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
                                'product_id' => $product['id'], 'product_name' => 'COMBO->' . $product['contents']['title'],
                                'order_id' => $orderID, 'shop_id' => $order['shop_id'], 'count' => $item['product_quantity'], 'price' => $price,
                                'totalprice' => $price, 'combo' => 1, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                            ]);
                        }
                    }
                    if ($orderItemId) {
                        foreach ($item['attributes'] as $attribute) {
                            DB::insert(Order::$table_item_attribute, ['order_item_id' => $orderItemId, 'attribute_id' => $attribute['id']]);
                        }
                    }
                }
            }
        }
    }

    /**
     * registerPayment
     *
     * @param  int order id
     *
     * @return success or failed
     */
    public function registerPayment($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'order_id' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'De ID van de Order wordt niet gevonden !!']);
        }
        $order = DB::queryFirstRow('SELECT id  FROM ' . Order::$table . ' where id=%i', $request->getParam('order_id'));
        if ($order) {
            $check = DB::update(Order::$table, [['ispaid' => 1, 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')]], 'id=%i', $request->getParam('order_id'));
            if ($check) {
                UserActivity::Record('Paid changed to 1 ', $request->getParam('order_id'), 'Orders');
                return $response->withJson(['status' => 'true']);
            }
        }
    }

    /**
     * pakbonIndex function
     *
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return array
     */
    public function pakbonIndex($request, $response, $args)
    {
        $records = DB::query('SELECT *  FROM selektvracht where shipment=0 order by order_id');
        if (count($records) > 0) {
            $i = 1;
            $waybill_error = false;
            foreach ($records as $key => $record) {
                $verlader = $record['verlader'];
                switch ($verlader) {
                    case '79099101':
                        // handtekening
                        $packagetype = 'pakket + handtekening';
                        break;
                    case '79099100':
                        // pakket > 250 gram
                        $packagetype = 'pakket';
                        break;
                    case '39099104':
                        // brievenbus > 250 gram
                        $packagetype = 'brievenbus > 250g';
                        break;
                    case '39099104':
                        // brievenbus < 250 gram
                        $packagetype = 'brievenbus < 250g';
                        break;
                }
                $records[$key]['packagetype'] = $packagetype;
                $order = Order::GetSingle($record['order_id']);
                $order['order_details']['address']['shipping']['zipcode'] =
                strtoupper(str_replace(' ', '', $order['order_details']['address']['shipping']['zipcode']));
                $records[$key]['order'] = $order;
            }
        }
        return $this->view->render($response, 'pakbon/index.tpl', ['active_menu' => 'orders',
            'records' => $records, 'page_title' => 'Pakbon']);
    }

    /**
     * pakbonDhlNotify send the pakbon to DHL function
     *
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return array json
     */
    public function pakbonDhlNotify($request, $response, $args)
    {
        $date = date('U');
        $old_db_server = new \MeekroDB('localhost');
        $old_db_server->user = 'webdev';
        $old_db_server->password = 'LurHUUga';
        $old_db_server->read_host = '123bestdeal.nl';
        $old_db_server->write_host = '123bestdeal.nl';
        $old_db_server->port = '3306';
        $old_db_server->dbName = 'i12cover_crm';

        $old_db_server->query("INSERT INTO 123_selektvracht_waybills (id,orders,date) VALUES (0,'','" . $date . "')");
        $last1 = $old_db_server->insertId();
        DB::query("insert into selektvracht_waybills (id,orders,date) VALUES (0,'','" . $date . "')");
        $last = sprintf('%04d', $last1);
        $vdate = date('d-m-Y');
        $output = '/var/www/beta_i12cover/voormeld/pakbon_' . $last . '-' . $vdate . '.txt';
        $fp = fopen($output, 'w');
        if ($fp) {
            stream_set_write_buffer($fp, 0);
            fwrite($fp, $this->createWaybill());
            fclose($fp);
        }

        // REMOTE FTP
        $remote_file = "/voormeld/BDL$last.TXT";
        // NEW SINCE 11-04-2016
        $ftp_server = 'ftp.dhlp.nl';
        $ftp_user_name = 'bestdeal';
        $ftp_user_pass = '4sydIQJB';


        // 20-09-2018 FTPS upload with certificate
        ini_set('display_errors', 'On');
        error_reporting(E_ALL | E_STRICT);

        $ssh_conn = ssh2_connect('sftp.dhlp.nl', 22, array('hostkey' => 'ssh-rsa'));
        ssh2_auth_pubkey_file($ssh_conn, 'bestdeal', '/var/www/beta_i12cover/voormeld/id_rsa.pub', '/var/www/beta_i12cover/voormeld/id_rsa');

        $sftp = ssh2_sftp($ssh_conn);
        $sftpStream = fopen('ssh2.sftp://'.$sftp.$remote_file, 'w');

        
        try {
            if (!$sftpStream) {
                throw new Exception("Could not open remote file: $remote_file");
            }

            $data_to_send = file_get_contents($output);
            if ($data_to_send === false) {
                throw new Exception("Could not open local file: $output.");
            }

            if (fwrite($sftpStream, $data_to_send) === false) {
                throw new Exception("Could not send data from file: $output.");
            }

            fclose($sftpStream);

            // Upload succesfull, now mark all orders as shipped on the waybill
            // mysql_query("UPDATE 123_selektvracht SET shipment = '$last1' WHERE shipment = '0'");

            DB::query("UPDATE selektvracht SET shipment = '$last1' WHERE shipment = '0'");
            //mail("mail@remo.pro","DHL UPLOAD CONTROLEREN",'CHECK UPLOAD');
        } catch (Exception $e) {
            error_log('Exception: ' . $e->getMessage());
            fclose($sftpStream);
        }


        return $response->withJson(['status' => 'true']);
    }

    public function createWaybill()
    {
        $eol = "\n";
        /*
        POS1-4:     A030
        POS 6-9:    XXXX = klantkenmerk (min. 1 en max. 4 hoofdletters)
        POS 10-30:  spaties
        POS 31-110: abc@xyz.nl
        */

        $output = 'A030 BDL                      info@123bestdeal.nl' . $eol;
        $get = DB::query("SELECT * FROM selektvracht WHERE shipment = '0' ORDER BY order_id ASC");
        foreach ($get as $getr) {
            //reset
            $address = '';
            $zipcode = '';
            $here = '';
            $num = '';
            $num2 = '';
            $huisnummer = '';
            $huisnummer2 = '';
            $huisnummer_toevoeging = '';
            $barcode = $getr['barcode'];
            $verlader = $getr['verlader'];

            $order = Order::GetSingle($getr['order_id']);

            if ($order) {
                // zoek huisnummer en eventuele toevoeging
                $address = $order['order_details']['address']['shipping']['street'] . ' ' .
                    $order['order_details']['address']['shipping']['houseNumber'] . ' ' .
                    $order['order_details']['address']['shipping']['houseNumberSupplement'];
                $zipcode = $order['order_details']['address']['shipping']['zipcode'];

                $here = explode(' ', $address);
                $num = (count($here) - 1);
                $num2 = (count($here) - 2);

                $huisnummer = strtoupper(str_replace(' ', '', $here[$num]));
                $huisnummer = str_replace('-', '', $huisnummer);
                $huisnummer = str_replace('.', '', $huisnummer);

                $huisnummer2 = strtoupper(str_replace(' ', '', $here[$num2]));
                $huisnummer2 = str_replace('-', '', $huisnummer2);
                $huisnummer2 = str_replace('.', '', $huisnummer2);

                $email = $order['order_details']['customerEmail'];

                if (is_numeric($huisnummer2)) {
                    if (!is_numeric($huisnummer)) {
                        $huisnummer_toevoeging = $huisnummer;
                        $huisnummer = $huisnummer2;
                    }
                }

                if (!is_numeric($huisnummer)) { // e.g. 28C
                    $huisnummer_toevoeging = $huisnummer;
                    $huisnummer = '0';
                }
            }
            //

            $postcode = strtoupper(str_replace(' ', '', $zipcode));

            $output .= 'V010 X' . $eol;
            $output .= 'V011 1' . $eol;
            $output .= "V020 $barcode" . $eol;

            // added 06-12-2018
            $output .= 'V173 01' . $eol;
            $output .= 'V176 ' . $email . $eol;

            $output .= "V180 $huisnummer" . $eol;
            if ($huisnummer_toevoeging) {
                $output .= "V181 $huisnummer_toevoeging" . $eol;
            }
            $output .= "V190 $postcode" . $eol;
            $output .= "V800 $verlader" . $eol;
            $output .= 'V999 Z' . $eol;
        }
        $output .= 'Z999 Z';

        return $output;
    }
}
