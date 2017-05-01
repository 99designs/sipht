<?php

namespace Sift\Exception;

/**
 * Corresponds to an HTTP 40x; generated if we make an invalid request to the
 * Sift Science API.
 */
class BadRequestException extends \Sift\Exception
{
    /**
     * Wrap a Guzzle\Http\Exception\ClientErrorResponseException
     */
    public static function fromClientErrorResponseException($ex)
    {
        $errorData = json_decode($ex->getResponse()->getBody(), true);
        return new self(
            $errorData['error_message'],
            $errorData['status'],
            $ex
        );
    }
}
