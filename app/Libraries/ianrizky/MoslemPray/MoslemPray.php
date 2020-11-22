<?php

namespace Ianrizky\MoslemPray;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * @method static \Ianrizky\MoslemPray\Drivers\AbstractDriver driver() Get a driver instance.
 * @method static \Ianrizky\MoslemPray\Drivers\Banghasan banghasan()
 *
 * @see \Ianrizky\MoslemPray\Manager
 */
class MoslemPray extends BaseFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return Manager::class;
    }
}
