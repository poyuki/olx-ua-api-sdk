<?php

namespace Parhomenko\Olx\Api;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

/**
 * Class Threads
 *
 * @package Parhomenko\Olx\Api
 */
class Threads extends BaseResource
{
    private const OLX_THREADS_URL = '/api/partner/threads';

    /**
     * Get OLX.ua thread info
     *
     * @param int $thread_id
     * @return array
     * @throws GuzzleException
     */
    public function get(int $thread_id): array
    {
        $response = $this->guzzleClient
            ->request('GET', self::OLX_THREADS_URL . '/' . $thread_id, [
                RequestOptions::HEADERS => [
                    'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                    'Version' => self::API_VERSION
                ]
            ]);

        $thread = json_decode($response->getBody()->getContents(), true);

        if (!isset($thread['data'])) {
            throw new Exception('Got empty response | Get OLX thread');
        }

        return $thread['data'];
    }

    /**
     * Get all threads from OLX.ua
     *
     * @param int $offset
     * @param int|null $limit
     * @param int|null $advert_id
     * @param int|null $interlocutor_id
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getAll(int $offset = 0, int $limit = null, int $advert_id = null, int $interlocutor_id = null): array
    {
        $params = [
            'offset' => $offset,
            'limit' => $limit
        ];

        if ($advert_id !== null) {
            $params['advert_id'] = $advert_id;
        }

        if ($interlocutor_id !== null) {
            $params['interlocutor_id'] = $interlocutor_id;
        }

        $response = $this->guzzleClient->request('GET', self::OLX_THREADS_URL, [
            RequestOptions::HEADERS => [
                'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                'Version' => self::API_VERSION
            ],
            RequestOptions::QUERY => $params
        ]);

        $threads = json_decode($response->getBody()->getContents(), true);

        if (!isset($threads['data'])) {
            throw new Exception('Got empty response | Get all OLX threads');
        }

        return $threads['data'];
    }

    /**
     * Mark thread as readed
     *
     * @param int $threadId
     * @return bool
     * @throws GuzzleException
     */
    public function markAsRead(int $threadId): bool
    {
        $params = ['command' => 'mark-as-read'];
        $response = $this->guzzleClient
            ->request('POST', self::OLX_THREADS_URL . '/' . $threadId . '/commands', [
                RequestOptions::HEADERS => [
                    'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                    'Version' => self::API_VERSION
                ],
                RequestOptions::JSON => $params
            ]);

        if ($response->getStatusCode() === 204) {
            return true;
        }

        throw new Exception($response->getBody()->getContents());
    }

    /**
     * Mark thread as favourite
     *
     * @param int $threadId
     * @return bool
     * @throws GuzzleException
     */
    public function setFavourite(int $threadId): bool
    {
        $params = ['command' => 'set-favourite', 'set-favourite' => true];
        $response = $this->guzzleClient
            ->request('POST', self::OLX_THREADS_URL . '/' . $threadId . '/commands', [
                RequestOptions::HEADERS => [
                    'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                    'Version' => self::API_VERSION
                ],
                RequestOptions::JSON => $params
            ]);

        if ($response->getStatusCode() === 204) {
            return true;
        }

        throw new Exception($response->getBody()->getContents());
    }

    /**
     * Unset favourite mark from thread
     *
     * @param int $threadId
     * @return bool
     * @throws GuzzleException
     */
    public function unsetFavourite(int $threadId): bool
    {
        $params = ['command' => 'set-favourite', 'set-favourite' => false];
        $response = $this->guzzleClient
            ->request('POST', self::OLX_THREADS_URL . '/' . $threadId . '/commands', [
                RequestOptions::HEADERS => [
                    'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                    'Version' => self::API_VERSION
                ],
                RequestOptions::JSON => $params
            ]);

        if ($response->getStatusCode() === 204) {
            return true;
        }

        throw new Exception($response->getBody()->getContents());
    }

    /**
     * Post message to the thread
     *
     * @param int $threadId
     * @param string $text
     * @return bool
     * @throws Exception|GuzzleException
     */
    public function post(int $threadId, string $text): bool
    {
        $params = ['text' => $text];
        $response = $this->guzzleClient
            ->request('POST', self::OLX_THREADS_URL . '/' . $threadId . '/messages', [
                RequestOptions::HEADERS => [
                    'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                    'Version' => self::API_VERSION
                ],
                RequestOptions::JSON => $params
            ]);

        if ($response->getStatusCode() === 200) {
            return true;
        }

        throw new Exception($response->getBody()->getContents());
    }
}