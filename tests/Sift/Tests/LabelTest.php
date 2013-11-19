<?php

namespace Sift\Tests;

use Sift\Label;

class LabelTest extends SiftTestCase
{
    public function testGood()
    {
        $this->assertEqualAssociativeArrays(
            array(
                '$is_bad' => false,
            ),
            Label::good()
        );
    }

    public function testGoodWithDescription()
    {
        $this->assertEqualAssociativeArrays(
            array(
                '$is_bad' => false,
                '$description' => 'foo',
            ),
            Label::good('foo')
        );
    }

    public function testBad()
    {
        $this->assertEqualAssociativeArrays(
            array(
                '$is_bad' => true,
            ),
            Label::bad()
        );
    }

    public function testBadWithReasonsAndDescription()
    {
        $this->assertEqualAssociativeArrays(
            array(
                '$is_bad' => true,
                '$reasons' => array('$chargeback', '$spam'),
                '$description' => 'foo',
            ),
            Label::bad(array(Label::REASON_CHARGEBACK, Label::REASON_SPAM), 'foo')
        );
    }
}
