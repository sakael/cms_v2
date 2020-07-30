<?php

namespace App\Classes;

use DB;
use App\Classes\Category;
use App\Classes\Event;
use Carbon\Carbon as Carbon;
use Nextimage\Nextimage\Resize;

class Product
{
    public static $thumb = true;
    public static $thumbs = [
        ['name' => '123bestdeal', 'w' => '173', 'h' => '130'],
        ['name' => 'bol', 'w' => '250', 'h' => '250'],
        ['name' => 'cart', 'w' => '65', 'h' => '48', 'type' => 'maxwidth']
    ];
    protected static $table = 'product';
    public static $imageFolder = 'products';

    public static function getAttributes($product_id)
    {
        /*
        Fetch all available attributes
        */

        $product_attributes = DB::queryOneColumn('attribute_id', 'SELECT * FROM product_attribute WHERE product_id=%i', $product_id);

        $attributes = [];
        $attribute_groups = DB::query("SELECT id, JSON_UNQUOTE(JSON_EXTRACT(name, '$." . language . "')) as name, multiselect FROM attribute_group");
        foreach ($attribute_groups as $group) {
            $group_attributes = DB::query("SELECT id, JSON_UNQUOTE(JSON_EXTRACT(name, '$." . language . "')) as name FROM attribute WHERE attribute_group_id=%i", $group['id']);
            foreach ($group_attributes as $ga_id => $ga) {
                $group_attributes[$ga_id]['active'] = 0;
                if (in_array($ga['id'], $product_attributes)) {
                    $group_attributes[$ga_id]['active'] = 1;
                }
            }

            $attributes[$group['name']]['id'] = $group['id'];
            $attributes[$group['name']]['multiselect'] = $group['multiselect'];
            $attributes[$group['name']]['attributes'] = $group_attributes;
        }

        //print_r($attributes);
        return $attributes;
    }

    public static function getCategories($product_id)
    {
        /*

        Fetch all available categories

        */

        $product_categories = DB::queryOneColumn('category_id', 'SELECT * FROM product_categories WHERE product_id=%i', $product_id);

        $categories = [];
        $main_categories = Category::All();
        foreach ($main_categories as $id => $cat) {
            $main_categories[$id]['active'] = 0;
            if (in_array($cat['id'], $product_categories)) {
                $main_categories[$id]['active'] = 1;
            }
        }

        //print_r($main_categories);
        return $main_categories;
    }

    public static function getWebshops($product_id)
    {
        /*
        Fetch all webshops where the product is shown
        */
        $product_webshops = DB::queryOneColumn('shop_id', 'SELECT * FROM product_shop WHERE product_id=%i', $product_id);

        $webshops = [];
        $all_shops = DB::query('SELECT * FROM shop');
        foreach ($all_shops as $id => $shop) {
            $all_shops[$id]['active'] = 0;
            if (in_array($shop['id'], $product_webshops)) {
                $all_shops[$id]['active'] = 1;
            }
        }

        //print_r($all_shops);
        return $all_shops;
    }

