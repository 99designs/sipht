<?php

namespace Sift\Exception;

/**
 * An exception thrown during event transmission
 */
class HttpException extends \Sift\Exception
{
    public static function fromGuzzleException($ex)
    {
        if ($ex instanceof \Guzzle\Http\Exception\ServerErrorResponseException)
            return ServerErrorException::fromServerErrorResponseException($ex);
        elseif ($ex instanceof \Guzzle\Http\Exception\ClientErrorResponseException)
            return BadRequestException::fromClientErrorResponseException($ex);
        else
            return new self($ex->getMessage(), 0, $ex);
    }
}
