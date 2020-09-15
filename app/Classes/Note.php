<?php

namespace App\Classes;

use DB;
use Carbon\Carbon as Carbon;
use App\Auth\Auth as Auth;

class Note
{

    public $id;
    public $user_id = 0;
    public $user_id_to = 1;
    public $product_id = 0;
    public $order_id = 0;
    public $note = '';
    public $status = 0;
    protected static $table = 'notes';
    /**
     * Get all notes function
     *
     * @return array
     */
    public static function All()
    {
        return DB::query("SELECT * from " . self::$table);
    }

    public static function From($id = 0)
    {
        return DB::query("SELECT notes.id,notes.user_id,notes.user_id_to,notes.order_id,notes.product_id,notes.note,notes.status,notes.created_at,notes.updated_at,user.id as userid, user.name  from " . self::$table . " as notes
         left join users as user on user.id = notes.user_id_to
         where user_id=%i order by notes.created_at DESC", $id);
    }

    public static function To($id = 0)
    {
        return DB::query("SELECT notes.id,notes.user_id,notes.user_id_to,notes.order_id,notes.product_id,notes.note,notes.status,notes.created_at,notes.updated_at,user.id as userid, user.name  from " . self::$table . " as notes
        left join users as user on user.id = notes.user_id
        where user_id_to=%i order by notes.created_at DESC", $id);
    }

    public static function Find($id)
    {
        $query = DB::query("SELECT * from " . self::$table . " where id=%i limit 1", $id);
        if (count($query) > 0) {
            $result = $query[0];
        } else {
            $result = $query;
        }
        return $result;
    }


    public function Update()
    {
        $check = DB::update(self::$table, array(['user_id' => $this->user_id, 'user_id_to' => $this->user_id_to, 'product_id' => $this->product_id, 'order_id' => $this->order_id, 'note' => $this->note, 'status' => $this->status, 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')]), 'id=%i', $this->id);
        if ($check) return true;
        else false;
    }

    public function Create()
    {

        $check = DB::insert(self::$table, array(['user_id' => $this->user_id, 'user_id_to' => $this->user_id_to, 'product_id' => $this->product_id, 'order_id' => $this->order_id, 'note' => $this->note, 'status' => $this->status, 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]));

        if ($check) {
            $this->id = DB::insertId();
            return $this->id;
        } else false;
    }

    public function CountNotDone()
    {
        if (Auth::user_id()) {
            $count = DB::queryFirstRow("SELECT  COUNT(" . self::$table . ".id) as count from " . self::$table . " where " . self::$table . ".status =0 and " . self::$table . ".user_id_to=%i", Auth::user_id());
            return $count['count'];
        }
        return 0;
    }
}
