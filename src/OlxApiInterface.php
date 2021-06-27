<?php

namespace Parhomenko\Olx;

use Parhomenko\Olx\Api\Adverts;
use Parhomenko\Olx\Api\Categories;
use Parhomenko\Olx\Api\Cities;
use Parhomenko\Olx\Api\Currencies;
use Parhomenko\Olx\Api\Districts;
use Parhomenko\Olx\Api\Languages;
use Parhomenko\Olx\Api\Regions;
use Parhomenko\Olx\Api\Threads;
use Parhomenko\Olx\Api\User;
use Parhomenko\Olx\Api\Users;

/**
 * Interface OlxApiInterface
 *
 * @package Parhomenko\Olx
 */
interface OlxApiInterface
{
    /**
     * @return User
     */
    public function getUser(): User;

    /**
     * @return Categories
     */
    public function getCategories(): Categories;

    /**
     * @return Adverts
     */
    public function getAdverts(): Adverts;

    /**
     * @return Regions
     */
    public function getRegions(): Regions;

    /**
     * @return Cities
     */
    public function getCities(): Cities;

    /**
     * @return Districts
     */
    public function getDistricts(): Districts;

    /**
     * @return Currencies
     */
    public function getCurrencies(): Currencies;

    /**
     * @return Users
     */
    public function getUsers(): Users;

    /**
     * @return Languages
     */
    public function getLanguages(): Languages;

    /**
     * @return Threads
     */
    public function getThreads(): Threads;
}