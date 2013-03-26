<?php

namespace Sift\Tests;

class SiftTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that two associative arrays are equal without respect to ordering
     * @param array $a
     * @param array $b
     */
    public function assertEqualAssociativeArrays($a, $b)
    {
        $a = (array) $a;
        $b = (array) $b;
        asort($a);
        asort($b);
        $this->assertEquals($a, $b);
    }
}
