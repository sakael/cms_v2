<?php

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../config.inc.php';

/*

This script generates the main CSV for Channable

*/

$file = fopen('productfeed.xml', 'w');
$xml = new SimpleXMLExtended('<?xml version=\'1.0\' encoding=\'utf-8\' ?><products></products>');

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

print_r($attribute_groups);

$limit = 0;
while(true){

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


    foreach ($products as $product){

        // product_meta (images)
        // product_shop (links to shop)
        // product_url (get most recent)

        $product['meta'] = DB::query("SELECT * FROM product_meta WHERE product_id=%i", $product['id']);
        $product['shop'] = DB::query("SELECT * FROM product_shop WHERE product_id=%i", $product['id']);
        $product['url'] = DB::queryFirstRow("SELECT * FROM product_url WHERE product_id=%i ORDER BY id DESC", $product['id']);
        $product['attributes'] = DB::queryOneColumn("attribute_id", "SELECT attribute_id FROM product_attribute WHERE product_id=%i AND attribute_id IS NOT NULL", $product['id']);
        $product['contents'] = json_decode($product['contents'],true);
        $product['measurements'] = json_decode($product['measurements'],true);
        $product['price'] = json_decode($product['price'],true);

        //print_r($product); die();

        $p_xml = $xml->addChild('product');
        $p_xml->addAttribute('created', $product['created_at']);
        $p_xml->addAttribute('last_updated', $product['updated_at']);

        $p_xml->addChild('id', $product['id']);
        $p_xml->addChild('sku', $product['sku']);
        $p_xml->addChild('active', ($product['active'] == 1 ? 'true' : 'false'));
        $p_xml->addChild('url', 'https://123bestdeal.nl/'.$product['url']['slug']);

        $stock = $p_xml->addChild('stock');
        $stock->addChild('status', $product['stocklevel']);
        $stock->addChild('level', 100);

        $measurements = $p_xml->addChild('measurements');
        $measurements->addChild('width',$product['measurements']['width']);
        $measurements->addChild('length',$product['measurements']['length']);
        $measurements->addChild('height',$product['measurements']['height']);
        $measurements->addChild('weight',$product['measurements']['weight']);
        $measurements->addChild('min_width',$product['measurements']['minwidth']);
        $measurements->addChild('max_width',$product['measurements']['maxwidth']);
        $measurements->addChild('min_length',$product['measurements']['minlength']);
        $measurements->addChild('max_length',$product['measurements']['maxlength']);

        $images = $p_xml->addChild('images');
        foreach($product['meta'] as $image){
            if ($image['non_image'] == 1) continue;
            $i = $images->addChild('url','https://ams3.cdn.123bestdeal.nl/'.$image['url']);
            $i->addAttribute('main', ($image['main'] == 1 ? 'true' : 'false'));
            $i->addAttribute('main_ext', ($image['main_ext'] == 1 ? 'true' : 'false'));
            $i->addAttribute('sort_order', $image['sort_order']);
        }

        $shops = $p_xml->addChild('shops');
        foreach($product['shop'] as $shop){
            $shops->addChild('shop',$webshop_shops[$shop['shop_id']]['domain']);
        }

        $prices = $p_xml->addChild('prices');
        foreach($product['price'] as $shop_id => $price_info){
            $p = $prices->addChild('shop');
            $p->addCData($webshop_shops[$shop_id]['domain']);
            $p->addAttribute('price_was', $price_info['price_was']);
            $p->addAttribute('discount', $price_info['discount']);
        }

        // Now the language-specific data

        $languages = array('nl','en');

        foreach ($languages as $lang){
            $language_child = $p_xml->addChild($lang);

            $child = $language_child->addChild('title');
            @$child->addCData($product['contents'][$lang]['title']);

            $child = $language_child->addChild('title_ext');
            @$child->addCData($product['contents'][$lang]['title_external']);

            $child = $language_child->addChild('description');
            @$child->addCData($product['contents'][$lang]['description']);

            $child = $language_child->addChild('description_ext');
            @$child->addCData($product['contents'][$lang]['description_external']);

            $child = $language_child->addChild('usps');
            foreach ($product['contents'][$lang]['characteristics'] as $usp){
                $c = $child->addChild('usp');
                $c->addCData($usp);
            }

            $attributes_entries = $language_child->addChild('attributes');
            foreach ($product['attributes'] as $attribute){

                $attribute_info = $attributes[$attribute];
                $attribute_group = $attribute_groups[$attribute_info['attribute_group_id']];

                $a = $attributes_entries->addChild('attribute');
                $a->addAttribute('option', $attribute_group['name'][$lang]);
                $a->addAttribute('value', $attribute_info['name'][$lang]);
            }



        }

    }

    print_r($xml->asXML());
    fwrite($file, $xml->asXML());
    die();

    $limit+=100;

}

fwrite($file, $xml->asXML());

class SimpleXMLExtended extends SimpleXMLElement {
  public function addCData($cdata_text) {
    $node = dom_import_simplexml($this);
    $no   = $node->ownerDocument;
    $node->appendChild($no->createCDATASection($cdata_text));
  }
}
