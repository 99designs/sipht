<?php

namespace Sift\Tests;

use Sift\Label;

class LabelTest extends SiftTestCase
{
    public function testGood()
    {
        $label = Label::good('1234');
        $this->assertEqualAssociativeArrays(array('$is_bad' => false), $label);
        $this->assertEquals('1234', $label->userId);
    }

    public function testGoodWithDescription()
    {
        $label = Label::good('1234', 'foo');
        $this->assertEqualAssociativeArrays(
            array(
                '$is_bad' => false,
                '$description' => 'foo',
            ),
            $label
        );
        $this->assertEquals('1234', $label->userId);
    }

    public function testBad()
    {
        $label = Label::bad('1234');
        $this->assertEqualAssociativeArrays(array('$is_bad' => true), $label);
        $this->assertEquals('1234', $label->userId);
    }

    public function testBadWithReasonsAndDescription()
    {
        $label = Label::bad('1234', array(Label::REASON_CHARGEBACK, Label::REASON_SPAM), 'foo');
        $this->assertEqualAssociativeArrays(
            array(
                '$is_bad' => true,
                '$reasons' => array('$chargeback', '$spam'),
                '$description' => 'foo',
            ),
            $label
        );
        $this->assertEquals('1234', $label->userId);
    }
}
