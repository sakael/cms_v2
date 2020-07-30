<?php

namespace App\Validation\Rules;

use DB;
use Respect\Validation\Rules\AbstractRule;

class CategoryUniqueSlug extends AbstractRule
{
    public function validate($input)
    {
        $category = DB::queryFirstRow("SELECT id FROM category where JSON_EXTRACT(slug, '$." . language . "')=%s", $input);
        if ($category) return false;
        else return true;
    }
}
