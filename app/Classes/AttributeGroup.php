<?php

namespace App\Classes;

use DB;

class AttributeGroup
{

    public $id;
    public $name;
    public $multiselect;

    protected static $table = 'attribute_group';
    protected static $attribute_table = 'attribute';
    /**
     * Get all attributeGroups with attributes function
     *
     * @return array
     */
    static function All()
    {
        return DB::query("SELECT " . self::$table . ".id, JSON_UNQUOTE(JSON_EXTRACT(" . self::$table . ".name, '$." . language . "')) as name," . self::$table . ".multiselect , COUNT(" . self::$table . ".id) attributes_count
FROM " . self::$table . " LEFT JOIN
     " . self::$attribute_table . " ON " . self::$attribute_table . ".attribute_group_id = " . self::$table . ".id
GROUP BY " . self::$table . ".id");
    }
    /**
     * Find an attributeGroup by Id function
     *
     * @param int $id
     * @return array
     */
    static function Find($id)
    {
        return DB::queryFirstRow("SELECT id,JSON_UNQUOTE(JSON_EXTRACT(name, '$." . language . "')) as name,multiselect FROM " . self::$table . " where id=%i", $id);
    }
    /**
     * Update function
     *
     * @return bool
     */
    public function Update()
    {
        $AttributeGroup = DB::queryFirstRow("SELECT name  FROM " . self::$table . " where id=%i", $this->id);
        $name = json_decode($AttributeGroup['name'], true);
        $name[language] = $this->name;
        if (!$this->multiselect) $this->multiselect = 0;
        $check = DB::update(self::$table, array('name' => json_encode($name), 'multiselect' => $this->multiselect), 'id=%i', $this->id);
        if ($check) return true;
        else false;
    }
    /**
     * Create function
     *
     * @return bool
     */
    public function Create()
    {
        $name = array();
        $name['nl'] = $this->name;
        $check = DB::insert(self::$table, array('name' => json_encode($name), 'multiselect' => $this->multiselect));

        if ($check) return DB::insertId();
        else false;
    }
}
