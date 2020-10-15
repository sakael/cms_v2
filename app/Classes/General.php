<?php

namespace App\Classes;

use DB;

class General
{
  /**
   * Get all shops function
   *
   * @return array
   */
  public static function getShops()
  {
    return DB::query("SELECT * FROM shop");
  }
  public static function getStatus()
  {
    $status = DB::query('select id,title,template_id from order_status order by title');
    $tempStatus = array();
    foreach ($status as $key => $value) {
      $tempStatus[$value['id']] = $value;
    }
    return $tempStatus;
  }
  public function getMainCategories()
  {
    return DB::query("select id,name->>'$." . language . "' as name from main_category order by name");
  }


}
