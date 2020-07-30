<?php

namespace App\Classes;

use DB;

class Attribute
{

    public $id;
    public $name;
    public $attribute_group_id;

    protected static $table = 'attribute';
    protected static $attribute_group_table = 'attribute_group';

    /**
     * Get All attributes function
     *
     * @return array Result of all attributes
     */
    static function All()
    {
        return DB::query("SELECT " . self::$table . ".id, JSON_UNQUOTE(JSON_EXTRACT(" . self::$table . ".name, '$." . language . "'))  as name,JSON_UNQUOTE(JSON_EXTRACT(" . self::$attribute_group_table . ".name, '$." . language . "')) as attribute_group_name FROM " . self::$table . " LEFT JOIN " . self::$attribute_group_table . " ON " . self::$table . ".attribute_group_id = " . self::$attribute_group_table . ".id order by name");
    }
    /**
     * Get all attribute in a group function
     *
     * @param int $attribbute_group_id
     * @return array
     */
    static function AllinAttributeGroup($attribbute_group_id = '')
    {
        return DB::query("SELECT " . self::$table . ".id, JSON_UNQUOTE(JSON_EXTRACT(" . self::$table . ".name, '$." . language . "'))  as name,JSON_UNQUOTE(JSON_EXTRACT(" . self::$attribute_group_table . ".name, '$." . language . "')) as attribute_group_name FROM " . self::$table . " LEFT JOIN " . self::$attribute_group_table . " ON " . self::$table . ".attribute_group_id = " . self::$attribute_group_table . ".id where attribute_group_id =%i order by name", $attribbute_group_id);
    }
    /**
     * Find a attribute from id function
     *
     * @param id $id
     * @return array
     */
    static function Find($id)
    {
        return DB::queryFirstRow("SELECT id,JSON_UNQUOTE(JSON_EXTRACT(name, '$." . language . "')) as name,attribute_group_id FROM " . self::$table . " where id=%i", $id);
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
        $check = DB::update(self::$table, array('name' => json_encode($name), 'attribute_group_id' => $this->attribute_group_id), 'id=%i', $this->id);
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
        $check = DB::insert(self::$table, array('name' => json_encode($name), 'attribute_group_id' => $this->attribute_group_id));

        if ($check) return DB::insertId();
        else false;
    }
}
