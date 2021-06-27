<?php

namespace Parhomenko\Olx\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Parhomenko\Olx\Exceptions\BadRequestException;
use Parhomenko\Olx\Exceptions\BaseOlxException;
use Parhomenko\Olx\Exceptions\CallLimitException;
use Parhomenko\Olx\Exceptions\ExceptionFactory;
use Parhomenko\Olx\Exceptions\ForbiddenException;
use Parhomenko\Olx\Exceptions\NotAcceptableException;
use Parhomenko\Olx\Exceptions\NotFoundException;
use Parhomenko\Olx\Exceptions\ServerException;
use Parhomenko\Olx\Exceptions\UnauthorizedException;
use Parhomenko\Olx\Exceptions\UnsupportedMediaTypeException;
use Parhomenko\Olx\Exceptions\ValidationException;

/**
 * Class Adverts
 *
 * @package Parhomenko\Olx\Api
 */
class Adverts extends BaseResource
{
    private const OLX_ADVERTS_URL = '/api/partner/adverts';

    /**
     * @param int $id
     * @return array
     * @throws BadRequestException
     * @throws GuzzleException
     * @throws \Throwable
     */
    public function get(int $id): array
    {
        try {
            $response = $this->guzzleClient->request('GET', self::OLX_ADVERTS_URL . '/' . $id, [
                RequestOptions::HEADERS => [
                    'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                    'Version' => self::API_VERSION
                ]
            ]);

            $advert = \json_decode($response->getBody()->getContents(), true);
            if (!isset($advert['data'])) {
                throw new BadRequestException('Got empty response');
            }

            return $advert['data'];
        } catch (ClientException $e) {
            throw ExceptionFactory::createFromThrowable($e);
        }
    }

    /**
     * @param int $id
     * @return array
     * @throws BadRequestException
     * @throws GuzzleException
     * @throws \Throwable
     */
    public function getStatistics(int $id): array
    {
        try {
            $response = $this->guzzleClient
                ->request('GET', self::OLX_ADVERTS_URL . '/' . $id . '/statistics', [
                    RequestOptions::HEADERS => [
                        'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                        'Version' => self::API_VERSION
                    ]
                ]);

            $advert = \json_decode($response->getBody()->getContents(), true);
            if (!isset($advert['data'])) {
                throw new BadRequestException('Got empty response');
            }

            return $advert['data'];
        } catch (ClientException $e) {
            throw ExceptionFactory::createFromThrowable($e);
        }
    }

    /**
     * @param array $params
     * @return array
     * @throws BadRequestException
     * @throws GuzzleException
     * @throws \Throwable
     */
    public function create(array $params): array
    {
        try {
            $response = $this->guzzleClient->request('POST', self::OLX_ADVERTS_URL, [
                RequestOptions::HEADERS => [
                    'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                    'Version' => self::API_VERSION
                ],
                RequestOptions::JSON => $params
            ]);

            $advert = \json_decode($response->getBody()->getContents(), true);
            if (!isset($advert['data'])) {
                throw new BadRequestException('Got empty response');
            }

            return $advert['data'];
        } catch (ClientException $e) {
            throw ExceptionFactory::createFromThrowable($e);
        }
    }

    /**
     * @param int $offset
     * @param int|null $limit
     * @param string|null $externalId
     * @param array $categoryIds
     * @return array
     * @throws BadRequestException
     * @throws GuzzleException
     * @throws \Throwable
     */
    public function getAll(
        int $offset = 0,
        int $limit = null,
        string $externalId = null,
        array $categoryIds = []
    ): array
    {
        try {
            $params = ['offset' => $offset, 'limit' => $limit];
            if ($externalId) {
                $params['external_id'] = $externalId;
            }
            if (count($categoryIds) > 0) {
                $params['category_ids'] = implode(',', $categoryIds);
            }

            $response = $this->guzzleClient->request('GET', self::OLX_ADVERTS_URL, [
                RequestOptions::HEADERS => [
                    'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                    'Version' => self::API_VERSION
                ],
                RequestOptions::QUERY => $params
            ]);

            $adverts = \json_decode($response->getBody()->getContents(), true);
            if (!isset($adverts['data'])) {
                throw new BadRequestException('Got empty response');
            }

            return $adverts['data'];
        } catch (ClientException $e) {
            throw ExceptionFactory::createFromThrowable($e);
        }
    }

