<?php

namespace Parhomenko\Olx\Api;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

/**
 * Class Currencies
 *
 * @package Parhomenko\Olx\Api
 */
class Currencies extends BaseResource
{
    private const OLX_CURRENCY_URL = '/api/partner/currencies';

    public function getAll(int $offset = 0, int $limit = null): array
    {
        $response = $this->guzzleClient->request('GET', self::OLX_CURRENCY_URL, [
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
            throw new Exception('Got empty response | Get all OLX currencies');
        }

        return $cities['data'];
    }
}