<?php

namespace Ianrizky\MoslemPray;

use DomainException;
use Ianrizky\MoslemPray\Drivers\AbstractDriver;
use Ianrizky\MoslemPray\Drivers\Banghasan;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Arr;

/**
 * @method \Ianrizky\MoslemPray\Contracts\Response\PrayerTime getPrayerTime(string|int $city, \Carbon\Carbon|null $date)
 */
class MoslemPray
{
    /**
     * Driver instance object.
     *
     * @var \Ianrizky\MoslemPray\Drivers\AbstractDriver
     */
    protected AbstractDriver $driver;

    /**
     * Create a new instance class.
     *
     * @param  string|array  $driverName
     * @param  array  $config
     * @param  \Illuminate\Http\Client\Factory  $http
     * @return void
     */
    public function __construct($driverName = 'banghasan', array $config = [], Factory $http = null)
    {
        if (is_array($driverName)) {
            $driverName = $this->getDriverNameFromConfig($driverName);

            $config = Arr::except($config, 'driver');
        }

        $this->driver = $this->createDriverInstance($driverName, $config, $http);
    }

    /**
     * Return driver name value from given config.
     *
     * @param  array  $config
     * @return string
     *
     * @throws \DomainException
     */
    protected function getDriverNameFromConfig(array $config): string
    {
        $driverName = $config['driver'] ?? null;

        switch ($driverName) {
            case 'banghasan':
                return $driverName;

            default:
                throw new DomainException('Driver name is unidentified');
        }
    }

    /**
     * Create driver instance based on given driver name.
     *
     * @param  string  $driverName
     * @param  array  $config
     * @param  \Illuminate\Http\Client\Factory  $http
     * @return \Ianrizky\MoslemPray\Drivers\AbstractDriver
     */
    protected function createDriverInstance(string $driverName, array $config, Factory $http = null): AbstractDriver
    {
        switch ($driverName) {
            case 'banghasan':
            default:
                return new Banghasan($config, $http);
        }
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return void
     */
    public function __call($method, $parameters)
    {
        return $this->driver->$method(...$parameters);
    }
}
