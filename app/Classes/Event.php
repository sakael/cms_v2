<?php

namespace App\Classes;

use DB;
use App\Classes\Mail;
use App\Classes\Order;
//use Phpfastcache\CacheManager;
use Carbon\Carbon as Carbon;
use Phpfastcache\Drivers\Redis\Config;
use Phpfastcache\Helper\Psr16Adapter;

class Event
{
    /**
     * OrderStatusBol function
     *
     * @param int $orderId
     * @return void
     */
    private static $cache;
    private static $cacheChecking = false;

    private static function init()
    {
        /*if (!self::$cacheChecking) {
            self::$cache = new Psr16Adapter('redis', new Config([
                'host' => 'tls://db-redis-ams3-123bestdeal-do-user-3545834-0.a.db.ondigitalocean.com', //Default value
                'port' => 25061, //Default value
                'password' => 'ipk22dxl35lz1ot7',
                'database' => 0,
            ]));
            self::$cacheChecking = true;
        }*/
    }

    public function __construct()
    {
        self::init();
    }

    public function orderStatusBol($orderId)
    {
        // dd($orderId);
    }

    /**
     * orderStatusChanged it runs when a status of an order is changed function
     *
     * @param int $orderId
     * @return void
     */
    public function orderStatusChanged($orderId, $statusId, $inform = false)
    {
        $order = Order::GetSingle($orderId);
        // send email when inform is true
        if ($inform) {
            $mail = new Mail();
            $status = DB::query("select name->>'$." . language . "' as name, email_subject->>'$." . language . "' as email_subject,
            email_content->>'$." . language . "' as email_content,
             template_id->>'$." . language . "' as template_id  from order_status where id =%i limit 1", $statusId);

            if ($status) {
                $template = $status[0]['template_id'];
                $title = $status[0]['name'];
            } else {
                $template = 20;
                $title = '';
            }
            $template = 20;
            $data = [];
            $data['tags'] = ['order', 'status_' . $statusId];
            $data['email_subject'] = $status[0]['email_subject'];
            $status = $status[0];

            switch ($statusId) {
                case 1:
                    $mail->prepareOrderNewMailData($order, $status, $data, $template);
                    break;
                case 3:
                    $mail->prepareOrderSentMailData($order, $status, $data, $template);
                    break;
                default:
                    $mail->prepareOrderOtherStatusMailData($order, $status, $data, $template);
                    break;
            }
            $mail->sendMailTransac();
        }
    }

    /**
     * orderImported it runs when an order is imported from cron script
     *
     * @param int $orderId
     * @return void
     */
    public function orderImported($orderId)
    {
        $order = Order::GetSingle($orderId);
        $status = DB::query("select name->>'$." . language . "' as name, email_subject->>'$." . language . "' as email_subject,
        email_content->>'$." . language . "' as email_content,
         template_id->>'$." . language . "' as template_id  from order_status where id =%i limit 1", 1);
        $data['tags'] = ['order', 'inform_us_status_' . 1];
        $data['email_subject'] = $status[0]['email_subject'];
        $status = $status[0];
        $mail = new Mail();
        $mail->prepareOrderNewInfomUsMailData($order, $status, $data, 20);
        $mail->sendMailTransac();
    }

    /**
    * productUpdated, it runs when a product is updated
    *
    * @param int $orderId
    * @param string $slug
    * @param string $type
    * @return void
    */
    public function productUpdated($productId, $slug = '', $type = 'all')
    {
        switch ($type) {
            case 'all':
                $this->cache->delete('Product.' . $productId . '.' . language);
                $this->cache->delete('Product.Slug.' . $slug . '.' . language);
                $this->cache->delete('Product.' . $productId . '.Images' . '.' . language);
                $this->cache->delete('Product.' . $productId . '.Url' . '.' . language);
                $this->cache->delete('Product.' . $productId . '.Attributes' . '.' . language);
                $this->cache->delete('Product.' . $productId . '.Combos' . '.' . language);
                $this->cache->delete('Product.' . $productId . '.Pricing' . '.' . language);
                $this->cache->delete('Product.' . $productId . '.Measurements' . '.' . language);
                $this->cache->delete('Product.' . $productId . '.Reviews' . '.' . language);
                $this->cache->delete('Product.' . $productId . '.Devices' . '.' . language);
                $this->cache->delete('Product.' . $productId . '.categories' . '.' . language);
                $categories = DB::query('select category_id from product_categories where product_id =%i', $productId);
                foreach ($categories as $category) {
                    $this->cache->delete('Category.' . $category['category_id'] . '.ProductIds' . '.' . language);
                }
                break;
            case 'category':
                $categories = DB::query('select category_id from product_categories where product_id =%i', $productId);
                foreach ($categories as $category) {
                    $this->cache->delete('Category.' . $category['category_id'] . '.ProductIds' . '.' . language);
                }
                break;
            case 'shop':
                $shops = DB::query('select shop_id from product_shop where product_id =%i', $productId);
                foreach ($shops as $shop) {
                    $this->cache->delete('shop_class.' . $shop['shop_id'] . '.listShopProducts' . '.' . language);
                }
                break;
            case 'content':
                $this->cache->delete('Product.' . $productId . '.' . language);
                break;
            case 'images':
                $this->cache->delete('Product.' . $productId . '.Images' . '.' . language);
                break;
            case 'price':
                $this->cache->delete('Product.' . $productId . '.Pricing' . '.' . language);
                break;
            case 'measurements':
                $this->cache->delete('Product.' . $productId . '.Measurements' . '.' . language);
                break;
            case 'url':
                $this->cache->delete('Product.' . $productId . '.Url' . '.' . language);
                break;
            case 'combo':
                $this->cache->delete('Product.' . $productId . '.Combos' . '.' . language);
                break;
            case 'comboDeleted':
                $this->cache->delete('Product.' . $productId . '.Combos' . '.' . language);
                break;
            case 'attributes':
                $this->cache->delete('Product.' . $productId . '.Attributes' . '.' . language);
                break;
            case 'reviews':
                $this->cache->delete('Product.' . $productId . '.Reviews' . '.' . language);
                break;
            case 'child':
                $this->cache->delete('Product.' . $productId . '.Devices' . '.' . language);
                break;
        }
        DB::update('product', [['updated_at' => Carbon::now()->format('Y-m-d H:i:s')]], 'id=%i', $productId);
    }

    /**
    * categoryUpdated, it runs when a category is updated
    *
    * @param int $categoryId
    * @param string $categoryName
    * @param string $type
    * @return void
    */
    public function categoryUpdated($categoryId, $categoryName, $type = 'all')
    {
        $key = 'getCategoryByName' . $categoryName . $categoryId . language;
        // $this->cache->delete($key);
    }

    /**
    * brandUpdated, it runs when a brand is updated
    *
    * @param int $brandId
    * @param string $brandName
    * @param string $type
    * @return void
    */
    public function brandUpdated($brandId, $brandName, $type = 'all')
    {
        switch ($type) {
            case 'all':
                $this->cache->delete('Brand.' . $brandId . '.Types' . '.' . language);
                $this->cache->delete('Brand.' . $brandName  . '.' . language);
                $key = 'getTypesForDevice' . $brandId . $brandName . language;
                $this->cache->delete($key);
                break;
        }
        
        
        /*
        ///
        'getTypeByName'.$brandId.$type_name
        'getAvailableAttributes'.$brand_id.$brand_type_id.$category_id;
        '*/
    }

    /**
    * typeUpdated, it runs when a type is updated
    *
    * @param int $typeId
    * @param int $typeName
    * @param int $brandId
    * @param string $type
    * @return void
    */
    public function typeUpdated($typeId, $typeName = '', $brandId = '', $type = 'all')
    {
        switch ($type) {
            case 'all':
                $this->cache->delete('Type.' . $typeName . '.' . language);
                break;
            case 'categories':
                $this->cache->delete('Type.' . $typeId . '.Categories' . '.' . language);
                break;
            case 'brand':
                $this->cache->delete('Brand.' . $brandId[0] . '.Types' . '.' . language);
                $this->cache->delete('Brand.' . $brandId[1] . '.Types' . '.' . language);
                $this->cache->delete('Type.' . $typeName . '.' . language);
                break;
        }
    }

    /**
    * typeCreated, it runs when a type is created
    *
    * @param int $typeId
    * @param int $brandId
    * @param string $type
    * @return void
    */
    public function typeCreated($typeId, $brandId, $type = 'all')
    {
        switch ($type) {
            case 'all':
                $this->cache->delete('Brand.' . $brandId . '.Types' . '.' . language);
                break;
        }
    }
    /**
    * productChildsUpdated, it runs when a type is added or removed from product
    *
    * @param int $productId
    * @param int $typeId
    * @return void
    */
    public function productChildsUpdated($productId, $typeId)
    {
        $this->cache->delete('Type.' . $typeId . '.ProductsIds' . '.' . language);
    }
}
