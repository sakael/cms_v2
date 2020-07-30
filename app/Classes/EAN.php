<?php

namespace App\Classes;

use DB;
use Carbon\Carbon as Carbon;

$ean =  8719828;

class EAN
{
    public $eanScope;
    public $eanScopeMax;
    
    public function __construct()
    {
        $this->eanScope = '8719828';
        $this->eanScopeMax = '8719829';
    }
     
    public function setScope($scope)
    {
        $this->eanScope = $scope;
        return 'Scope set to ' . $scope;
    }
    /**
     * retrieve ean function
     *
     * @param int $productId
     * @param int $variantId
     * @param int $typeId
     * @return string Ean number
     */
    public function retrieve($productId, $variantId, $typeId)
    {
       
        $q = DB::queryFirstRow(
            "SELECT EAN FROM EAN WHERE product_id=%i AND type_id=%i AND variation_id=%i",
            $productId,
            $typeId,
            $variantId
        );
        if ($q && $q['EAN'] != "") {
            return $q['EAN'];
        } else {
            $this->throwError(0, '200', 'EAN not found');
        }
    }
    /**
     * checkExistense function
     *
     * @param int $productId
     * @param int $variantId
     * @param int $typeId
     * @return void
     */
    public function checkExistense($productId, $variantId, $typeId)
    {
        $q = DB::queryFirstRow(
            "SELECT EAN FROM EAN WHERE product_id=%i AND type_id=%i AND variation_id=%i",
            $productId,
            $typeId,
            $variantId
        );
        if ($q && $q['EAN'] != "") {
            return array("status" => "1","EAN" => $q['EAN']);
        } else {
            return array("status" => "0");
        }
    }
    /**
     * checkEanExistense function
     *
     * @param string $ean
     * @return array json
     */
    public function checkEanExistense($ean)
    {
        $q = DB::queryFirstRow("SELECT id FROM EAN WHERE EAN=%s", $ean);
        if ($q) {
            $return = array("status" => "1","EAN" => $ean);
        } else {
            $return = $this->throwError('0', '201', 'Barcode does not exist in table');
        }
        return $return;
    }
    
    /**
     * generate EAN function
     *
     * @param int $productId
     * @param int $variantId
     * @param int $typeId
     * @return string
     */
    public function generate($productId, $variantId, $typeId)
    {
        $check = $this->checkExistense($productId, $variantId, $typeId);
        if ($check['status'] == 0) {
            $last = $this->lastEan();

            $newEAN = $this->generateFullEan($last['last'] + 1);
        
            if (substr($newEAN, 0, 7) == $this->eanScopeMax) {
                // we ran out of EAN codes
                mail('remo@123bestdeal.nl', 'EAN SCOPE ENDED', 'NO MORE EANS LEFT!');
                mail('sam@123bestdeal.nl', 'EAN SCOPE ENDED', 'NO MORE EANS LEFT!');
                die('NO MORE EANS LEFT');
                return false;
            }
            if (strlen($newEAN) == 13) {
                $check = DB::insert('EAN', array(
                    'product_id' => $productId,
                    'variation_id' => $variantId,
                    'type_id' => $typeId,
                    'EAN' => $newEAN,
                    'active' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ));
                $last = DB::queryFirstRow("SELECT id,last FROM EAN_settings WHERE `range`=%s", $this->eanScope);
                DB::update('EAN_settings', array(
                    'last' => $last['last'] + 1
                ), 'id=%i', $last['id']);

                return array('EAN' => $newEAN, 'status' => 1);
            } else {
                return $this->throwError('0', '204', 'Barcode length issue (' . $newEAN . ') for PID: ' . $productId . '(V: ' . $variantId . ',T:' . $typeId . ')');
            }
        } else {
            return $this->retrieve($productId, $variantId, $typeId);
        }
    }
    
    /**
     * Return Last EAN function
     *
     * @return int
     */
    private function lastEan()
    {
        $last = DB::queryFirstRow("SELECT last FROM EAN_settings WHERE `range`=%s", $this->eanScope);
        return $last;
    }
    /**
     * generateFullEan function
     *
     * @param int $lastId
     * @return void
     */
    private function generateFullEan($lastId)
    {
        $predigits = array(1 => '0000', 2 => '000', 3 => '00', 4 => '0',5 => '');
        return $this->eanCheckDigit($this->eanScope . $predigits[strlen($lastId)] . $lastId);
    }
    /**
     * eanCheckDigit function
     *
     * @param string $ean
     * @return int
     */
    private function eanCheckDigit($ean)
    {
        $digits = (string)$ean;
        $even_sum = $digits[1] + $digits[3] + $digits[5] + $digits[7] + $digits[9] + $digits[11];
        $even_sum_three = $even_sum * 3;
        $odd_sum = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8] + $digits[10];
        $total_sum = $even_sum_three + $odd_sum;
        $next_ten = (ceil($total_sum / 10)) * 10;
        $check_digit = $next_ten - $total_sum;
        return $digits . $check_digit;
    }
    
    /**
     * insertManual EAN function
     *
     * @param int $productId
     * @param int $variantId
     * @param string $EAN
     * @param int $typeId
     * @return array
     */
    public function insertManual($productId, $variantId, $EAN, $typeId)
    {
        //print_r('here');
        $check = $this->checkBarcodeExistense($EAN);
        
        if ($check['status'] == '0') {
            $check = DB::insert('EAN', array(
                'product_id' => $productId,
                'variation_id' => $variantId,
                'type_id' => $typeId,
                'EAN' => $EAN,
                'active' => 1,
                'created_at' => Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ));
            return array("status" => "1", "EAN" => $EAN);
        } else {
            return $this->throwError('0', '202', 'Barcode can not be added, already in system');
        }
    }
    /**
     * throwError function
     *
     * @param int $status
     * @param int $code
     * @param string $msg
     * @return array
     */
    private function throwError($status, $code, $msg)
    {
        $this->log($msg, $code);
        return array('status' => $status, 'code' => $code,'message' => $msg);
    }
    /**
     * log function
     *
     * @param string $msg
     * @param int $code
     * @return bolean
     */
    private function log($msg, $code)
    {
        $myFile = TMP_DIR . "/log.txt";
        $fh = fopen($myFile, 'a') or die("can't open file");
        fwrite($fh, date('d-m-Y H:i:s') . ' - ' . $code . ' ' . $msg . "\n");
        fclose($fh);
        return true;
    }
}