    public static function getProduct($product_id)
    {
        $product = DB::queryFirstRow("SELECT *, JSON_UNQUOTE(JSON_EXTRACT(contents, '$." . language . "')) as contents FROM product WHERE id=%i", $product_id);

        if (!$product) {
            return false;
        }
        $product['contents'] = $product['contents'];
        $product['contents'] = json_decode($product['contents'], true);

        $product['images'] = DB::query("SELECT *, JSON_UNQUOTE(JSON_EXTRACT(title, '$." . language . "')) as title FROM product_meta WHERE product_id=%i ORDER BY sort_order ASC", $product_id);
        $product['urls'] = DB::query('SELECT * FROM product_url WHERE product_id=%i AND lang=%s ORDER BY id DESC', $product_id, language);

        $price = DB::queryFirstRow('SELECT *,JSON_UNQUOTE(price) as price FROM product_pricing WHERE product_id=%i limit 1', $product_id);
        $price['price'] = json_decode($price['price'], true);
        $product['prices'] = $price;

        $measurements = DB::queryFirstRow('SELECT *,JSON_UNQUOTE(measurements) as measurements FROM product_measurements WHERE product_id=%i limit 1', $product_id);
        $measurements = json_decode($measurements['measurements'], true);
        $product['measurements'] = $measurements;
        $b2b = DB::queryFirstRow('SELECT * FROM b_2_b WHERE product_id=%i limit 1', $product_id);
        $product['b2b'] = $b2b;
        return $product;
    }
    /**
     * getProductBySku function
     *
     * @param [type] $sku
     * @return product
     */
    public static function getProductBySku($sku)
    {
        $product = DB::queryFirstRow("SELECT *, JSON_UNQUOTE(JSON_EXTRACT(contents, '$." . language . "')) as contents FROM product WHERE sku=%s limit 1", $sku);

        if (!$product) {
            return false;
        }
        $product['contents'] = $product['contents'];
        $product['contents'] = json_decode($product['contents'], true);

        $product['images'] = DB::query("SELECT *, JSON_UNQUOTE(JSON_EXTRACT(title, '$." . language . "')) as title FROM product_meta WHERE product_id=%i ORDER BY sort_order ASC", $product['id']);
        $product['urls'] = DB::query('SELECT * FROM product_url WHERE product_id=%i AND lang=%s ORDER BY id DESC', $product['id'], language);

        $price = DB::queryFirstRow('SELECT *,JSON_UNQUOTE(price) as price FROM product_pricing WHERE product_id=%i limit 1', $product['id']);
        $price['price'] = json_decode($price['price'], true);
        $product['prices'] = $price;

        $measurements = DB::queryFirstRow('SELECT *,JSON_UNQUOTE(measurements) as measurements FROM product_measurements WHERE product_id=%i limit 1', $product['id']);
        $measurements = json_decode($measurements['measurements'], true);
        $product['measurements'] = $measurements;
        $b2b = DB::queryFirstRow('SELECT * FROM b_2_b WHERE product_id=%i limit 1', $product['id']);
        $product['b2b'] = $b2b;
        return $product;
    }

