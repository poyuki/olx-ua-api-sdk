<?php

namespace Parhomenko\Olx\Api;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use function json_decode;

/**
 * Class Languages
 *
 * @package Parhomenko\Olx\Api
 */
class Languages extends BaseResource
{
    private const OLX_LANGUAGES_URL = '/api/partner/languages';

    /**
     * @return array
     * @throws GuzzleException
     */
    public function getAll(): array
    {
        $response = $this->guzzleClient->request('GET', self::OLX_LANGUAGES_URL, [
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