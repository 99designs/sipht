<?php

namespace Sift\Tests;

class ClientTest extends SiftTestCase
{
    const API_KEY = 'ABC123';

    public function setUp()
    {
        $http = new \Guzzle\Http\Client('http://example.com');
        $mock = new \Guzzle\Plugin\Mock\MockPlugin();
        $http->addSubscriber($mock);

        $this->client = new \Sift\Client(self::API_KEY, $http);
        $this->httpMock = $mock;
    }

    public function testPostEvent()
    {
        $this->httpMock->addResponse(new \Guzzle\Http\Message\Response(200, null, json_encode(array(
            'baz' => 'bla'
        ))));
        $response = $this->client->postEvent(new \Sift\Event(array('foo' => 'bar')));
        $requests = $this->httpMock->getReceivedRequests();

        $this->assertEqualAssociativeArrays($response, array('baz' => 'bla'));
        $this->assertEquals(1, count($requests));
        $this->assertEquals('http://example.com/v202/events', $requests[0]->getUrl());
        $this->assertEqualAssociativeArrays(
            array('$api_key' => self::API_KEY, 'foo' => 'bar'),
            json_decode((string) $requests[0]->getBody())
        );
    }

    public function testPostInvalidEvent()
    {
        $this->httpMock->addResponse(new \Guzzle\Http\Message\Response(403, null, json_encode(array(
            'status' => 51,
            'error_message' => 'Invalid API key',
        ))));

        $this->setExpectedException('\Sift\Exception\BadRequestException');
        $this->client->postEvent(new \Sift\Event(array('foo' => 'bar')));
    }

    public function testPostEventCausingServerError()
    {
        $this->httpMock->addResponse(new \Guzzle\Http\Message\Response(500));
        $this->setExpectedException('\Sift\Exception\ServerErrorException');
        $this->client->postEvent(new \Sift\Event(array('foo' => 'bar')));
    }

    public function testPostEventWithTransportError()
    {
        $this->httpMock->addException(new \Guzzle\Http\Exception\CurlException('derp'));
        $this->setExpectedException('\Sift\Exception\HttpException');
        $this->client->postEvent(new \Sift\Event(array('foo' => 'bar')));
    }
}
