<?php

namespace Parhomenko\Olx\Api;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

/**
 * Class Users
 *
 * @package Parhomenko\Olx\Api
 */
class Users extends BaseResource
{
    private const OLX_AUTHENTICATED_USER_URL = '/api/partner/users/me';
    private const OLX_USER_URL = '/api/partner/users';

    /**
     * Return information about current user
     *
     * @return array
     * @throws Exception|GuzzleException
     */
    public function me(): array
    {
        $response = $this->guzzleClient
            ->request('GET', self::OLX_AUTHENTICATED_USER_URL, [
                RequestOptions::HEADERS => [
                    'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                    'Version' => self::API_VERSION
                ]
            ]);

        $meInfo = json_decode($response->getBody()->getContents(), true);

        if (!isset($meInfo['data'])) {
            throw new Exception('Got empty response | Get authenticated user');
        }

        return $meInfo['data'];
    }

    /**
     * Get one user from OLX.UA by ID
     *
     * @param int $user_id
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function get(int $user_id): array
    {
        $response = $this->guzzleClient
            ->request('GET', self::OLX_USER_URL . '/' . $user_id, [
                RequestOptions::HEADERS => [
                    'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                    'Version' => self::API_VERSION
                ]
            ]);

        $userInfo = json_decode($response->getBody()->getContents(), true);

        if (!isset($userInfo['data'])) {
            throw new Exception('Got empty response | Get OLX advert');
        }

        return $userInfo['data'];
    }
}