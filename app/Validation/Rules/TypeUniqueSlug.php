<?php

namespace App\Validation\Rules;

use DB;
use Respect\Validation\Rules\AbstractRule;

class TypeUniqueSlug extends AbstractRule
{
    public function validate($input)
    {
        $type = DB::queryFirstRow("SELECT id FROM product_brand_type where JSON_EXTRACT(slug, '$." . language . "')=%s", $input);
        if ($type) return false;
        else return true;
    }
}
