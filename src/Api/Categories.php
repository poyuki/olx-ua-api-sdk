<?php

namespace Parhomenko\Olx\Api;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

/**
 * Class Categories
 *
 * @package Parhomenko\Olx\Api
 */
class Categories extends BaseResource
{
    private const OLX_CATEGORIES_URL = '/api/partner/categories';

    /**
     * Get all olx categories or one specified
     *
     * @param int $categoryId
     * @return array|mixed
     * @throws GuzzleException
     */
    public function get(int $categoryId): array
    {
        $response = $this->guzzleClient
            ->request('GET', self::OLX_CATEGORIES_URL . '/' . $categoryId, [
                RequestOptions::HEADERS => [
                    'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                    'Version' => self::API_VERSION
                ]]);

        $categories = \json_decode($response->getBody()->getContents(), true);

        if (!isset($categories['data'])) {
            throw new Exception('Got empty response | Get OLX category: ' . $categoryId);
        }

        return $categories['data'];
    }

    /**
     * @param int|null $parentId
     * @return array
     * @throws GuzzleException
     */
    public function getAll(int $parentId = null): array
    {
        $query = [];
        if ($parentId !== null) {
            $query['parent_id'] = $parentId;
        }

        $response = $this->guzzleClient->request('GET', self::OLX_CATEGORIES_URL, [
            RequestOptions::HEADERS => [
                'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                'Version' => self::API_VERSION
            ],
            RequestOptions::QUERY => $query
        ]);

        $categories = \json_decode($response->getBody()->getContents(), true);

        if (!isset($categories['data'])) {
            throw new Exception('Got empty response | Get all OLX categories, parent_id: ' . $parentId);
        }

        return $categories['data'];
    }

    /**
     * Get olx category attributes
     *
     * @param int $categoryId
     * @return array
     * @throws Exception|GuzzleException
     */
    public function attributes(int $categoryId): array
    {
        $response = $this->guzzleClient
            ->request('GET', self::OLX_CATEGORIES_URL . '/' . $categoryId . '/attributes', [
                RequestOptions::HEADERS => [
                    'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                    'Version' => self::API_VERSION
                ]]);

        $attributes = \json_decode($response->getBody()->getContents(), true);

        if (!isset($attributes['data'])) {
            throw new Exception('Got empty response | Get OLX category attributes: ' . $categoryId);
        }

        return $attributes['data'];
    }
}