<?php

namespace App\Classes;

use DB;
use App\Classes\Event;
use Carbon\Carbon as Carbon;
use Nextimage\Nextimage\Resize;

class Brand
{

    public $id;
    public $name;
    public $contents = array();
    public $active_menu = 0;
    public $active_feed = 0;
    public $popular_list = 0;
    public $photo = '';
    public $slug = array();
    protected static $table = 'product_brand';
    protected static $type_table = 'product_brand_type';

    public static $thumb=true;
    /**
     * $thumbs variable
     *
     * @var array
     */
    public static $thumbs=array(
        array('name'=>'123bestdeal','w'=>'173','h'=>'130')
    );
    public static $imageFolder='brands';

    private $event;

    /**
     * contructer function
     */
    public function __construct()
    {
        $this->event = new Event();
    }

    /**
     * Get All Brands with types function
     *
     * @return array
     */
    static function All()
    {
        $brands = DB::query("SELECT " . self::$table . ".id," . self::$table . ".name, JSON_UNQUOTE(JSON_EXTRACT(" . self::$table . ".contents, '$." . language . "')) as contents,
        JSON_UNQUOTE(JSON_EXTRACT(" . self::$table . ".slug, '$." . language . "')) as slug," . self::$table . ".active_menu," . self::$table . ".active_feed," . self::$table . ".created_at,
        " . self::$table . ".updated_at," . self::$table . ".popular_list," . self::$table . ".photo , COUNT(" . self::$table . ".id) types_count
FROM " . self::$table . " LEFT JOIN
     " . self::$type_table . " ON " . self::$type_table . ".product_brand_id = " . self::$table . ".id
GROUP BY " . self::$table . ".id");
        return $brands;
    }

    static function Find($id)
    {
        return DB::queryFirstRow("SELECT id,name,JSON_UNQUOTE(JSON_EXTRACT(contents, '$." . language . "')) as contents,JSON_UNQUOTE(JSON_EXTRACT(" . self::$table . ".slug, '$." . language . "')) as slug,active_menu,active_feed,created_at,updated_at,popular_list,photo FROM " . self::$table . " where id=%i", $id);
    }

    static function AllTypesIn($id)
    {
        return DB::query("SELECT id, name,active_menu,active_feed,active_menu,popular_list,updated_at,created_at from " . self::$type_table . " where product_brand_id =%i order by name", $id);
    }

    public function Update()
    {
        $brand = DB::queryFirstRow("SELECT id,contents,slug,photo  FROM " . self::$table . " where id=%i", $this->id);
        $contents = json_decode($brand['contents'], true);
        $slugs = json_decode($brand['slug'], true);
        $slugs[language] = $this->slug;
        foreach ($this->contents as $key => $content) {
            $contents[language][$key] = $content;
        }
        $photo= '';
        if ($this->photo != '') {
            $photo = Upload('brands', $this->id, $this->photo);
            $tmpBrand=array('id'=>$brand['id'],'photo'=>$photo,'name'=>'brands');
            Thumbs($tmpBrand, $this->photo,self::$thumbs);
        }
        if (!$photo || $photo == '') {
            if ($brand['photo'] && $brand['photo'] != '') $photo = $brand['photo'];
            else $photo = '';
        } elseif ($brand['photo'] && $brand['photo'] != '') {
            RemoveFromS3Bucket($brand['photo'],'brands',$this->id,self::$thumbs);
        }
        $check = DB::update(self::$table, array(['contents' => json_encode($contents), 'slug' => json_encode($slugs), 'name' => $this->name, 'active_menu' => $this->active_menu, 'active_feed' => $this->active_feed, 'popular_list' => $this->popular_list, 'photo' => $photo, 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')]), 'id=%i', $this->id);
        if ($check) {
            $this->event->brandUpdated($this->id, $this->slug);
            return true;
        }
        else {
            false;
        }
    }
    public function Create()
    {
        $slugs = array();
        $slugs['nl'] = $this->slug;
        $content = array();
        $contents['nl'] = $this->contents;

        $check = DB::insert(self::$table, array(['contents' => json_encode($contents), 'slug' => json_encode($slugs), 'name' => $this->name, 'active_menu' => $this->active_menu, 'active_feed' => $this->active_feed, 'popular_list' => $this->popular_list, 'photo' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]));

        if ($check) {
            $this->id = DB::insertId();
            if ($this->photo != '') {
                $photo = Upload('brands', $this->id, $this->photo);
                $tmpBrand=array('id'=>$this->id,'photo'=>$photo,'name'=>'brands');
                Thumbs($tmpBrand, $this->photo,self::$thumbs);
            }
            if (!$photo) {
                $photo = '';
            }
            DB::update(self::$table, array(['photo' => $photo]), 'id=%i', $this->id);
            return $this->id;
        } else false;
    }
    public function getTableName()
    {
        return self::$table;
    }
}