    public static function getProducts($options = [], $datatable = true)
    {
        if (empty($options)) {
            // fetch all products
            $products = DB::query("
                SELECT *,
                JSON_UNQUOTE(JSON_EXTRACT(contents, '$." . language . ".title')) as title
                FROM product order by id");

            foreach ($products as $id => $p) {
                $products[$id]['contents'] = @json_decode($p['contents'], true);
            }
        }
        //dd($products);

        $returndata = [
            'draw' => null,
            'cached' => null,
            'recordsTotal' => count($products),
            'recordsFiltered' => count($products),
            'data' => $products
        ];
        if ($datatable) {
            return json_encode($returndata);
        } else {
            return $products;
        }
    }

    public static function handleUpdate($product_id, $requestdata)
    {
        //get slug to delete the cache
        $slug = DB::query('SELECT slug FROM product_url WHERE product_id=%i AND lang=%s ORDER BY id DESC limit 1', $product_id, language);
        if ($slug) {
            $slug = $slug[0]['slug'];
        } else {
            $slug = '';
        }
        $event = new Event();
        switch ($requestdata['type']) {
            case 'attributes':
                // get all product attributes
                $existing_attributes = DB::query('
                    SELECT pa.attribute_id FROM product_attribute pa
                    LEFT JOIN attribute a ON a.id = pa.attribute_id
                    WHERE pa.product_id=%i AND a.attribute_group_id=%i', $product_id, $requestdata['group']);

                // should we delete attributes?
                foreach ($existing_attributes as $attr) {
                    if (empty($requestdata['attributes']) || !in_array($attr['attribute_id'], $requestdata['attributes'])) {
                        DB::query('DELETE FROM product_attribute WHERE product_id=%i AND attribute_id=%i', $product_id, $attr['attribute_id']);
                    }
                }

                // shoud we add attributes?
                if (is_array($requestdata['attributes'])) {
                    foreach ($requestdata['attributes'] as $attr_id) {
                        if (!DB::query('SELECT id FROM product_attribute WHERE product_id=%i AND attribute_id=%i limit 1', $product_id, $attr_id)) {
                            DB::insert('product_attribute', ['product_id' => $product_id, 'attribute_id' => $attr_id]);
                        }
                    }
                } else {
                    if (!DB::query('SELECT id FROM product_attribute WHERE product_id=%i AND attribute_id=%i limit 1', $product_id, $requestdata['attributes'])) {
                        DB::insert('product_attribute', ['product_id' => $product_id, 'attribute_id' => $requestdata['attributes']]);
                    }
                }
                //runevent
                $event->productUpdated($product_id, $slug, 'attributes');
                UserActivity::Record('Update attributes (product)', $product_id, 'Products');
                break;
            case 'categories':
                DB::delete('product_categories', 'product_id=%i', $product_id);
                if (is_array($requestdata['categories']) && count($requestdata['categories']) > 0) {
                    foreach ($requestdata['categories'] as $cat) {
                        DB::insert('product_categories', ['category_id' => $cat, 'product_id' => $product_id]);
                    }
                }
                //runevent
                $event->productUpdated($product_id, $slug, 'category');
                UserActivity::Record('Update categories (product)', $product_id, 'Products');
                break;

            case 'shops':
                DB::delete('product_shop', 'product_id=%i', $product_id);
                if (is_array($requestdata['shops']) && count($requestdata['shops']) > 0) {
                    foreach ($requestdata['shops'] as $shop) {
                        DB::insert('product_shop', ['shop_id' => $shop, 'product_id' => $product_id]);
                    }
                }
                //run event
                $event->productUpdated($product_id, $slug, 'shop');
                UserActivity::Record('Update shops (product)', $product_id, 'Products');
                break;

            case 'contents':
                $product = DB::queryFirstRow('SELECT contents FROM product WHERE id=%i', $product_id);
                $contents = json_decode($product['contents'], true);
                foreach ($requestdata['contents'] as $key => $content) {
                    $contents[language][$key] = $content;
                }
                DB::update('product', ['contents' => json_encode($contents)], 'id=%i', $product_id);
                //run event
                $event->productUpdated($product_id, $slug, 'content');
                UserActivity::Record('Update contents (product)', $product_id, 'Products');
                break;

            case 'remove_image':
                // get the image details
                $image = DB::queryFirstRow('SELECT * FROM product_meta WHERE id=%i', $requestdata['image_id']);
                if (RemoveFromS3Bucket($image['url'], 'products', $product_id, self::$thumbs)) {
                    DB::delete('product_meta', 'id=%i', $requestdata['image_id']);
                }
                //run event
                $event->productUpdated($product_id, $slug, 'images');
                UserActivity::Record('Update remove_image (product)', $product_id, 'Products');
                break;

            case 'update_image':
                switch ($requestdata['cbtype']) {
                    case 'image_main':
                        DB::update('product_meta', ['main' => 0], 'product_id=%i', $product_id);
                        DB::update('product_meta', ['main' => 1], 'id=%i', $requestdata['image_id']);
                        break;
                    case 'image_main_ext':
                        DB::update('product_meta', ['main_ext' => 0], 'product_id=%i', $product_id);
                        DB::update('product_meta', ['main_ext' => 1], 'id=%i', $requestdata['image_id']);
                        break;
                    case 'image_hidden':
                        $i = DB::queryFirstRow('SELECT visible FROM product_meta WHERE id=%i', $requestdata['image_id']);
                        if ($i['visible'] == 0) {
                            DB::update('product_meta', ['visible' => 1], 'id=%i', $requestdata['image_id']);
                        } else {
                            DB::update('product_meta', ['visible' => 0], 'id=%i', $requestdata['image_id']);
                        }
                        break;
                }
                //run event
                $event->productUpdated($product_id, $slug, 'images');
                UserActivity::Record('Update update_image (product)', $product_id, 'Products');
                break;

            case 'update_image_title':
                DB::query("UPDATE product_meta SET title = JSON_SET(`title`,'$." . language . "',%s) WHERE id=%i", $requestdata['title'], $requestdata['image_id']);
                //run event
                $event->productUpdated($product_id, $slug, 'images');
                break;

            case 'update_image_order':
                foreach ($requestdata['keys'] as $id => $image) {
                    DB::update('product_meta', ['sort_order' => $id], 'id=%i', $image);
                }
                //run event
                $event->productUpdated($product_id, $slug, 'images');
                UserActivity::Record('Update update_image_order (product)', $product_id, 'Products');
                break;
            default:
                // run event
                $event->productUpdated($product_id, $slug);
                break;
        }

        return true;
    }

    public static function handleUpload($product_id, $file)
    {
        $s3_location = Upload('products', $product_id, $file);
        $non_image = 0;
        if (!is_array(getimagesize($file->file))) {
            $non_image = 1;
        }
        if ($s3_location) {
            DB::insert('product_meta', ['product_id' => $product_id, 'url' => $s3_location, 'non_image' => $non_image, 'title' => '{}']);
            $tmpProduct = ['id' => $product_id, 'photo' => $s3_location, 'name' => 'products'];
            if ($non_image == 0) {
                Thumbs($tmpProduct, $file, self::$thumbs);
            }
            //run event
            $event = new Event();
            $event->productUpdated($product_id, '', 'images');
            return true;
        }

        return false;
    }

    public function sendToS3Bucket($product_id, $local_file, $ext)
    {
        /*
        $upload_file = md5(date("U") . $local_file) . '.' . $ext;
        $r = $GLOBALS['s3']->putObject(array(
            'Bucket' => '123bdcdn',
            'Key' => 'products/'.$product_id . '/' . $upload_file,
            'SourceFile' => $local_file,
            'ACL' => 'public-read'
        ));
        return 'products/'.$product_id . '/' . $upload_file;*/
        $upload_file = md5(date('U') . $local_file) . '.' . $ext;
        // Image path
        $img = 'products/' . $product_id . '/' . $upload_file;

        if (!is_dir(FILES_DIR . 'products')) {
            mkdir(FILES_DIR . 'products', 0755, true);
        }
        if (!is_dir(FILES_DIR . 'products/' . $product_id)) {
            mkdir(FILES_DIR . 'products/' . $product_id, 0755, true);
        }

        $downloadPath = FILES_DIR . $img;
        $file = fopen($downloadPath, 'w+');
        $checking = fwrite($file, $local_file);
        fclose($file);
        if ($checking) {
            return $img;
        } else {
            return false;
        }
    }

    public static function getAttributesKeys($product_id)
    {
        /*
        Fetch all available attributes
        */
        $product_attributes = DB::queryOneColumn('attribute_id', 'SELECT * FROM product_attribute WHERE product_id=%i', $product_id);
        $attributes = [];
        $attribute_groups = DB::query("SELECT id, JSON_UNQUOTE(JSON_EXTRACT(name, '$." . language . "')) as name, multiselect FROM attribute_group");
        $attributesTmp = [];
        foreach ($attribute_groups as $group) {
            $group_attributes = DB::query("SELECT id, JSON_UNQUOTE(JSON_EXTRACT(name, '$." . language . "')) as name FROM attribute WHERE attribute_group_id=%i", $group['id']);
            foreach ($group_attributes as $ga_id => $ga) {
                $group_attributes[$ga_id]['active'] = 0;
                if (in_array($ga['id'], $product_attributes)) {
                    $group_attributes[$ga_id]['active'] = 1;
                    $attributesTmp[$group['id']][] = $ga;
                }
            }
            $attributes[$group['id']]['id'] = $group['id'];
            $attributes[$group['id']]['multiselect'] = $group['multiselect'];
            $attributes[$group['id']]['attributes'] = $group_attributes;
        }
        $attributes['products'] = $attributesTmp;
        return $attributes;
    }

    public static function getAllProductsIds($search = null)
    {
        if ($search == null) {
            return DB::queryOneColumn('id', 'SELECT * FROM product');
        } else {
            return DB::query('SELECT id,sku FROM product where id like %s or sku like %s', $search . '%', $search . '%');
        }
    }

    public static function getAllProductsIdSkuTitle($search = null)
    {
        if ($search == null) {
            return DB::query("SELECT id,sku,contents->>'$." . language . ".title' as title FROM product where active=1 order by sku");
        } else {
            return DB::query("SELECT id,sku,contents->>'$." . language . ".title' as title FROM product where active=1 and id like %s or sku like %s", $search . '%', $search . '%');
        }
    }

    public function getProductFromComboId($comboId)
    {
        $productId = DB::queryFirstRow('select combo_product_id from product_combo where id=%i', $comboId);
        return self::getProduct($productId['combo_product_id']);
    }

    public function getTableName()
    {
        return self::$table;
    }
}
