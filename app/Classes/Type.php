<?php

namespace App\Classes;

use DB;
use Carbon\Carbon as Carbon;
use App\Classes\Event;
use Nextimage\Nextimage\Resize;

class Type
{
    public $id;
    public $name;
    public $product_brand_id;
    public $main_category_id = 0;
    public $contents = [];
    public $categoriesContents = [];
    public $measurements = [];
    public $kb_options;
    public $staff_pick;
    public $active_menu = 0;
    public $active_feed = 0;
    public $popular_list = 0;
    public $photo = '';
    public $slug = [];
    public static $thumb = true;
    public static $thumbs = [
        ['name' => '123bestdeal', 'w' => '173', 'h' => '130']
    ];
    public static $imageFolder = 'types';
    protected static $table = 'product_brand_type';
    protected static $brand_table = 'product_brand';
    protected static $product_table = 'product';

    private $event;

    /**
     * contructer function
     */
    public function __construct()
    {
        $this->event = new Event();
    }

    public static function All()
    {
        return DB::query('SELECT ' . self::$table . '.id,' . self::$table . '.product_brand_id,' . self::$table . '.main_category_id,' . self::$table . '.name,
        JSON_UNQUOTE(JSON_EXTRACT(' . self::$table . ".categories_contents, '$." . language . "')) as categories_contents,
        JSON_UNQUOTE(JSON_EXTRACT(" . self::$table . ".slug, '$." . language . "')) as slug,
        " . self::$table . '.active_menu,' . self::$table . '.active_feed,' . self::$table . '.staff_pick,' . self::$table . '.kb_options,' . self::$table . '.created_at,
        ' . self::$table . '.updated_at,' . self::$table . '.popular_list,' . self::$table . '.photo,' . self::$brand_table . '.name as brand_name FROM ' . self::$table . '
        LEFT JOIN ' . self::$brand_table . ' ON ' . self::$brand_table . '.id = ' . self::$table . '.product_brand_id');
    }

    public static function Find($id)
    {
        return DB::queryFirstRow('SELECT ' . self::$table . '.id,product_brand_id,main_category_id,' . self::$table . '.name,JSON_UNQUOTE(JSON_EXTRACT(' . self::$table . ".contents, '$." . language . "')) as contents,
        JSON_UNQUOTE(JSON_EXTRACT(categories_contents, '$." . language . "')) as categories_contents,JSON_UNQUOTE(JSON_EXTRACT(" . self::$table . ".slug, '$." . language . "')) as slug,
        JSON_UNQUOTE(measurements) as measurements,active_menu,staff_pick,kb_options,
        active_feed," . self::$table . '.created_at,' . self::$table . '.updated_at,popular_list,' . self::$table . ".photo,
        main_category.name->>'$." . language . "' as main_category_name FROM " . self::$table . '
        left join main_category on main_category.id= ' . self::$table . '.main_category_id
        where ' . self::$table . '.id=%i', $id);
    }

    public function Update()
    {
        $Type = DB::queryFirstRow('SELECT id,product_brand_id,contents,categories_contents,photo,measurements,slug  FROM ' . self::$table . ' where id=%i', $this->id);
        $contents = json_decode($Type['contents'], true);
        $measurements = json_decode($Type['measurements'], true);
        $slugs = json_decode($Type['slug'], true);
        $slugs[language] = $this->slug;
        foreach ($this->contents as $key => $content) {
            $contents[language][$key] = $content;
        }
        foreach ($this->measurements as $key => $measurement) {
            $measurements[$key] = $measurement;
        }
        $photo = '';
        if ($this->photo != '') {
            $photo = Upload('types', $this->id, $this->photo);
            $tmp = ['id' => $Type['id'], 'photo' => $photo, 'name' => 'types'];
            Thumbs($tmp, $this->photo, self::$thumbs);
        }
        if (!$photo || $photo == '') {
            if ($Type['photo'] && $Type['photo'] != '') {
                $photo = $Type['photo'];
            } else {
                $photo = '';
            }
        } elseif ($Type['photo'] && $Type['photo'] != '') {
            RemoveFromS3Bucket($Type['photo'], 'types', $this->id, self::$thumbs);
        }
        $check = DB::update(self::$table, [[
            'product_brand_id' => $this->product_brand_id, 'contents' => json_encode($contents),
            'slug' => json_encode($slugs), 'measurements' => json_encode($measurements), 'name' => $this->name, 'active_menu' => $this->active_menu,
            'active_feed' => $this->active_feed, 'popular_list' => $this->popular_list, 'kb_options' => $this->kb_options, 'staff_pick' => $this->staff_pick, 'photo' => $photo, 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]], 'id=%i', $this->id);
        if ($check) {
            if ($Type['product_brand_id'] != $this->product_brand_id) {
                $brands = array($Type['product_brand_id'], $this->product_brand_id);
                $this->event->typeUpdated($this->id, $this->slug, $brands, 'brand');
            } else {
                $this->event->typeUpdated($this->id, $this->slug, $this->product_brand_id);
            }
            return true;
        } else {
            false;
        }
    }

    public function Create()
    {
        $content = [];
        $slugs = [];
        $contents['nl'] = $this->contents;
        $slugs['nl'] = $this->slug;

        $check = DB::insert(self::$table, [[
            'product_brand_id' => $this->product_brand_id, 'main_category_id' => $this->main_category_id, 'contents' => json_encode($contents), 'categories_contents' => json_encode($this->categories_contents), 'slug' => json_encode($slugs), 'measurements' => json_encode($this->measurements), 'name' => $this->name, 'active_menu' => $this->active_menu,
            'active_feed' => $this->active_feed, 'popular_list' => $this->popular_list, 'kb_options' => $this->kb_options, 'staff_pick' => $this->staff_pick, 'photo' => '', 'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]]);
        if ($check) {
            $this->id = DB::insertId();
            if ($this->photo != '') {
                $photo = Upload('types', $this->id, $this->photo);
                $tmp = ['id' => $this->id, 'photo' => $photo, 'name' => 'types'];
                Thumbs($tmp, $this->photo, self::$thumbs);
            }
            if (!$photo) {
                $photo = '';
            }
            DB::update(self::$table, [['photo' => $photo]], 'id=%i', $this->id);
            //event
            $this->event->typeCreated($this->id, $this->product_brand_id);
            return $this->id;
        } else {
            false;
        }
    }

    public function UpdateCategories($type = 'contents', $cat_id = null, $photo = null)
    {
        $Type = DB::queryFirstRow('SELECT categories_contents  FROM ' . self::$table . ' where id=%i', $this->id);
        $categoriesContents = json_decode($Type['categories_contents'], true);
        if ($type == 'image' && $cat_id != null && $photo != null) {
            RemoveFromS3Bucket($categoriesContents['nl'][$cat_id]['image'], 'type_categories', $this->id . '_' . $cat_id, self::$thumbs);
            $categoriesContents['nl'][$cat_id]['image'] = $photo;
            $categoriesContents['en'][$cat_id]['image'] = $photo;
            $check = DB::update(self::$table, [['categories_contents' => json_encode($categoriesContents), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')]], 'id=%i', $this->id);
            if ($check) {
                return true;
            } else {
                false;
            }
        } else {
            foreach ($this->categoriesContents as $key => $categoriesContent) {
                if ($key == 'show') {
                    continue;
                }
                $categoriesContents[language][$this->categoriesContents['id']][$key] = $categoriesContent;
            }
            $check = DB::update(self::$table, [['categories_contents' => json_encode($categoriesContents), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')]], 'id=%i', $this->id);
            if ($check) {
                //event
                $this->event->typeUpdated($this->id, '', '', 'categories');
                return true;
            } else {
                false;
            }
        }
    }

    public function getTableName()
    {
        return self::$table;
    }
}
