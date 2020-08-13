<?php

use Nextimage\Nextimage\Resize;

/**
 * GetLang function
 *
 * @return string
 */
function GetLang()
{
    return LANGUAGE;
}

/**
 * Slugify function
 *
 * @param [string] $text url
 *
 * @return string
 */
function Slugify($text)
{
    // replace non letter or digits by -
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

    // trim
    $text = trim($text, '-');

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // lowercase
    $text = strtolower($text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

if (!function_exists('dd')) {
    /**
     * Die and echo function function
     *
     * @return array
     */
    function dd()
    {
        $args = func_get_args();
        call_user_func_array('dump', $args);
        die();
    }
}

/**
 * Undocumented function
 *
 * @return void
 */
function LangToDefault()
{
    setcookie('language', 'nl', time() + 63072000, '/');
}

/**
 * Thumbs Product function
 *
 * @param array $model
 * @param string $photo
 * @param string $thumbs
 * @return void
 */
function Thumbs($model, $photo, $thumbs)
{
    if (count($thumbs) > 0 && $model) {
        $imageName = pathinfo($model['photo'], PATHINFO_FILENAME);
        $ext = pathinfo($model['photo'], PATHINFO_EXTENSION);
        $Resize = new Resize($photo->file, $ext);
        foreach ($thumbs as $key => $thumb) {
            try {
                if (isset($thumb['type']) && $thumb['type'] != '') {
                    switch ($thumb['type']) {
                        case 'maxwidth':
                            $Resize->resizeTo($thumb['w'], $thumb['h'], 'maxwidth');
                            break;
                        case 'maxheight':
                            $Resize->resizeTo($thumb['w'], $thumb['h'], 'maxheight');
                            break;
                        case 'exact':
                            $Resize->resizeTo($thumb['w'], $thumb['h'], 'exact');
                            break;
                    }
                } else {
                    $Resize->resizeTo($thumb['w'], $thumb['h']);
                }
                $path = TMP_DIR . $model['name'] . '_' . $model['id'] . '_' . $thumb['name'] . '.' . $ext;
                $Resize->saveImage($path);
                if (!UploadThumb($model['name'], $model['id'], $path, $imageName . '_' . $thumb['name'], $ext)) {
                    return false;
                }
            } catch (Exception $e) {
                echo 'Image with error :  ' . $e->getMessage();
            }
        }
        return true;
    }
}

/**
 * UndocumUploadThumbented function
 *
 * @param array $model
 * @param int $model_id
 * @param file $file
 * @param string $imageName
 * @param string $ext
 * @return void
 */
function UploadThumb($model, $model_id, $file, $imageName, $ext)
{
    $fileName = $imageName . '.' . $ext;
    $s3_location = SendToS3BucketDigital($model, $model_id . '/thumbs', $fileName, $file, $ext);
    if (file_exists($file)) {
        unlink($file);
    }
    if ($s3_location) {
        return $s3_location;
    }
    return false;
}

/**
 * Upload Images function
 *
 * @param array $model
 * @param int $model_id
 * @param file $file
 * @return void
 */
function Upload($model, $model_id, $file)
{
    $ext = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
    $fileName = md5(date("U") . $file->file) . '.' . $ext;
    $s3_location = SendToS3BucketDigital($model, $model_id, $fileName, $file->file, $ext);
    if ($s3_location) {
        return $s3_location;
    }
    return false;
}

/**
 * SendToS3BucketDigital function
 *
 * @param array $model
 * @param int $model_id
 * @param string $fileName
 * @param string $local_file
 * @param string $ext
 * @return void
 */
function SendToS3BucketDigital($model, $model_id, $fileName, $local_file, $ext)
{
    $r = $GLOBALS['s3']->putObject(
        array(
        'Bucket' => 'cms',
        'Key' => $model . '/' . $model_id . '/' . $fileName,
        'SourceFile' => $local_file,
        'ACL' => 'public-read'
        )
    );
    return  $model . '/' . $model_id . '/' . $fileName;
}

/**
 * RemoveFromS3Bucket function
 *
 * @param string $filename
 * @param boolean $model
 * @param boolean $modelId
 * @param boolean $modelThumbs
 * @return void
 */
function RemoveFromS3Bucket($filename, $model = false, $modelId = false, $modelThumbs = false)
{
    $r = true;
    if ($filename) {
        $info = $GLOBALS['s3']->doesObjectExist('cms', $filename);
        if ($info) {
            $r = $GLOBALS['s3']->deleteObject(array(
                'Bucket' => 'cms',
                'Key' => $filename
            ));
        }
    }
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if ($model != false && $modelThumbs != false && ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'gif' || $ext == 'png')) {
        $imageName = pathinfo($filename, PATHINFO_FILENAME);
        foreach ($modelThumbs as $key => $modelThumb) {
            $file = $model . '/' . $modelId . '/' . 'thumbs/' . $imageName . '_' . $modelThumb['name'] . '.' . $ext;

            $info = $GLOBALS['s3']->doesObjectExist('cms', $file);
            if ($info) {
                $GLOBALS['s3']->deleteObject(array(
                'Bucket' => 'cms',
                'Key' => $file
                ));
            }
        }
    }
    if ($r) {
        return true;
    } else {
        return false;
    }
}
/**
 * isAjax check if the request is ajax request function
 *
 * @return boolean
 */
function isAjax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) and
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * payAfterMSP change the status of payafter in MSP function
 *
 * @param int $transaction_id
 * @param int $orderID
 * @param string $zipcode
 * @param string $shZipcode
 * @param string $shCountry
 * @return void
 */
