<?php

namespace Ianrizky\MoslemPray\Drivers;

use Carbon\Carbon;
use Ianrizky\MoslemPray\Contracts\Response\PrayerTime;
use Illuminate\Http\Client\Factory as Http;
use Illuminate\Http\Client\Factory;

abstract class AbstractDriver
{
    /**
     * List of configuration value.
     *
     * @var array
     */
    protected array $config = [];

    /**
     * Laravel Http factory instance.
     *
     * @var \Illuminate\Http\Client\Factory
     */
    protected Http $http;

    /**
     * Create a new instance class.
     *
     * @param  array  $config
     * @param  \Illuminate\Http\Client\Factory|null  $http
     * @return void
     */
    public function __construct(array $config = [], Http $http = null)
    {
        $this->mergeConfig($config);

        $this->http = $http ?? resolve(Factory::class);
    }

    /**
     * Merge given configuration value with existing default configuration.
     *
     * @param  array  $config
     * @return void
     */
    protected function mergeConfig(array $config = [])
    {
        $this->config = array_merge($this->config, array_intersect_key($config, $this->config));
    }

    /**
     * Return url path into API driver.
     *
     * @return string
     */
    protected function getUrl(): string
    {
        return rtrim($this->config['url'], '/');
    }

    /**
     * Return full url path with sub url into API driver.
     *
     * @return string
     */
    protected function getFullUrl(): string
    {
        $subUrl = property_exists($this, 'subUrl') ? rtrim(ltrim($this->subUrl, '/'), '/') : null;

        return $this->config['url'] . ($subUrl ? '/' . $subUrl : null);
    }

    /**
     * Return prayer time based on given city and date.
     *
     * @param  int|string  $city
     * @param  \Carbon\Carbon|null  $date
     * @return \Ianrizky\MoslemPray\Contracts\Response\PrayerTime
     */
    abstract public function getPrayerTime($city, Carbon $date = null): PrayerTime;
}
