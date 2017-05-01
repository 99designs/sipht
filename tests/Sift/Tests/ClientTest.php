<?php

namespace Sift\Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client as HttpClient;
use Sift\Client;
use Sift\Event;
use Sift\Exception\BadRequestException;
use Sift\Exception\HttpException;
use Sift\Exception\ServerErrorException;
use Sift\Label;

class ClientTest extends SiftTestCase
{
    const API_KEY = 'ABC123';

    private $mockHandler;
    private $requests;
    private $client;

    public function setUp()
    {
        $this->mockHandler = new MockHandler([]);

        $this->requests = [];
        $history = Middleware::history($this->requests);
        $handler = HandlerStack::create($this->mockHandler);
        $handler->push($history);

        $httpClient = new HttpClient([
            'handler' => $handler,
            'base_uri' => 'http://example.com/v203/',
        ]);

        $this->client = new Client('ABC123', $httpClient);
    }

    public function testDefaultConfiguration()
    {
        $client = new Client(self::API_KEY);
        $httpClient = $client->getHttpClient();

        $this->assertEquals('https://api.siftscience.com/v203/', $httpClient->getConfig('base_uri'));
    }

    public function testPostEvent()
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'baz' => 'bla',
        ])));
        $response = $this->client->postEvent(new Event(['foo' => 'bar']));

        $this->assertEqualAssociativeArrays($response, ['baz' => 'bla']);
        $this->assertEquals(1, count($this->requests));
        $this->assertEquals('http://example.com/v203/events', (string)$this->requests[0]['request']->getUri());
        $this->assertEqualAssociativeArrays(
            array('$api_key' => self::API_KEY, 'foo' => 'bar'),
            json_decode((string) $this->requests[0]['request']->getBody())
        );
    }

    public function testLabelUser()
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'baz' => 'bla',
        ])));
        $response = $this->client->labelUser(new Label('1234', ['foo' => 'bar']));

        $this->assertEqualAssociativeArrays($response, ['baz' => 'bla']);
        $this->assertEquals(1, count($this->requests));
        $this->assertEquals('http://example.com/v203/users/1234/labels', (string)$this->requests[0]['request']->getUri());
        $this->assertEqualAssociativeArrays(
            array('$api_key' => self::API_KEY, 'foo' => 'bar'),
            json_decode((string) $this->requests[0]['request']->getBody())
        );
    }

    public function testUserScore()
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'user_id' => '123',
            'score' => 0.93,
            'reasons' => [
                [
                    'name' => 'UsersPerDevice',
                    'value' => 4,
                    'details' => [
                        'users' => 'a, b, c, d',
                    ],
                ],
            ],
        ])));
        $score = $this->client->userScore(123);

        $this->assertEquals(1, count($this->requests));
        $this->assertEquals('http://example.com/v203/score/123/?api_key=' . self::API_KEY, (string)$this->requests[0]['request']->getUri());

        $this->assertEquals('123', $score->userId);
        $this->assertEquals(0.93, $score->score);
        $this->assertEquals(
            [
                [
                    'name' => 'UsersPerDevice',
                    'value' => 4,
                    'details' => [
                        'users' => 'a, b, c, d',
                    ],
                ],
            ],
            $score->reasons
        );
    }

    public function testInvalidResponseRethrownAsBadRequestException()
    {
        $this->mockHandler->append(new Response(403, [], json_encode([
            'status' => 51,
            'error_message' => 'Invalid API key',
        ])));

        $this->setExpectedException(BadRequestException::class);
        $this->client->send('POST', 'foo/butts');
    }

    public function testServerErrorRethrownAsServerErrorException()
    {
        $this->mockHandler->append(new Response(500, []));

        $this->setExpectedException(ServerErrorException::class);
        $this->client->send('POST', 'foo/butts');
    }

    public function testCurlExceptionRethrownAsHttpException()
    {
        $this->mockHandler->append(new \Exception('derp'));

        $this->setExpectedException(HttpException::class);
        $this->client->send('POST', 'foo/butts');
    }
}
