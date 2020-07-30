<?php

namespace App\Validation\Rules;

use DB;
use Respect\Validation\Rules\AbstractRule;

class MatchesPassword extends AbstractRule
{
    protected $password;
    protected $newPassword;

    public function __construct($password, $newPassword)
    {
        $this->password = $password;
        $this->newPassword = $newPassword;
    }

    public function validate($input)
    {
        if ($this->newPassword == $this->password['password']) return true;
        else return false;
    }
}
