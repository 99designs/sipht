<?php

namespace Sift\Tests;

class EventTest extends SiftTestCase
{
    public function testCreateTransactionEvent()
    {
        $this->assertEqualAssociativeArrays(
            array(
                '$type' => '$transaction',
                '$user_id' => 123,
                'foo' => 'bar',
            ),
            \Sift\Event::transactionEvent(123, array('foo' => 'bar'))
        );
    }

    public function testCreateLabelEvent()
    {
        $this->assertEqualAssociativeArrays(
            array(
                '$type' => '$label',
                '$label' => '$banned',
                '$user_id' => 123,
                'foo' => 'bar',
            ),
            \Sift\Event::labelEvent('$banned', 123, array('foo' => 'bar'))
        );
    }

    public function testCreateCustomEvent()
    {
        $this->assertEqualAssociativeArrays(
            array(
                '$type' => 'some_event',
                '$user_id' => 123,
                'foo' => 'bar',
            ),
            \Sift\Event::customEvent('some_event', 123, array('foo' => 'bar'))
        );
    }

    public function testWithApiKey()
    {
        $this->assertEqualAssociativeArrays(
            array(
                '$type' => 'foo',
                '$user_id' => 123,
                'quux' => 42,
                '$api_key' => 'derp',
            ),
            \Sift\Event::customEvent('foo', 123, array('quux' => '42'))->withKey('derp')
        );
    }

    public function testToJson()
    {
        $this->assertEqualAssociativeArrays(
            array(
                '$type' => 'foo',
                '$user_id' => 123,
                'quux' => '42'
            ),
            json_decode(
                \Sift\Event::customEvent('foo', 123, array('quux' => '42'))->toJson(),
                true
            )
        );
    }
}
