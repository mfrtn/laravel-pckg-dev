<?php

namespace mfrtn\Press\Fields;

use Carbon\Carbon;
use mfrtn\Press\MarkdownParser;

class Body
{
    public static function process($type, $value)
    {
        return [
            $type => MarkdownParser::parse($value),
        ];
    }
}
