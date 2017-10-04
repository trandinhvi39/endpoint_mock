<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use App\Contracts\Services\PassportInterface;

class Passport implements PassportInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client;
    }

    public function request(callable $request)
    {
        try {
            $response = call_user_func($request);

            return json_decode($response->getBody());
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();

                return json_decode($response->getBody());
            }

            throw new \Exception("RequestException");
        }
    }

    public function passwordGrantToken(array $input, $scope = '')
    {
        return $this->request(function () use ($input, $scope) {
            return $this->client->post(env('APP_URL') . '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => env('API_CLIENT_ID'),
                    'client_secret' => env('API_CLIENT_SECRET'),
                    'username' => $input['email'],
                    'password' => $input['password'],
                    'scope' => $scope,
                ],
            ]);
        });
    }

    public function refreshGrantToken($refreshTokenKey)
    {
        return $this->request(function () use ($refreshTokenKey) {
            return $this->client->post(env('APP_URL') . '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refreshTokenKey,
                    'client_id' => env('API_CLIENT_ID'),
                    'client_secret' => env('API_CLIENT_SECRET'),
                ],
            ]);
        });
    }
}
