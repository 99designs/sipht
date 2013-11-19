<?php

namespace Sift\Exception;

/**
 * Corresponds to an HTTP 50x; generated if the Sift Science API is having
 * problems.
 */
class ServerErrorException extends HttpException
{
    /**
     * Wrap a Guzzle\Http\Exception\ServerErrorResponseException
     */
    public static function fromServerErrorResponseException($ex)
    {
        return new self($ex->getMessage(), 0, $ex);
    }
}
