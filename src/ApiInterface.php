<?php

namespace Parhomenko\Olx;

use GuzzleHttp\Client;
use Parhomenko\Olx\Api\Districts;
use Parhomenko\Olx\Api\Languages;
use Parhomenko\Olx\Api\Threads;
use Parhomenko\Olx\Api\User;
use Parhomenko\Olx\Api\Categories;
use Parhomenko\Olx\Api\Adverts;
use Parhomenko\Olx\Api\Regions;
use Parhomenko\Olx\Api\Cities;
use Parhomenko\Olx\Api\Currencies;
use Parhomenko\Olx\Api\Users;

/**
 * Class ApiInterface
 *
 * @package Parhomenko\Olx
 */
class ApiInterface implements OlxApiInterface
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var Client
     */
    private $guzzleClient;
    /**
     * @var
     */
    private $categories;
    /**
     * @var
     */
    private $adverts;
    /**
     * @var
     */
    private $cities;
    /**
     * @var
     */
    private $districts;
    /**
     * @var
     */
    private $regions;
    /**
     * @var
     */
    private $currencies;
    /**
     * @var
     */
    private $users;
    /**
     * @var
     */
    private $languages;
    /**
     * @var
     */
    private $threads;

    /**
     * Api constructor.
     *
     * @param string $base_uri
     * @param array $credentials
     * @param bool $update_token
     */
    public function __construct(string $base_uri, array $credentials, bool $update_token = false)
    {
        $this->guzzleClient = new Client(['base_uri' => $base_uri]);
        $this->user = new User($this->guzzleClient, $credentials);
        if ($update_token) {
            $this->user->checkToken();
        }
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Categories
     */
    public function getCategories(): Categories
    {
        if (!$this->categories instanceof Categories) {
            $this->categories = new Categories($this->user, $this->guzzleClient);
        }

        return $this->categories;
    }

    /**
     * @return Adverts
     */
    public function getAdverts(): Adverts
    {
        if (!$this->adverts instanceof Adverts) {
            $this->adverts = new Adverts($this->user, $this->guzzleClient);
        }
        return $this->adverts;
    }

    /**
     * @return Regions
     */
    public function getRegions(): Regions
    {
        if (!$this->regions instanceof Regions) {
            $this->regions = new Regions($this->user, $this->guzzleClient);
        }

        return $this->regions;
    }

    /**
     * @return Cities
     */
    public function getCities(): Cities
    {
        if (!$this->cities instanceof Cities) {
            $this->cities = new Cities($this->user, $this->guzzleClient);
        }

        return $this->cities;
    }

    /**
     * @return Districts
     */
    public function getDistricts(): Districts
    {
        if (!$this->districts instanceof Districts) {
            $this->districts = new Districts($this->user, $this->guzzleClient);
        }

        return $this->districts;
    }

    /**
     * @return Currencies
     */
    public function getCurrencies(): Currencies
    {
        if (!$this->currencies instanceof Currencies) {
            $this->currencies = new Currencies($this->user, $this->guzzleClient);
        }

        return $this->currencies;
    }

    /**
     * @return Users
     */
    public function getUsers(): Users
    {
        if (!$this->users instanceof Users) {
            $this->users = new Users($this->user, $this->guzzleClient);
        }

        return $this->users;
    }

    /**
     * @return Languages
     */
    public function getLanguages(): Languages
    {
        if (!$this->languages instanceof Languages) {
            $this->languages = new Languages($this->user, $this->guzzleClient);
        }

        return $this->languages;
    }

    /**
     * @return Threads
     */
    public function getThreads(): Threads
    {
        if (!$this->threads instanceof Threads) {
            $this->threads = new Threads($this->user, $this->guzzleClient);
        }

        return $this->threads;
    }
}