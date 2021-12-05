<?php

namespace Parhomenko\Olx\Api;

use DateInterval;
use DateTime;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Query;
use GuzzleHttp\RequestOptions;
use Parhomenko\Olx\Exceptions\BadRequestException;
use Parhomenko\Olx\Exceptions\ExceptionFactory;
use Parhomenko\Olx\Exceptions\RefreshTokenException;
use Throwable;
use function json_decode;

/**
 * Class User
 *
 * @package Parhomenko\Olx\Api
 */
class User
{
    private const OLX_AUTH_REQUEST_URI = '/api/open/oauth/token';
    private const OLX_AUTH_DEFAULT_GRAND_TYPE = 'authorization_code';
    private const OLX_AUTH_DEFAULT_SCOPE = 'read write v2';
    private const OLX_AUTH_DEFAULT_TOKEN_TYPE = 'bearer';

    /**
     * @var Client
     */
    private $guzzleClient;
    /**
     * @var int
     */
    private $client_id;
    /**
     * @var string
     */
    private $client_secret;
    /**
     * @var string|null
     */
    private $access_token;
    /**
     * @var string|null
     */
    private $refresh_token;
    /**
     * @var string
     */
    private $token_type;
    /**
     * @var int
     */
    private $token_expires_in;
    /**
     * @var string
     */
    private $token_updated_at;
    /**
     * @var string
     */
    private $grant_type;
    /**
     * @var string
     */
    private $scope;

    /**
     * @var string[]
     */
    private $requiredCredentials = [
        'client_id',
        'client_secret'
    ];

    /**
     * User constructor.
     *
     * @param Client $guzzleClient
     * @param $credentials
     * @throws Exception
     */
    public function __construct(Client $guzzleClient, $credentials)
    {
        $this->validateCredentials($credentials);

        $this->guzzleClient = $guzzleClient;

        $this->client_id = $credentials['client_id'];
        $this->client_secret = $credentials['client_secret'];
        $this->access_token = $credentials['access_token'] ?? null;
        $this->refresh_token = $credentials['refresh_token'] ?? null;
        $this->token_type = $credentials['token_type'] ?? self::OLX_AUTH_DEFAULT_TOKEN_TYPE;
        $this->grant_type = $credentials['grant_type'] ?? self::OLX_AUTH_DEFAULT_GRAND_TYPE;
        $this->scope = $credentials['scope'] ?? self::OLX_AUTH_DEFAULT_SCOPE;
        $this->token_expires_in = $credentials['expires_in'] ?? 0;
        $this->token_updated_at = $credentials['updated_at'] ?? '2000-01-01 00:00:00';
    }

    /**
     * @return integer
     */
    public function getClientId(): int
    {
        return $this->client_id;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->client_secret;
    }

    /**
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->token_type;
    }

    public function getAccessToken(): ?string
    {
        return $this->access_token;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refresh_token;
    }

    /**
     * @return int
     */
    public function getTokenExpiresIn(): int
    {
        return $this->token_expires_in;
    }

    /**
     * @param array $credentials
     * @return void
     * @throws Exception
     */
    private function validateCredentials(array $credentials): void
    {
        $missing_credentials = [];

        foreach ($this->requiredCredentials as $required_credential) {
            if (!array_key_exists($required_credential, $credentials)) {
                $missing_credentials[] = $required_credential;
            }
        }

        if (!empty($missing_credentials)) {
            throw new Exception('Missing credentials: ' . implode(', ', $missing_credentials));
        }
    }

    /**
     * Check if token is invalid or unexpected
     *
     * @throws BadRequestException
     * @throws GuzzleException
     * @throws RefreshTokenException
     * @throws Throwable
     */
    public function checkToken(): self
    {

        if (!$this->access_token) {
            $this->refreshToken();
        } else {

            $date_time_expires = new DateTime($this->token_updated_at);
            $date_time_expires->add(new DateInterval('PT' . $this->token_expires_in . 'S'));

            if ($date_time_expires <= new DateTime()) {
                $this->refreshToken();
            }

        }

        return $this;
    }

    /**
     * Step 1. Get OAuth link
     *
     * @param string|null $redirect_uri
     * @param string|null $state
     * @return string
     */
    public function getOAuthLink(string $redirect_uri = null, string $state = null): string
    {
        $params = [
            'client_id' => $this->client_id,
            'response_type' => 'code',
            'scope' => self::OLX_AUTH_DEFAULT_SCOPE,
        ];

        if ($redirect_uri !== null) {
            $params['redirect_uri'] = $redirect_uri;
        }
        if ($state !== null) {
            $params['state'] = $state;
        }
        return $this->guzzleClient->getConfig('base_uri') . 'oauth/authorize/?' . Query::build($params);
    }

    /**
     * Step2. Get access token via code
     *
     * @throws GuzzleException
     * @throws Exception
     */
    public function authorize(string $code = null, $redirect_uri = null): self
    {
        $request_data = [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => $this->grant_type,
            'scope' => $this->scope
        ];

        if ($code !== null) {
            $request_data['code'] = $code;
        }
        if ($redirect_uri !== null) {
            $request_data['redirect_uri'] = $redirect_uri;
        }

        $response = $this->guzzleClient->request('POST', self::OLX_AUTH_REQUEST_URI, [
            RequestOptions::JSON => $request_data
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        if (!empty($data['access_token'])) {
            $this->access_token = $data['access_token'];
            $this->token_type = $data['token_type'];
            if (!empty($data['refresh_token'])) {
                $this->refresh_token = $data['refresh_token'];
            }
            $this->token_expires_in = $data['expires_in'];
            $this->token_updated_at = date("Y-m-d H:i:s");
        } else {
            throw new Exception('Can not get access token');
        }

        return $this;
    }

    /**
     * @return $this
     * @throws BadRequestException
     * @throws GuzzleException
     * @throws RefreshTokenException
     * @throws Throwable
     */
    public function refreshToken(): self
    {
        try {
            $response = $this->guzzleClient->request('POST', self::OLX_AUTH_REQUEST_URI, [
                RequestOptions::JSON => [
                    'client_id' => $this->client_id,
                    'client_secret' => $this->client_secret,
                    'grant_type' => "refresh_token",
                    'refresh_token' => $this->refresh_token
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (!empty($data['access_token'])) {
                $this->access_token = $data['access_token'];
                $this->token_type = $data['token_type'];
                $this->refresh_token = $data['refresh_token'];
                $this->token_expires_in = $data['expires_in'];
                $this->token_updated_at = date("Y-m-d H:i:s");
            } else {
                throw new BadRequestException('Can not refresh access token');
            }

            return $this;
        } catch (ClientException $e) {
            if ($e->getCode() === 400) {
                $response = json_decode($e->getResponse()->getBody(), false);

                throw new RefreshTokenException(
                    $response->error_human_title ?? 'Can not refresh access token',
                    $e->getCode(),
                    null,
                    $response->error ?? null,
                    $response->error_description ?? null
                );
            }

            throw ExceptionFactory::createFromThrowable($e);
        }
    }
}
