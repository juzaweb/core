<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/laravel-cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Core\Support;

class JuzawebApi
{
    protected $curl;
    protected $apiUrl = 'http://localhost:8008/api';
    protected $accessToken;
    protected $expiresAt;

    public function __construct(Curl $curl)
    {
        $this->curl = $curl;
    }

    public function login($email, $password)
    {
        $response = $this->callApiGetData('POST', $this->apiUrl . '/auth/login', [
            'email' => $email,
            'password' => $password
        ]);

        if (empty($response->access_token)) {
            return false;
        }

        $this->accessToken = $response->access_token;
        $this->expiresAt = now()->addSeconds($response->expires_in)->format('Y-m-d H:i:s');
        return true;
    }

    /**
     * @param string $uri
     * @param array $params
     * @return bool|object|object[]
     */
    public function get($uri, $params = [])
    {
        return $this->callApi('GET', $uri, $params);
    }

    /**
     * @param string $uri
     * @param array $params
     * @return bool|object|object[]
     */
    public function post($uri, $params = [])
    {
        return $this->callApi('POST', $uri, $params);
    }

    /**
     * Put data to api
     *
     * @param string $uri
     * @param array $params
     * @return bool|object|object[]
     */
    public function put($uri, $params = [])
    {
        return $this->callApi('PUT', $uri, $params);
    }

    protected function callApi($method, $uri, $params = [])
    {
        $headers = [];
        if ($this->accessToken) {
            $headers['Authorization'] = 'Bearer ' . $this->accessToken;
        }

        $response = $this->callApiGetData(
            $method,
            $this->apiUrl . '/' . $uri,
            $params,
            $headers
        );

        if (empty($response)) {
            return false;
        }

        return $response;
    }

    protected function callApiGetData($method, $url, $params = [], $headers = [])
    {
        switch (strtolower($method)) {
            case 'post':
                $response = $this->curl->post($url, $params, $headers);
                break;
            case 'put':
                $response = $this->curl->put($url, $params, $headers);
                break;
            default:
                $response = $this->curl->get($url, $params, $headers);
                break;
        }

        $content = $response->getBody()->getContents();

        if (is_json($content)) {
            return json_decode($content)->data ?? false;
        }

        return false;
    }
}