<?php

namespace Sift;

class Micros
{
    public static function fromDollars($dollars)
    {
        return $dollars * 1000000;
    }
}
