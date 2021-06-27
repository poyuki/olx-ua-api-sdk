<?php

namespace Parhomenko\Olx;

use Parhomenko\Olx\Exceptions\UnknownCountryException;

/**
 * Class OlxFactory
 *
 * @package Parhomenko\Olx
 */
final class OlxFactory
{
    public const UA = 'ua';
    public const PL = 'pl';
    public const BG = 'bg';
    public const RO = 'ro';
    public const KZ = 'kz';
    public const PT = 'pt';

    /**
     * @param string $country_code
     * @param array $credentials
     * @param bool $update_token
     * @return ApiInterface
     * @throws UnknownCountryException
     */
    public static function create(string $country_code, array $credentials, bool $update_token = false): ApiInterface
    {
        $links = [
            self::UA => 'https://www.olx.ua/',
            self::PL => 'https://www.olx.pl/',
            self::BG => 'https://www.olx.bg/',
            self::RO => 'https://www.olx.ro/',
            self::KZ => 'https://www.olx.kz/',
            self::PT => 'https://www.olx.pt/',
        ];

        if (array_key_exists($country_code, $links)) {
            return new ApiInterface($links[$country_code], $credentials, $update_token);
        }

        throw new UnknownCountryException("Country does not supported");
    }
}
