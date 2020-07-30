<?php

namespace App\Validation\Rules;

use DB;
use Respect\Validation\Rules\AbstractRule;

class EmailAvailable extends AbstractRule
{
    public function validate($input)
    {
        $usernames = DB::queryFirstRow("SELECT id FROM users where email=%s", $input);
        if ($usernames) return false;
        else return true;
    }
}
