<?php

namespace Gglink\Ggtasker;

use Gglink\Ggtasker\Exception\GgtaskerException;
use Gglink\Ggtasker\Util\ApiClient;


/**
 * The Ggtasker SDK Client
 *
 * @package  Gglink\Ggtasker
 */
class Ggtasker
{

    /**
     * The user token
     *
     * @var string
     */
    private $userToken;

    /**
     * The HTTP API client
     *
     * @var ApiClient
     */
    private $client;


    /**
     * Creates a instance of the SDK Client
     *
     * @param string $apiBaseURL The API base url
     * @param string $apiKey The program token that is used for some API calls
     *
     * @throws GgtaskerException
     */
    public function __construct($apiBaseURL, $apiKey) {
        if (empty($apiKey) || empty($apiBaseURL)) {
            throw new GgtaskerException('You need to specify your API base URL & API KEY!');
        }
        $this->client = new ApiClient($apiBaseURL, $apiKey);
    }


    /**
     * User login authentication
     *
     * @param string $username user to login
     * @param string $password user password to login
     * @return array
     *
     * @throws GgtaskerException
     */
    public function login(string $username, string $password) {
        $requestBody = [
            'Username'=> $username,
            'Password'=> $password
        ];
        $body = $this->client->doPost('/user/login', array(), $requestBody, array());
        return $body;
    }

    /**
     * User request token
     *
     * @param string $username user to login
     * @param string $password user password to login
     * @return string
     *
     * @throws GgtaskerException
     */
    public function requestToken(string $username, string $password) {
        $requestBody = [
            'Username'=> $username,
            'Password'=> $password
        ];
        $body = $this->client->doPost('/user/request_token', array(), $requestBody, array());
        return $body["Token"];
    }

    /**
     * Add user
     *
     * @param array $userData user data
     * @return array
     *
     * @throws GgtaskerException
     */
    public function addUser($userData, $userToken) {
        if (empty($userToken)) {
            throw new GgtaskerException('You need to specify your user token!');
        }
        $body = $this->client->doPost('/user/add', array(), $userData, $this->getExtraHeader($userToken));
        return $body;
    }


    /**
     * Get a user details
     *
     * @param array $userData user data
     * @return array
     *
     * @throws GgtaskerException
     */
    public function getUserDetails($userId, $userToken) {
        if (empty($userToken)) {
            throw new GgtaskerException('You need to specify your user token!');
        }
        $urlParam = ['Id' => $userId];
        $body = $this->client->doGet('/user/detail', $urlParam, $this->getExtraHeader($userToken));
        return $body;
    }


    /**
     * Delete user
     *
     * @param array $userData user data
     * @return array
     *
     * @throws GgtaskerException
     */

    public function deleteUser($userId, $userToken) {
        if (empty($userToken)) {
            throw new GgtaskerException('You need to specify your user token!');
        }
        $requestBody = ['Id' => $userId];
        $body = $this->client->doPost('/user/delete', array(), $requestBody, $this->getExtraHeader($userToken));
        return $body;
    }

    /**
     * update user
     *
     * @param array $userData user data
     * @return array
     *
     * @throws GgtaskerException
     */
    public function updateUser($userData, $userToken) {
        if (empty($userToken)) {
            throw new GgtaskerException('You need to specify your user token!');
        }
        $body = $this->client->doPost('/user/update', array(), $userData, $this->getExtraHeader($userToken));
        return $body;
    }

    /**
     * user permissions
     *
     * @param array $userData user data
     * @return array
     *
     * @throws GgtaskerException
     */
    public function userPermissions($userData, $userToken) {
        if (empty($userToken)) {
            throw new GgtaskerException('You need to specify your user token!');
        }
        // need to implement this
    }

    /**
     * user permissions
     *
     * @param array $userData user data
     * @return array
     *
     * @throws GgtaskerException
     */
    public function userSettings($userData, $userToken) {
        if (empty($userToken)) {
            throw new GgtaskerException('You need to specify your user token!');
        }
        // need to implement this
    }



    /**
     * user permissions
     *
     * @param array $userData user data
     * @return array
     *
     * @throws GgtaskerException
     */
    public function userReport($userData, $userToken) {
        if (empty($userToken)) {
            throw new GgtaskerException('You need to specify your user token!');
        }
        // need to implement this
    }


    /**
     * Build extra headers for request
     *
     * @param string $token user token
     * @return array
     *
     */
    private function getExtraHeader($token=null) {
        if(empty($token)){
            return [];
        }
        return [
            "X-Token" => $token
        ];
    }

}
