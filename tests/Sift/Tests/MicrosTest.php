<?php

namespace Sift\Tests;

class MicrosTest extends SiftTestCase
{
    public function testFromDollars()
    {
        $this->assertEquals(1230000, \Sift\Micros::fromDollars(1.23));
    }
}
