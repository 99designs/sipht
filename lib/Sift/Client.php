<?php

namespace Sift;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use function GuzzleHttp\Psr7\stream_for;
use Sift\Exception\BadRequestException;
use Sift\Exception\HttpException;
use Sift\Exception\ServerErrorException;

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
    private $httpClient;

    /**
     * Constructor
     * @param string $apiKey     Sift API key
     * @param HttpClient $httpClient something matching the HttpClient interface
     */
    public function __construct($apiKey, $httpClient = null)
    {
        $this->apiKey = $apiKey;
        $this->httpClient = $httpClient ?: $this->defaultHttpClient();
    }

    /**
     * Return a default client for posting HTTP requests.
     * @return HttpClient
     */
    public function defaultHttpClient()
    {
        $baseUri = sprintf(
            '%s/v%s/',
            self::API_ENDPOINT,
            self::API_VERSION
        );

        return new HttpClient([
            'base_uri' => $baseUri,
        ]);
    }

    /**
     * Fetch the configured HTTP client
     * @return HttpClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Post an event to the REST API and return decoded JSON response.
     *
     * @see https://siftscience.com/docs/references/events-api
     * @see Event
     *
     * @param Event $event
     * @return array
     */
    public function postEvent(Event $event)
    {
        $json = $event
            ->withKey($this->apiKey)
            ->toJson();

        $json = $this->send('POST', "events", $json);

        return $json;
    }

    /**
     * Label a given user and return decoded JSON response.
     *
     * @see https://siftscience.com/docs/references/labels-api
     * @see Label
     *
     * @param Label $label
     * @return array
     */
    public function labelUser(Label $label)
    {
        $json = $label
            ->withKey($this->apiKey)
            ->toJson();

        $json = $this->send('POST', "users/{$label->userId}/labels", $json);

        return $json;
    }

    /**
     * Fetch user fraud score details
     *
     * @see https://siftscience.com/docs/getting-scores
     *
     * @param string $userId
     * @return Score
     */
    public function userScore($userId)
    {
        $path = sprintf(
            'score/%s/?%s',
            $userId,
            http_build_query(array('api_key' => $this->apiKey))
        );

        $json = $this->send('GET', $path);

        return Score::fromArray($json);
    }

    public function send($method, $url, $jsonBody = null)
    {
        try {
            $options = [];
            if ($jsonBody) {
                $options = [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => $jsonBody,
                ];
            }

            $response = $this->httpClient->request($method, $url, $options);

            return json_decode($response->getBody(), true);
        } catch (ClientException $ex) {
            throw BadRequestException::fromClientErrorResponseException($ex);
        } catch (ServerException $ex) {
            throw ServerErrorException::fromServerErrorResponseException($ex);
        } catch (\Exception $ex) {
            throw new HttpException($ex->getMessage(), 0, $ex);
        }
    }
}
