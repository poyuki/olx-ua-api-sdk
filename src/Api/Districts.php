<?php

namespace Parhomenko\Olx\Api;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

/**
 * Class Districts
 *
 * @package Parhomenko\Olx\Api
 */
class Districts extends BaseResource
{
    private const OLX_DISTRICTS_URL = '/api/partner/districts';

    /**
     * @return array
     * @throws GuzzleException
     */
    public function getAll(): array
    {
        $response = $this->guzzleClient->request('GET', self::OLX_DISTRICTS_URL, [
            RequestOptions::HEADERS => [
                'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                'Version' => self::API_VERSION
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['data'])) {
            throw new Exception('Got empty response | Get all OLX districts');
        }

        return $data['data'];
    }

    /**
     * @param int $districtId
     * @return array
     * @throws Exception|GuzzleException
     */
    public function get(int $districtId): array
    {
        $response = $this->guzzleClient
            ->request('GET', self::OLX_DISTRICTS_URL . '/' . $districtId, [
                RequestOptions::HEADERS => [
                    'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                    'Version' => self::API_VERSION
                ]
            ]);

        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['data'])) {
            throw new Exception('Got empty response | Get all OLX district');
        }

        return $data['data'];
    }
}