<?php

namespace Ianrizky\MoslemPray\Support\Manager;

use Ianrizky\MoslemPray\Drivers\Banghasan;
use Illuminate\Http\Client\Factory;

/**
 * @property \Illuminate\Contracts\Config\Repository $config The configuration repository instance.
 * @property \Illuminate\Contracts\Container\Container $container The container instance.
 *
 * @see \Ianrizky\MoslemPray\Manager
 */
trait BanghasanDriver
{
    /**
     * Create an instance of the Banghasan Driver.
     *
     * @return \Ianrizky\MoslemPray\Drivers\Banghasan
     */
    protected function createBanghasanDriver(): Banghasan
    {
        return new Banghasan(
            $this->config->get('moslempray.banghasan') ?? [],
            $this->container->make(Factory::class)
        );
    }
}
