<?php

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../config.inc.php';

error_reporting(0);
ini_set('display_errors', 0);



/* This script generates the main CSV for Channable */

$file = fopen('productfeed.json', 'w');
fwrite($file,'[');  // opens the JSON array block



/* Load basic data */

$shops = DB::query("SELECT * FROM shop");
$webshop_shops = DBHelper::reIndex($shops,'id');

$attribute_sets = array();

$attributes = DB::query("SELECT id,name,attribute_group_id FROM attribute");
foreach($attributes as $a_id => $a){
    $a['name'] = json_decode($a['name'],true);
    $attributes[$a_id] = $a;
}
$attributes = DBHelper::reIndex($attributes,'id');

$attribute_groups = DB::query("SELECT id,name FROM attribute_group");
foreach ($attribute_groups as $g_id => $g){ $attribute_groups[$g_id]['name'] = json_decode($g['name'],true); }
$attribute_groups = DBHelper::reIndex($attribute_groups, 'id');

$categories = DB::query("SELECT id,name FROM category");
foreach ($categories as $c_id => $c){ $categories[$c_id]['name'] = json_decode($c['name'],true); }
$categories = DBHelper::reIndex($categories,'id');

$product_brands = DB::query("SELECT id, name FROM product_brand");
$product_brands = DBHelper::reIndex($product_brands,'id');

$product_brand_types = DB::query("SELECT id,product_brand_id, name FROM product_brand_type");
$product_brand_types = DBHelper::reIndex($product_brand_types,'id');



/* Here we go */

$product_data = array();

$limit = 0;
while(true){

    DB::debugMode();

    // 100 products per query
    $products = DB::query("
        SELECT
        p.*,
        pm.measurements,
        pp.price

        FROM product p
        LEFT JOIN product_measurements pm ON pm.product_id = p.id
        LEFT JOIN product_pricing pp ON pp.product_id = p.id

        LIMIT ".$limit.",100");

    if (!$products || count($products) < 1)
        break;

    DB::debugMode(0);

    foreach ($products as $product){

        $product['meta'] = DB::query("SELECT * FROM product_meta WHERE product_id=%i", $product['id']);
        $product['shop'] = DB::query("SELECT * FROM product_shop WHERE product_id=%i", $product['id']);
        $product['url'] = DB::queryFirstRow("SELECT * FROM product_url WHERE product_id=%i ORDER BY id DESC", $product['id']);
        $product['categories'] = DB::queryOneColumn("category_id","SELECT category_id FROM product_categories WHERE product_id=%i", $product['id']);
        $product['attributes'] = DB::queryOneColumn("attribute_id", "SELECT attribute_id FROM product_attribute WHERE product_id=%i AND attribute_id IS NOT NULL", $product['id']);
        $product['children'] = DB::queryOneColumn("product_brand_type_id", "SELECT product_brand_type_id FROM product_child WHERE product_id=%i", $product['id']);
        $product['contents'] = json_decode($product['contents'],true);
        $product['measurements'] = json_decode($product['measurements'],true);
        $product['price'] = json_decode($product['price'],true);

        $p = array(
            'id' => $product['id'],
            'sku' => $product['sku'],
            'active' => ($product['active'] == 1 ? 'true' : 'false'),
            'url' => 'https://123bestdeal.nl/'.$product['url']['slug'],
            'stock' => array(
                'status' => $product['stocklevel'],
                'level' => 100,
            ),
            'measurements' => array(
                'width' => $product['measurements']['width'],
                'length' => $product['measurements']['length'],
                'height' => $product['measurements']['height'],
                'weight' => $product['measurements']['weight'],
                'min_width' => $product['measurements']['minwidth'],
                'max_width' => $product['measurements']['maxwidth'],
                'min_length' => $product['measurements']['minlength'],
                'max_length' => $product['measurements']['maxlength'],
            ),
            'images' => array(),
            'prices' => array(),
            'children' => array(),
            'languages' => array(),
        );

        foreach ($product['meta'] as $image){
            if ($image['main'] == 1){
                $p['images']['main'] = 'https://ams3.cdn.123bestdeal.nl/'.$image['url'];
            }else{
                $p['images']['other'][] = 'https://ams3.cdn.123bestdeal.nl/'.$image['url'];
            }
        }

        foreach($product['shop'] as $shop){
            $p['prices'][filter($webshop_shops[$shop['shop_id']]['domain'])] = round($product['price'][$shop['shop_id']]['price'],2);
        }

        foreach ($product['children'] as $product_brand_type_id){
            $brand_type = $product_brand_types[$product_brand_type_id];
            $brand = $product_brands[$brand_type['product_brand_id']];
            $p['children'][$brand['name']][] = $brand_type['name'];
        }

        // Now the language-specific data

        $languages = array('nl','en');

        foreach ($languages as $lang){

            $p['languages'][$lang] = array(
                'title' => $product['contents'][$lang]['title'],
                'title_ext' => $product['contents'][$lang]['title_ext'],
                'description' => $product['contents'][$lang]['description'],
                'description_ext' => $product['contents'][$lang]['description_ext'],
                'characteristics' => array(),
                'attributes' => array(),
                'categories' => array()
            );

            foreach ($product['contents'][$lang]['characteristics'] as $usp){
                $p['languages'][$lang]['characteristics'][] = $usp;
            }

            foreach ($product['attributes'] as $attribute){
                $attribute_info = $attributes[$attribute];
                $attribute_group = $attribute_groups[$attribute_info['attribute_group_id']];
                $p['languages'][$lang]['attributes'][filter($attribute_group['name'][$lang])] = $attribute_info['name'][$lang];
            }

            foreach ($product['categories'] as $c){
                $p['languages'][$lang]['categories'][] = $categories[$c]['name'][$lang];
            }

        }

        print_r($p); die();
        fwrite($file,json_encode($p).',');

    }

    // only process 100 for now
    fwrite($file,']'); die("done");

    $limit+=100;

}
fwrite($file,']');
print_r("done");

function filter($in){

    // Filters everything that shouldn't be in a key of an array
    $out = strtolower($in);
    //$out = preg_replace('/[[:^print:]]/', '', $out);
    $out = preg_replace("/[^a-zA-Z0-9]/", '', $out);
    $out = str_replace(" ","_",$out);

    return $out;
}
