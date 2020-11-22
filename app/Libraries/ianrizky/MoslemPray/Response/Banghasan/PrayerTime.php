<?php

namespace Ianrizky\MoslemPray\Response\Banghasan;

use Closure;
use Ianrizky\MoslemPray\Response\AbstractResponse;
use Ianrizky\MoslemPray\Support\Drivers\Banghasan\TimezoneList;
use Illuminate\Support\Carbon;

/**
 * @property \Illuminate\Support\Carbon $imsak
 * @property \Illuminate\Support\Carbon $subuh
 * @property \Illuminate\Support\Carbon $sunrise
 * @property \Illuminate\Support\Carbon $dhuha
 * @property \Illuminate\Support\Carbon $dzuhur
 * @property \Illuminate\Support\Carbon $ashar
 * @property \Illuminate\Support\Carbon $maghrib
 * @property \Illuminate\Support\Carbon $isya
 */
class PrayerTime extends AbstractResponse
{
    use TimezoneList;

    /**
     * Return list of attribute key with it's data type.
     *
     * @param  array  $items
     * @var array
     */
    public function attributes(array $items): array
    {
        return [
            'imsak' => $this->createCarbon($cityName = $items['city_name']),
            'subuh' => $this->createCarbon($cityName),
            'sunrise' => $this->createCarbon($cityName),
            'dhuha' => $this->createCarbon($cityName),
            'dzuhur' => $this->createCarbon($cityName),
            'ashar' => $this->createCarbon($cityName),
            'maghrib' => $this->createCarbon($cityName),
            'isya' => $this->createCarbon($cityName),
        ];
    }

    /**
     * Return callable function to create \Illuminate\Support\Carbon object.
     *
     * @param  string  $cityName
     * @return \Closure
     */
    protected function createCarbon(string $cityName): Closure
    {
        return function ($value) use ($cityName) {
            return new Carbon($value, $this->getTimezoneList(strtolower($cityName), 'Asia/Jakarta'));
        };
    }
}