    /**
     * @param int $id
     * @param array $params
     * @return array
     * @throws BadRequestException
     * @throws GuzzleException
     * @throws \Throwable
     */
    public function update(int $id, array $params): array
    {
        try {
            $response = $this->guzzleClient->request('PUT', self::OLX_ADVERTS_URL . '/' . $id, [
                RequestOptions::HEADERS => [
                    'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                    'Version' => self::API_VERSION
                ],
                RequestOptions::JSON => $params
            ]);

            $advert = \json_decode($response->getBody()->getContents(), true);
            if (!isset($advert['data'])) {
                throw new BadRequestException('Got empty response');
            }

            return $advert['data'];
        } catch (ClientException $e) {
            throw ExceptionFactory::createFromThrowable($e);
        }
    }

    /**
     * @param int $id
     * @return void
     * @throws BadRequestException
     * @throws GuzzleException
     * @throws \Throwable
     */
    public function activate(int $id): void
    {
        try {
            $response = $this->guzzleClient
                ->request('POST', self::OLX_ADVERTS_URL . '/' . $id . '/commands', [
                    RequestOptions::HEADERS => [
                        'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                        'Version' => self::API_VERSION
                    ],
                    RequestOptions::JSON => ['command' => 'activate']
                ]);
            throw new BadRequestException($response->getBody()->getContents());

        } catch (ClientException $e) {
            throw ExceptionFactory::createFromThrowable($e);
        }
    }

    /**
     * @param int $id
     * @param bool $isSuccess
     * @throws BadRequestException
     * @throws GuzzleException
     * @throws \Throwable
     */
    public function deactivate(int $id, bool $isSuccess = true): void
    {
        try {
            $response = $this->guzzleClient
                ->request('POST', self::OLX_ADVERTS_URL . '/' . $id . '/commands', [
                    RequestOptions::HEADERS => [
                        'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                        'Version' => self::API_VERSION
                    ],
                    RequestOptions::JSON => ['command' => 'deactivate', 'is_success' => $isSuccess]
                ]);

            throw new BadRequestException($response->getBody()->getContents());
        } catch (ClientException $e) {
            throw ExceptionFactory::createFromThrowable($e);
        }
    }

    /**
     * @param int $id
     * @throws BadRequestException
     * @throws GuzzleException
     * @throws \Throwable
     */
    public function deleteNotActive(int $id): void
    {
        try {
            $response = $this->guzzleClient->request('DELETE', self::OLX_ADVERTS_URL . '/' . $id, [
                RequestOptions::HEADERS => [
                    'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                    'Version' => self::API_VERSION
                ]
            ]);

            throw new BadRequestException($response->getBody()->getContents());
        } catch (ClientException $e) {
            throw ExceptionFactory::createFromThrowable($e);
        }
    }

    /**
     * @param int $id
     * @throws BadRequestException
     * @throws GuzzleException
     * @throws \Throwable
     */
    public function delete(int $id): void
    {
        try {
            $this->deactivate($id);
            $response = $this->guzzleClient->request('DELETE', self::OLX_ADVERTS_URL . '/' . $id, [
                RequestOptions::HEADERS => [
                    'Authorization' => $this->user->getTokenType() . ' ' . $this->user->getAccessToken(),
                    'Version' => self::API_VERSION
                ]
            ]);

            throw new BadRequestException($response->getBody()->getContents());
        } catch (ClientException $e) {
            throw ExceptionFactory::createFromThrowable($e);
        }
    }

}