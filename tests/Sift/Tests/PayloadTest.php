<?php

namespace Sift\Tests;

use Sift\Payload;

class PayloadTest extends SiftTestCase
{
    public function testWithApiKey()
    {
        $payload = new Payload(array('quux' => 42));

        $this->assertEqualAssociativeArrays(
            array(
                'quux' => 42,
                '$api_key' => 'derp',
            ),
            $payload->withKey('derp')
        );
    }

    public function testToJson()
    {
        $payload = new Payload(array(
            'foo' => 'bar',
            'quux' => 42,
        ));

        $this->assertEqualAssociativeArrays(
            array(
                'foo' => 'bar',
                'quux' => 42
            ),
            json_decode(
                $payload->toJson(),
                true
            )
        );
    }
}
