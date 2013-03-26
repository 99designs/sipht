<?php

namespace Sift;

/**
 * A minimal wrapper around the Sift REST API.
 */
class Client
{
    const API_ENDPOINT = 'https://api.siftscience.com';

    private $apiKey;
    private $http;

    /**
     * Constructor
     * @param string $apiKey Sift API key
     * @param object $http   something matching the Guzzle\Http\Client interface
     */
    public function __construct($apiKey, $http=null)
    {
        $this->apiKey = $apiKey;
        $this->http = $http ?: $this->defaultHttpClient();
    }

    /**
     * Return a default client for posting HTTP requests.
     * @return object
     */
    public function defaultHttpClient()
    {
        return new \Guzzle\Http\Client(self::API_ENDPOINT);
    }

    /**
     * Post an event to the REST API and return decoded JSON response.
     * @param Sift\Event $event
     * @return array
     */
    public function postEvent($event)
    {
        $json = $event->withKey($this->apiKey)->toJson();
        return $this->http
            ->post('/v202/events', null, $json)
            ->send()
            ->json();
    }
}
