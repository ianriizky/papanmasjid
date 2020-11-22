<?php

namespace Ianrizky\MoslemPray\Facade;

use Ianrizky\MoslemPray\Manager;
use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * @method static \Ianrizky\MoslemPray\Drivers\AbstractDriver driver() Get a driver instance.
 * @method static \Ianrizky\MoslemPray\Drivers\Banghasan banghasan()
 * @method static \Ianrizky\MoslemPray\Contracts\Response\PrayerTime getPrayerTime(int|string $city, \Illuminate\Support\Carbon|null $date)
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
