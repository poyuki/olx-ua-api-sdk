<?php

namespace Parhomenko\Olx\Exceptions;

use Exception;

/**
 * Class ExceptionFactory
 *
 * @package Parhomenko\Olx\Exceptions
 */
class ExceptionFactory
{

    /**
     * @param Exception $e
     * @throws BadRequestException
     * @throws CallLimitException
     * @throws ForbiddenException
     * @throws NotAcceptableException
     * @throws NotFoundException
     * @throws ServerException
     * @throws UnauthorizedException
     * @throws UnsupportedMediaTypeException
     * @throws ValidationException
     */
    public static function throw(Exception $e): void
    {
        $response = \json_decode($e->getResponse()->getBody()->getContents(), false);

        switch ($e->getCode()) {
            case 400:
                if ($response && $response->error && !empty($response->error->validation)) {
                    throw new ValidationException(
                        $response->error->detail,
                        $e->getCode(),
                        null,
                        $response->error->title,
                        $response->error->detail,
                        $response->error->validation
                    );
                }

                throw new BadRequestException(
                    $response->error->title ?? $e->getMessage(),
                    $e->getCode(),
                    null,
                    $response->error->title ?? null,
                    $response->error->detail ?? null
                );
            case 401:
                throw new UnauthorizedException(
                    $response->error_description ?? $e->getMessage(),
                    $e->getCode(),
                    null,
                    $response->error_description ?? null,
                    $response->error_human_title ?? null
                );
            case 403:
                throw new ForbiddenException($e->getMessage(), $e->getCode());
            case 404:
                throw new NotFoundException(
                    $response->error->detail ?? $e->getMessage(),
                    $e->getCode(),
                    null,
                    $response->error->detail ?? null,
                    $response->error->title ?? null
                );
            case 406:
                throw new NotAcceptableException($e->getMessage(), $e->getCode());
            case 415:
                throw new UnsupportedMediaTypeException($e->getMessage(), $e->getCode());
            case 429:
                throw new CallLimitException($e->getMessage(), $e->getCode());
            case 500:
                throw new ServerException($e->getMessage(), $e->getCode());
        }
    }


    /**
     * @param Exception $e
     * @return \Throwable
     */
    public static function createFromThrowable(\Throwable $e): \Throwable
    {
        $response = \json_decode($e->getResponse()->getBody()->getContents(), false);

        switch ($e->getCode()) {
            case 400:
                if ($response && $response->error && !empty($response->error->validation)) {
                    return new ValidationException(
                        $response->error->detail,
                        $e->getCode(),
                        null,
                        $response->error->title,
                        $response->error->detail,
                        $response->error->validation
                    );
                }

                return new BadRequestException(
                    $response->error->title ?? $e->getMessage(),
                    $e->getCode(),
                    null,
                    $response->error->title ?? null,
                    $response->error->detail ?? null
                );
            case 401:
                return new UnauthorizedException(
                    $response->error_description ?? $e->getMessage(),
                    $e->getCode(),
                    null,
                    $response->error_description ?? null,
                    $response->error_human_title ?? null
                );
            case 403:
                return new ForbiddenException($e->getMessage(), $e->getCode());
            case 404:
                return new NotFoundException(
                    $response->error->detail ?? $e->getMessage(),
                    $e->getCode(),
                    null,
                    $response->error->detail ?? null,
                    $response->error->title ?? null
                );
            case 406:
                return new NotAcceptableException($e->getMessage(), $e->getCode());
            case 415:
                return new UnsupportedMediaTypeException($e->getMessage(), $e->getCode());
            case 429:
                return new CallLimitException($e->getMessage(), $e->getCode());
            case 500:
                return new ServerException($e->getMessage(), $e->getCode());
            default:
                return $e;
        }
    }
}