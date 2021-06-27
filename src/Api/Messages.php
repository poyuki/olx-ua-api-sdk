<?php

namespace Parhomenko\Olx\Api;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

/**
 * Class Messages
 *
 * @package Parhomenko\Olx\Api
 */
class Messages extends BaseResource
{
    const OLX_THREADS_URL = '/api/partner/threads';

    /**
     * Get all messages for thread from OLX.ua
     *
     * @param int $thread_id
     * @param int $offset
     * @param int|null $limit
     * @return array
     * @throws GuzzleException
     */
    public function get(int $thread_id, int $offset = 0, int $limit = null): array
    {
        $response = $this->guzzleClient
            ->request('GET', self::OLX_THREADS_URL . '/' . $thread_id . '/messages', [
                RequestOptions::HEADERS => [
                    'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                    'Version' => self::API_VERSION
                ]
            ]);

        $messages = json_decode($response->getBody()->getContents(), true);

        if (!isset($messages['data'])) {
            throw new Exception('Got empty response | Get all OLX thread messages');
        }

        return $messages['data'];
    }
}