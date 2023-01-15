<?php

namespace mfrtn\Press\Fields;

use mfrtn\Press\MarkdownParser;

class Body extends FieldContract
{
    public static function process($type, $value, $data)
    {
        return [
            $type => MarkdownParser::parse($value),
        ];
    }
}
