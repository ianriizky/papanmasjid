<?php

namespace Ianrizky\MoslemPray\Drivers;

use Ianrizky\MoslemPray\Support\Drivers\Banghasan\ManageSholat;

class Banghasan extends AbstractDriver
{
    use ManageSholat;

    /**
     * List of configuration value.
     *
     * @var array
     */
    protected array $config = [
        /**
         * @api string
         */
        'url' => 'https://api.banghasan.com',

        /**
         * @example format json|array
         */
        'format' => 'json',
    ];
}
