<?php

namespace Ianrizky\MoslemPray\Drivers;

use Illuminate\Http\Client\Factory as Http;

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
     * @param  \Illuminate\Http\Client\Factory  $http
     * @return void
     */
    public function __construct(array $config = [], Http $http)
    {
        $this->mergeConfig($config);

        $this->http = $http;
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
}
