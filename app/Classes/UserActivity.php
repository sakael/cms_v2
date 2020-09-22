<?php

namespace App\Classes;

use DB;
use App\Auth\Auth as Auth;
use Carbon\Carbon as Carbon;

class UserActivity
{

    protected static $table = 'activity_log';
    protected static $tableOrderStatus = 'order_status_changes';

    static function Record($task = 'default', $subject_id, $subject_type)
    {
        DB::insert(self::$table, array(
            'task' => $task,
            'subject_id' => $subject_id,
            'subject_type' => $subject_type,
            'user_id' => Auth::user_id(),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ));
    }
    static function RecordOrderStatus($beforeStatus, $afterStatus, $orderId)
    {
        DB::insert(self::$tableOrderStatus, array(
            'order_id' => $task,
            'status_id_before' => $subject_id,
            'status_id_after' => $subject_type,
            'user_id' => Auth::user_id(),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ));
    }

    static function All($subject_type = NULL, $subject_id = NULL, $task = NULL)
    {
        $variables = '';
        $query = "Select " . self::$table . ".*,users.name from " . self::$table . "
        left join users on users.id= " . self::$table . ".user_id
        ";
        if ($task != NULL) {
            $query .= ' where task =%s';
        } else {
            $query .= ' where task !=%s';
        }

        if ($subject_id != NULL) {
            $query .= ' and subject_id =%i';
        } else {
            $query .= ' where subject_id !=%i';
        }

        if ($subject_type != NULL) {
            $query .= ' and subject_type =%s';
        } else {
            $query .= ' where subject_type !=%s';
        }

        return DB::query($query . ' Order by id Desc', $task, $subject_id, $subject_type);
    }

    static function AllOrderChanges($orderId)
    {
        $variables = '';
        $query = "Select " . self::$tableOrderStatus . ".*,users.name from " . self::$tableOrderStatus . "
        left join users on users.id= " . self::$tableOrderStatus . ".user_id
        ";
        return DB::query($query . 'where order_id = %i Order by created_at Desc', $orderId);
    }
}
