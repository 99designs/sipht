<?php

namespace Sift\Exception;

/**
 * Corresponds to an HTTP 400; generated if we make an invalid request to the
 * Sift Science API.
 */
class BadRequestException extends \Sift\Exception
{
    /**
     * Wrap a Guzzle\Http\Exception\ClientErrorResponseException
     */
    public static function fromClientErrorResponseException($ex)
    {
        $errorData = $ex->getResponse()->json();
        return new self(
            $errorData['error_message'],
            $errorData['status'],
            $ex
        );
    }
}
