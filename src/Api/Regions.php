<?php

namespace Parhomenko\Olx\Api;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use function json_decode;

/**
 * Class Regions
 *
 * @package Parhomenko\Olx\Api
 */
class Regions extends BaseResource
{
    private const OLX_REGIONS_URL = '/api/partner/regions';

    /**
     * @return array
     * @throws GuzzleException
     */
    public function getAll(): array
    {
        $response = $this->guzzleClient->request('GET', self::OLX_REGIONS_URL, [
            RequestOptions::HEADERS => [
                'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                'Version' => self::API_VERSION
            ]
        ]);

        $regions = json_decode($response->getBody()->getContents(), true);

        if (!isset($regions['data'])) {
            throw new Exception('Got empty response | Get all OLX regions');
        }

        return $regions['data'];
    }

    /**
     * @param int $regionId
     * @return array
     * @throws GuzzleException
     */
    public function get(int $regionId): array
    {
        $response = $this->guzzleClient
            ->request('GET', self::OLX_REGIONS_URL . '/' . $regionId, [
                RequestOptions::HEADERS => [
                    'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                    'Version' => self::API_VERSION
                ]
            ]);

        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['data'])) {
            throw new Exception('Got empty response | Get all OLX regions');
        }

        return $data['data'];
    }
}