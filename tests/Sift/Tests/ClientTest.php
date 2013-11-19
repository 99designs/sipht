<?php

namespace Sift\Tests;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use Sift\Client;
use Sift\Event;
use Sift\Label;

class ClientTest extends SiftTestCase
{
    const API_KEY = 'ABC123';

    public function setUp()
    {
        $http = new GuzzleClient('http://example.com/v203');
        $mock = new MockPlugin();
        $http->addSubscriber($mock);

        $this->client = new Client(self::API_KEY, $http);
        $this->http = $http;
        $this->httpMock = $mock;
    }

    public function testPostEvent()
    {
        $this->httpMock->addResponse(new Response(200, null, json_encode(array(
            'baz' => 'bla',
        ))));
        $response = $this->client->postEvent(new Event(array('foo' => 'bar')));
        $requests = $this->httpMock->getReceivedRequests();

        $this->assertEqualAssociativeArrays($response, array('baz' => 'bla'));
        $this->assertEquals(1, count($requests));
        $this->assertEquals('http://example.com/v203/events', $requests[0]->getUrl());
        $this->assertEqualAssociativeArrays(
            array('$api_key' => self::API_KEY, 'foo' => 'bar'),
            json_decode((string) $requests[0]->getBody())
        );
    }

    public function testLabelUser()
    {
        $this->httpMock->addResponse(new Response(200, null, json_encode(array(
            'baz' => 'bla',
        ))));
        $response = $this->client->labelUser('1234', new Label(array('foo' => 'bar')));
        $requests = $this->httpMock->getReceivedRequests();

        $this->assertEqualAssociativeArrays($response, array('baz' => 'bla'));
        $this->assertEquals(1, count($requests));
        $this->assertEquals('http://example.com/v203/users/1234/labels', $requests[0]->getUrl());
        $this->assertEqualAssociativeArrays(
            array('$api_key' => self::API_KEY, 'foo' => 'bar'),
            json_decode((string) $requests[0]->getBody())
        );
    }

    public function testUserScore()
    {
        $this->httpMock->addResponse(new Response(200, null, json_encode(array(
            'user_id' => '123',
            'score' => 0.93,
            'reasons' => array(
                array(
                    'name' => 'UsersPerDevice',
                    'value' => 4,
                    'details' => array(
                        'users' => 'a, b, c, d',
                    ),
                ),
            ),
        ))));

        $score = $this->client->userScore('123');
        $requests = $this->httpMock->getReceivedRequests();

        $this->assertEquals(1, count($requests));
        $this->assertEquals(
            'http://example.com/v203/score/123/?api_key=' . self::API_KEY,
            $requests[0]->getUrl()
        );

        $this->assertEquals('123', $score->userId);
        $this->assertEquals(0.93, $score->score);
        $this->assertEquals(
            array(
                array(
                    'name' => 'UsersPerDevice',
                    'value' => 4,
                    'details' => array(
                        'users' => 'a, b, c, d',
                    ),
                ),
            ),
            $score->reasons
        );
    }

    public function testInvalidResponseRethrownAsBadRequestException()
    {
        $this->httpMock->addResponse(new Response(403, null, json_encode(array(
            'status' => 51,
            'error_message' => 'Invalid API key',
        ))));

        $this->setExpectedException('Sift\Exception\BadRequestException');
        $this->client->send($this->http->post('foo/butts'));
    }

    public function testServerErrorRethrownAsServerErrorException()
    {
        $this->httpMock->addResponse(new Response(500));
        $this->setExpectedException('Sift\Exception\ServerErrorException');
        $this->client->send($this->http->post('foo/butts'));
    }

    public function testCurlExceptionRethrownAsHttpException()
    {
        $this->httpMock->addException(new CurlException('derp'));
        $this->setExpectedException('Sift\Exception\HttpException');
        $this->client->send($this->http->post('foo/butts'));
    }
}
