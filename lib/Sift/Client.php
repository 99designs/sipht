<?php

namespace Sift;

use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Exception\HttpException as GuzzleHttpException;
use Guzzle\Http\Message\Request;
use Sift\Event;
use Sift\Exception\HttpException as SiftHttpException;
use Sift\Label;

/**
 * A minimal wrapper around the Sift REST API.
 *
 * This class implements three API calls:
 *  - submitting events (`postEvent()`)
 *  - labelling users (`labelUser()`)
 *  - fetching scores (`userScore()`)
 */
class Client
{
    const API_VERSION = '203';
    const API_ENDPOINT = 'https://api.siftscience.com';

    private $apiKey;
    private $http;

    /**
     * Constructor
     * @param string $apiKey Sift API key
     * @param object $http   something matching the Guzzle\Http\Client interface
     */
    public function __construct($apiKey, $http = null)
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
        return new HttpClient(sprintf(
            '%s/v%s',
            self::API_ENDPOINT.
            self::API_VERSION
        ));
    }

    /**
     * Post an event to the REST API and return decoded JSON response.
     *
     * @see https://siftscience.com/docs/references/events-api
     * @see Sift\Event
     *
     * @param Sift\Event $event
     * @return array
     */
    public function postEvent(Event $event)
    {
        $json = $event
            ->withKey($this->apiKey)
            ->toJson();

        return $this->send(
            $this->http->post('events', null, $json)
        );
    }

    /**
     * Label a given user and return decoded JSON response.
     *
     * @see https://siftscience.com/docs/references/labels-api
     * @see Sift\Label
     *
     * @param string     $userId
     * @param Sift\Label $label
     * @return array
     */
    public function labelUser($userId, Label $label)
    {
        $json = $label
            ->withKey($this->apiKey)
            ->toJson();

        return $this->send(
            $this->http->post("users/$userId/labels", null, $json)
        );
    }

    /**
     * Fetch user fraud score details
     *
     * @see https://siftscience.com/docs/getting-scores
     *
     * @param string $userId
     * @return Sift\Score
     */
    public function userScore($userId)
    {
        $path = sprintf(
            'score/%s/?%s',
            $userId,
            http_build_query(array('api_key' => $this->apiKey))
        );

        $scoreData = $this->send(
            $this->http->get($path)
        );

        return Score::fromArray($scoreData);
    }

    public function send(Request $request)
    {
        try {
            return $request
                ->send()
                ->json();
        } catch (GuzzleHttpException $ex) {
            throw SiftHttpException::fromGuzzleException($ex);
        }
    }
}
