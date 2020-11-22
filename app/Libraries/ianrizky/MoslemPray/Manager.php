<?php

namespace Ianrizky\MoslemPray;

use Ianrizky\MoslemPray\Drivers\Banghasan;
use Ianrizky\MoslemPray\Support\Manager\BanghasanDriver;
use Illuminate\Support\Manager as BaseManager;

class Manager extends BaseManager
{
    use BanghasanDriver;

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return $this->config->get('moslempray.driver', 'banghasan');
    }

    /**
     * Alias method to call createBanghasanDriver().
     *
     * @return \Ianrizky\MoslemPray\Drivers\Banghasan
     */
    public function banghasan(): Banghasan
    {
        return $this->createBanghasanDriver();
    }
}
