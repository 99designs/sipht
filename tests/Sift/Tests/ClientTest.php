<?php

namespace Sift\Tests;

class ClientTest extends SiftTestCase
{
	const API_KEY = 'ABC123';

	public function testPostEvent()
	{
		$http = new \Guzzle\Http\Client('http://example.com');
        $mock = new \Guzzle\Plugin\Mock\MockPlugin();
        $http->addSubscriber($mock);

		$sift = new \Sift\Client(self::API_KEY, $http);

		$self = $this;

        $mock->addResponse(new \Guzzle\Http\Message\Response(200, null, '{"baz": "bla"}'));
		$response = $sift->postEvent(new \Sift\Event(array('foo' => 'bar')));
        $requests = $mock->getReceivedRequests();

		$this->assertEqualAssociativeArrays($response, array('baz' => 'bla'));
        $this->assertEquals(1, count($requests));
        $this->assertEquals('http://example.com/v202/events', $requests[0]->getUrl());
        $this->assertEqualAssociativeArrays(
            array('$api_key' => self::API_KEY, 'foo' => 'bar'),
            json_decode((string) $requests[0]->getBody())
        );
	}
}
