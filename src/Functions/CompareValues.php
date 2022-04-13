<?php

namespace Bakgul\Kernel\Functions;

class CompareValues
{
    public static function _($val1, $val2, string $operator): bool
    {
        return match ($operator) {
            '=' => $val1 == $val2,
            '==' => $val1 === $val2,
            '!' => $val1 != $val2,
            '!=' => $val1 !== $val2,
            '<>' => $val1 <> $val2,
            '>' => $val1 > $val2,
            '<' => $val1 < $val2,
            '>=' => $val1 >= $val2,
            '<=' => $val1 <= $val2,
            '<' => $val1 < $val2,
        };
    }
}
