<?php

namespace Sift\Tests\Exception;

class HttpExceptionTest extends \Sift\Tests\SiftTestCase
{
    public function testCreateServerErrorException()
    {
        $wrapped = $this->createGuzzleException(500);
        $exception = \Sift\Exception\HttpException::fromGuzzleException($wrapped);

        $this->assertTrue($exception instanceof \Sift\Exception\ServerErrorException);
    }

    public function testCreateBadRequestException()
    {
        $wrapped = $this->createGuzzleException(403, json_encode(array(
            'status' => 42,
            'error_message' => 'foo',
        )));
        $exception = \Sift\Exception\HttpException::fromGuzzleException($wrapped);

        $this->assertTrue($exception instanceof \Sift\Exception\BadRequestException);
        $this->assertEquals('foo', $exception->getMessage());
    }

    public function testCreateHttpException()
    {
        $wrapped = new \Guzzle\Http\Exception\CurlException('derp');
        $exception = \Sift\Exception\HttpException::fromGuzzleException($wrapped);

        $this->assertTrue($exception instanceof \Sift\Exception\HttpException);
    }

    private function createGuzzleException($statusCode, $body=null)
    {
        return \Guzzle\Http\Exception\BadResponseException::factory(
            new \Guzzle\Http\Message\Request('GET', 'http://example.com/'),
            new \Guzzle\Http\Message\Response($statusCode, null, $body)
        );
    }
}
