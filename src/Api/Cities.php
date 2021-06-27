<?php

namespace Parhomenko\Olx\Api;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

/**
 * Class Cities
 *
 * @package Parhomenko\Olx\Api
 */
class Cities extends BaseResource
{
    const OLX_CITIES_URL = '/api/partner/cities';

    /**
     * @param int $offset
     * @param int|null $limit
     * @return array
     * @throws GuzzleException
     */
    public function getAll(int $offset = 0, int $limit = null): array
    {
        $response = $this->guzzleClient->request('GET', self::OLX_CITIES_URL, [
            RequestOptions::HEADERS => [
                'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                'Version' => self::API_VERSION
            ],
            RequestOptions::QUERY => [
                'offset' => $offset,
                'limit' => $limit
            ]
        ]);

        $cities = json_decode($response->getBody()->getContents(), true);

        if (!isset($cities['data'])) {
            throw new Exception('Got empty response | Get all OLX cities');
        }

        return $cities['data'];

    }

    /**
     * @param int $city_id
     * @return array
     * @throws GuzzleException
     */
    public function get(int $city_id): array
    {
        $response = $this->guzzleClient->request('GET', self::OLX_CITIES_URL . '/' . $city_id, [
            RequestOptions::HEADERS => [
                'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                'Version' => self::API_VERSION
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['data'])) {
            throw new Exception('Got empty response | Get all OLX cities');
        }

        return $data['data'];
    }
}