function payAfterMSP($transaction_id = null, $orderID, $zipcode = '', $shZipcode = '', $shCountry = '')
{
    //autoload
    require_once("/data/www/i12cover.nl/public_html/home/inc/msp/API/Autoloader.php");
    //load config
    require_once("/data/www/i12cover.nl/public_html/home/inc/msp/API/config/config.php");

    $msp = new \MultiSafepayAPI\Client;

    $msp->setApiKey(API_KEY);
    $msp->setApiUrl(API_URL);

    $endpoint = 'orders/' . $transaction_id;

    $track = mysql_query("select `id`,`order`,`barcode` from 123_selektvracht WHERE `order`=" . $orderID . " order by id DESC limit 1;");
    $track = mysql_fetch_assoc($track);
    if ($track) {
        $tracktrace_code = $track['barcode'];
        if ($shZipcode != '' && $shCountry != '') {
            $zipcode = $shZipcode;
        } else {
            $zipcode = $zipcode;
        }
        $url = "https://www.dhlparcel.nl/nl/volg-uw-zending?tc=" . $tracktrace_code . "&pc=" . $zipcode . "&lc=nl-NL";
        try {
            $order = $msp->orders->patch(
                array(
                "status" => 'shipped',
                "tracktrace_code" => $tracktrace_code,
                "carrier" => 'DHL',
                "tracktrace_url" => $url,
                "ship_date" => date('Y-m-d H:i:s'),
                "reason" => 'Shipped'
                ),
                $endpoint
            );
        } catch (Exception $e) {
            echo "Error " . htmlspecialchars($e->getMessage());
        }
    }
}
/**
 * GenerateThumbs function
 *
 * @param array $model
 * @param string $photo
 * @param array $thumbs
 * @return void
 */
function GenerateThumbs($model, $photo, $thumbs)
{
    $photo = array('file' => IMAGE_PATH . '/' . $photo);
    $check= Thumbs($model, (object) $photo, $thumbs);
    if ($check) {
        echo("\033[32m  Generated: ".$photo['file']." \033\n");
        return true;
    } else {
        return false;
    }
}
/**
 * deleteFolders function
 *
 * @param array $models
 * @param string $modelName
 * @param string $thumbName
 * @return void
 */
