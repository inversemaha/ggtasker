<?php
namespace Gglink\Ggtasker\Util;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use Gglink\Ggtasker\Response\ErrorResponse;
use Gglink\Ggtasker\Exception\GgtaskerException;



/**
 * The internal API client
 *
 * @package Gglink\Ggtasker\Util
 */
class ApiClient {

    /**
     * The SDK version number
     *
     * @var string
     */
    const VERSION = '0.1.0';

    /**
     * The Guzzle http client
     *
     * @var Client
     */
    private $client;

    /**
     * Creates a instance of the API client
     *
     * @param string $apiBaseURL The API server to connect to
     * @param string $apiKey The API key to authenticate with server
     * @param array $clientOptions Guzzle Client Options
     */
    public function __construct($apiBaseURL, $apiKey, $clientOptions = array()) {
        // Setup http client if not specified
        $this->client = new Client(array_merge_recursive(array(
            'base_uri' => $apiBaseURL,
            'headers' => array(
                'User-Agent' => 'Ggtasker PHP SDK v' . self::VERSION,
                'Accept' => 'application/json',
                'x-sdk-version' => self::VERSION,
                'x-sdk-type' => 'PHP',
                'X-API-KEY' => $apiKey)
        ), $clientOptions));
    }

    /**
     * Do a POST call to the Ggtasker API server
     *
     * @param string $partialUrl The url template
     * @param array $uriParams The url template parameters
     * @param array|null $data The data to submit
     * @param array $headers Additional headers
     * @return array
     *
     * @throws GgtaskerException
     */
    public function doPost($partialUrl, array $uriParams, array $data = null, array $headers = array()) {
        return $this->doRequest('POST', $partialUrl, $uriParams, array(
            'body' => $data ? \GuzzleHttp\json_encode($data, JSON_FORCE_OBJECT) : '{}',
            'headers' => array_merge($headers, array(
                'Content-Type' => 'application/json'
            ))
        ));
    }

    /**
     * Do a PUT call to the Ggtasker API server
     *
     * @param string $partialUrl The url template
     * @param array $uriParams The url template parameters
     * @param array $data The data to update
     * @param array $headers Additional headers
     * @return array
     *
     * @throws GgtaskerException
     */
    public function doPut($partialUrl, array $uriParams, array $data, array $headers = array()) {
        return $this->doRequest('PUT', $partialUrl, $uriParams, array(
            'body' => $data ? \GuzzleHttp\json_encode($data, JSON_FORCE_OBJECT) : '{}',
            'headers' => array_merge($headers, array(
                'Content-Type' => 'application/json'
            ))
        ));
    }

    /**
     * Do a GET call to the Ggtasker API server
     *
     * @param string $partialUrl The url template
     * @param array $uriParams The url template parameters
     * @param array $headers Additional headers
     * @return array
     *
     * @throws GgtaskerException
     */
    public function doGet($partialUrl, array $uriParams, array $headers = array()) {
        return $this->doRequest('GET', $partialUrl, $uriParams, array(
            'headers' => $headers
        ));
    }


    /**
     * Do a DELETE call to the Ggtasker API server
     *
     * @param string $partialUrl The url template
     * @param array $uriParams The url template parameters
     * @param array $headers Additional headers
     * @return array
     *
     * @throws GgtaskerException
     */
    public function doDelete($partialUrl, array $uriParams, array $headers = array()) {
        return $this->doRequest('DELETE', $partialUrl, $uriParams, array(
            'headers' => $headers
        ));
    }

    /**
     * Execute API call and map error messages
     *
     * @param string $method The http method
     * @param string $url The url template
     * @param array $urlParams The url template parameters
     * @param array $options The request options
     * @return array
     *
     * @throws GgtaskerException
     */
    private function doRequest($method, $url, array $urlParams, array $options) {
        try {
            if (!isset($options['headers'])) {
                $options[] = array('headers' => array());
            }
            $options['headers']['Accept'] = 'application/json';
            if(count($urlParams)> 0) {
                $options["query"] = $urlParams;
            }

            $response = $this->client->request($method, $url, $options);
            if ($response->getStatusCode() === 204) {
                return array();
            }
            $this->checkResponseHeaderContentType($response);
            $body = \GuzzleHttp\json_decode($response->getBody(), true);
            return $body;
        } catch (ConnectException $e) {
            $errorResponse = new ErrorResponse(0, array('errors' => array(
                array(
                    'message' => 'Could not communicate with ' . $this->client->getConfig('base_uri'),
                    'code' => 'COMMUNICATION_ERROR'
                )
            )));
            throw new GgtaskerException($errorResponse, $e);
        } catch (BadResponseException $e) {
            dd($e->getResponse()->getStatusCode());
            $body = \GuzzleHttp\json_decode($e->getResponse()->getBody(), true);
            if (is_null($body) || !isset($body['errors']) || empty($body['errors'])) {
                $body = array('errors' => array(
                    array(
                        'message' => 'Failed to get any error message from response',
                        'code' => 'BAD_REQUEST'
                    )
                ));
            }
            $errorResponse = new ErrorResponse($e->getResponse()->getStatusCode(), $body);
            throw new GgtaskerException($errorResponse, $e);
        }
    }

    /**
     * Checks whether Content-Type header is valid in response
     *
     * @param string $response Response to be checked
     *
     * @throws GgtaskerException
     */
    private function checkResponseHeaderContentType($response) {
        $contentType = implode('', $response->getHeader('Content-Type'));
        $expectedContentType = 'application/json';
        $invalidContentType = $response->getStatusCode() !== 204 && !empty($contentType) && strpos($contentType, $expectedContentType) === false;
        if ($invalidContentType) {
            throw new GgtaskerException('Invalid Content-Type specified in Response Header');
        }
    }


}
