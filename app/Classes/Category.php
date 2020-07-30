<?php

namespace App\Classes;

use DB;
use App\Classes\Event;
use Carbon\Carbon as Carbon;

class Category
{
    public $id;
    public $name;
    public $active = 0;
    public $contents = [];

    protected static $table = 'category';
    public static $thumb = true;
    public static $thumbs = [
        ['name' => '123bestdeal', 'w' => '173', 'h' => '130'],
        ['name' => 'bol', 'w' => '60', 'h' => '50']
    ];
    public static $imageFolder = 'categories';

    private $event;

    /**
     * constructer function
     */
    public function __construct()
    {
        $this->event = new Event();
    }

    /**
     * Get All fcategories unction
     *
     * @return array
     */
    public static function All()
    {
        return DB::query("SELECT id,photo, JSON_UNQUOTE(JSON_EXTRACT(name, '$." . language . "')) as name,active,created_at FROM " . self::$table . ' order by id');
    }

    public static function Find($id)
    {
        return DB::queryFirstRow("SELECT id,JSON_UNQUOTE(JSON_EXTRACT(name, '$." . language . "')) as name,JSON_UNQUOTE(JSON_EXTRACT(contents, '$." . language . "')) as contents,JSON_UNQUOTE(JSON_EXTRACT(" . self::$table . ".slug, '$." . language . "')) as slug,active,photo FROM " . self::$table . ' where id=%i', $id);
    }
    /**
     * Update function
     *
     * @return void
     */
    public function Update()
    {
        $category = DB::queryFirstRow('SELECT name,contents,slug,photo  FROM ' . self::$table . ' where id=%i', $this->id);
        $contents = json_decode($category['contents'], true);
        $slugs = json_decode($category['slug'], true);
        $names = json_decode($category['name'], true);
        $slugs[language] = $this->slug;
        $names[language] = $this->name;
        foreach ($this->contents as $key => $content) {
            $contents[language][$key] = $content;
        }
        $photo = '';
        if ($this->photo != '') {
            $photo = Upload('categories', $this->id, $this->photo);
            $tmp = ['id' => $this->id, 'photo' => $photo, 'name' => 'categories'];
            Thumbs($tmp, $this->photo, self::$thumbs);
        }
        if (!$photo || $photo == '') {
            if ($category['photo'] && $category['photo'] != '') {
                $photo = $category['photo'];
            } else {
                $photo = '';
            }
        } elseif ($category['photo'] && $category['photo'] != '') {
            RemoveFromS3Bucket($category['photo'], 'categories', $this->id, self::$thumbs);
        }
        $check = DB::update(self::$table, [['contents' => json_encode($contents), 'slug' => json_encode($slugs), 'name' => json_encode($names), 'active' => $this->active, 'photo' => $photo, 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')]], 'id=%i', $this->id);
        if ($check) {
            $this->event->categoryUpdated($this->id, $this->name);
            return true;
        } else {
            false;
        }
    }

    public function Create()
    {
        $slugs = [];
        $slugs['nl'] = $this->slug;
        $content = [];
        $contents['nl'] = $this->contents;
        $names = [];
        $names['nl'] = $this->name;

        $check = DB::insert(self::$table, [[
            'contents' => json_encode($contents),
            'name' => json_encode($names), 'slug' => json_encode($slugs),
            'active' => $this->active, 'photo' => '',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]]);

        if ($check) {
            $this->id = DB::insertId();
            if ($this->photo != '') {
                $photo = Upload('categories', $this->id, $this->photo);
                $tmp = ['id' => $this->id, 'photo' => $photo, 'name' => 'categories'];
                Thumbs($tmp, $this->photo, self::$thumbs);
            }
            if (!$photo) {
                $photo = '';
            }
            DB::update(self::$table, [['photo' => $photo]], 'id=%i', $this->id);
            return $this->id;
        } else {
            false;
        }
    }

    public function getTableName()
    {
        return self::$table;
    }
}
