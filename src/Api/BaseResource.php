<?php

namespace Parhomenko\Olx\Api;

use GuzzleHttp\Client;

/**
 * Class BaseResource
 *
 * @package Parhomenko\Olx\Api
 */
abstract class BaseResource
{
    protected const API_VERSION = '2.0';
    /**
     * @var User
     */
    protected $user;
    /**
     * @var Client
     */
    protected $guzzleClient;

    /**
     * BaseResource constructor.
     *
     * @param User $user
     * @param Client $guzzleClient
     */
    public function __construct(User $user, Client $guzzleClient)
    {
        $this->user = $user;
        $this->guzzleClient = $guzzleClient;
    }
}