<?php

namespace App\Validation\Rules;

use DB;
use Respect\Validation\Rules\AbstractRule;

class BrandUniqueSlug extends AbstractRule
{
    public function validate($input)
    {
        $brand = DB::queryFirstRow("SELECT id FROM product_brand where JSON_EXTRACT(slug, '$." . language . "')=%s", $input);
        if ($brand) return false;
        else return true;
    }
}