function deleteFolders($models, $modelName, $thumbName='')
{
    foreach ($models as $model) {
        if (is_array($model) && isset($model['id'])) {
            $model=$model['id'];
        }
        $folder=$modelName . '/' . $model . '/thumbs';
        $allFilesDeletedSuccess = true;
        $results = $GLOBALS['s3']->getPaginator('ListObjects', array(
      'Bucket' => 'cms',
      'Prefix' => $folder
    ));
        foreach ($results as $result) {
            if (empty($result['Contents'])) {
                continue;
            }
            foreach ($result['Contents'] as $object) {
                if ($thumbName=='') {
                    if (!RemoveFromS3Bucket($object['Key'])) {
                        $allFilesDeletedSuccess = false;
                    }
                } elseif (strpos($object['Key'], $thumbName) !== false) {
                    if (!RemoveFromS3Bucket($object['Key'])) {
                        $allFilesDeletedSuccess = false;
                    }
                }
            }
        }
    }
    if ($allFilesDeletedSuccess) {
        return true;
    } else {
        return false;
    }
}

/**
 * Record updating product status function
 *
 * @param int $productId
 * @param int $newStatus
 * @return void
 */
function updateProductStockStatus($productId, $newStatus, $oldStatus)
{
    if ($oldStatus != $newStatus) {
        DB::insert(
            'product_stock_status_change',
            [
            'product_id' => $productId, 'user_id' => \App\Auth\Auth::user_id(), 'old_state' => $oldStatus,
            'new_state' => $newStatus, 'created_at' => Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ]
        );
    }
}

/**
 * GenerateInvoice for orders function
 *
 * @param int $orderId order id should be given
 * @param int $shopId  shop id should be given
 *
 * @return string
 */
function generateInvoice($orderId = '', $shopId = '')
{
    if ($orderId == '' || $shopId == '') {
        return false;
    }
    //get invoice number
    $invoice = DB::queryFirstRow(
        'select orderpre,last_invoice from invoices 
        where shop_id=%i',
        $shopId
    );
    if ($invoice) {
        $invoice['last_invoice']++;
        DB::update(
            'invoices',
            array('last_invoice' => $invoice['last_invoice']),
            'shop_id=%i',
            $shopId
        );
        $invoice = $invoice['orderpre'] . $invoice['last_invoice'];
    } else {
        $invoice = '';
    }
    return $invoice;
}

/**
 * Generate EAN for the new variation function
 *
 * @param int $variatonId
 * @param int $productId
 * @return int
 */
function gereateEanForVariation($variatonId, $productId)
{
    //select all types of this product
    $types = DB::query('select product_brand_type_id from product_child where product_id=%i', $productId);
    foreach ($types as $type) {
        $ean = new App\Classes\EAN();
        $return = $ean->generate($productId, $variatonId, $type['product_brand_type_id']);
        if (!$return) {
            return $response->withJson([
                'status' => 'false', 'msg' => 'EAN wordt niet gegenereerd voor Product:' . $productId . ', Variant:' . $variatonId
                ]);
        }
    }
}

/**
 * Generate EAN for the new type function
 *
 * @param int $typeId
 * @param int $productId
 * @return int
 */
function gereateEanForType($typeId, $productId)
{
    //select all variations of this product
    $variations = DB::query('select id from product_variation where product_id=%i', $productId);
    foreach ($variations as $variation) {
        $ean = new App\Classes\EAN();
        $return = $ean->generate($productId, $variation['id'], $typeId);
        if (!$return) {
            return $response->withJson([
                'status' => 'false', 'msg' => 'EAN wordt niet gegenereerd voor Product:' . $productId . ', Variant:' . $variatonId
                ]);
        }
    }
}
/**
 * Undocumented function
 *
 * @param [string] $image url
 * @param [string] $thumb thumb name
 * @return string image url
 */
function getThumb($image, $thumb)
{
    $path=dirname($image);
    $imageName = pathinfo($image, PATHINFO_FILENAME);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $imageThumb=$path.'/thumbs/'.$imageName.'_'.$thumb.'.'.$ext;
    $check = $GLOBALS['s3']->doesObjectExist('cms', $imageThumb);
    if (!$check) {
        return $image;
    }
    return $imageThumb;
}
