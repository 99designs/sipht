<?php

namespace Sift\Tests;

class EventTest extends SiftTestCase
{
	public function testCreateTransactionEvent()
	{
		$this->assertEqualAssociativeArrays(
			array('$type' => '$transaction', 'foo' => 'bar'),
			\Sift\Event::transactionEvent(array('foo' => 'bar'))
		);
	}

	public function testCreateLabelEvent()
	{
		$this->assertEqualAssociativeArrays(
			array('$type' => '$label', 'foo' => 'bar'),
			\Sift\Event::labelEvent(array('foo' => 'bar'))
		);
	}

	public function testCreateCustomEvent()
	{
		$this->assertEqualAssociativeArrays(
			array('$type' => 'some_event', 'foo' => 'bar'),
			\Sift\Event::customEvent('some_event', array('foo' => 'bar'))
		);
	}

    public function testWithApiKey()
    {
        $this->assertEqualAssociativeArrays(
            array('$type' => 'foo', 'quux' => 42, '$api_key' => 'derp'),
            \Sift\Event::customEvent('foo', array('quux' => '42'))->withKey('derp')
        );
    }

    public function testToJson()
    {
        $this->assertEqualAssociativeArrays(
            array('$type' => 'foo', 'quux' => '42'),
            json_decode(
                \Sift\Event::customEvent('foo', array('quux' => '42'))->toJson(),
                true
            )
        );
    }
}
