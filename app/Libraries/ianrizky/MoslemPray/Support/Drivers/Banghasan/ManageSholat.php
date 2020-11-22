<?php

namespace Ianrizky\MoslemPray\Support\Drivers\Banghasan;

use Ianrizky\MoslemPray\Response\Banghasan\City;
use Ianrizky\MoslemPray\Response\Banghasan\PrayerTime;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @property array $config List of configuration value.
 * @property \Illuminate\Http\Client\Factory $http Laravel Http factory instance.
 * @method string getUrl() Return url path into API driver.
 * @method string getFullUrl() Return full url path with sub url into API driver.
 *
 * @see \Ianrizky\MoslemPray\Drivers\Banghasan
 */
trait ManageSholat
{
    /**
     * Sub url path into the API driver.
     *
     * @var string
     */
    protected string $subUrl = 'sholat';

    /**
     * Return full url path with sub url into API driver.
     *
     * @return string
     */
    protected function getFullUrl(): string
    {
        return parent::getFullUrl() . '/format/' . $this->config['format'];
    }

    /**
     * Return city information based on specified code.
     *
     * @param  int  $code
     * @return \Ianrizky\MoslemPray\Response\Banghasan\City
     */
    public function getCityCode(int $code): City
    {
        $response = $this->http->get($this->getFullUrl() . '/kota/kode/' . $code);

        return $this->createCityResponse($response->json());
    }

    /**
     * Return city information based on specified name.
     *
     * @param  string  $name
     * @return \Ianrizky\MoslemPray\Response\Banghasan\City
     */
    public function getCityName(string $name): City
    {
        $response = $this->http->get($this->getFullUrl() . '/kota/nama/' . $name);

        return $this->createCityResponse($response->json());
    }

    /**
     * Return list of all city.
     *
     * @return \Illuminate\Support\Collection|\Ianrizky\MoslemPray\Response\Banghasan\City[]
     */
    public function getCities()
    {
        $response = $this->http->get($this->getFullUrl() . '/kota');

        if ($response->json('status') === 'error') {
            $this->throwRequestException($response);
        }

        $cities = collect([]);

        foreach ($response->json('kota') as $city) {
            $cities->push($this->createCityResponse($city));
        }

        return $cities;
    }

    /**
     * Return prayer time based on given city and date.
     *
     * @param  int|string  $city
     * @param  \Illuminate\Support\Carbon|null  $date
     * @return \Ianrizky\MoslemPray\Response\Banghasan\PrayerTime
     */
    public function getPrayerTime($city, Carbon $date = null): PrayerTime
    {
        if (is_string($city)) {
            $city = $this->getCityName($city);
        } elseif (is_int($city)) {
            $city = $this->getCityCode($city);
        }

        $date = $date ?? Carbon::today();

        $response = $this->http->get($this->getFullUrl() . '/jadwal/kota/' . $city->id . '/tanggal/' . $date->format('Y-m-d'));

        return $this->createPrayerTimeResponse($response->json(), $city);
    }

    /**
     * Return city response object from given array.
     *
     * @param  array  $json
     * @return \Ianrizky\MoslemPray\Response\Banghasan\City
     *
     * @throws \Illuminate\Http\Client\HttpClientException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function createCityResponse(array $json): City
    {
        if ($json['status'] === 'error') {
            throw new HttpClientException($json['pesan']);
        }

        if ($city = head($json['kota'])) {
            [$id, $name] = array_values($city);

            return new City(compact('id', 'name'));
        }

        throw new NotFoundHttpException('City not found');
    }

    /**
     * Return prayer time response object from given array.
     *
     * @param  array  $json
     * @param  \Ianrizky\MoslemPray\Response\Banghasan\City $city
     * @return \Ianrizky\MoslemPray\Response\Banghasan\PrayerTime
     *
     * @throws \Illuminate\Http\Client\HttpClientException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function createPrayerTimeResponse(array $json, City $city): PrayerTime
    {
        if ($json['status'] === 'error') {
            throw new HttpClientException($json['pesan']);
        }

        if ($data = data_get($json, 'jadwal.data')) {
            [
                $imsak, $subuh, $sunrise, $dhuha,
                $dzuhur, $ashar, $maghrib, $isya,
                $city_name,
            ] = [
                $data['imsak'], $data['subuh'], $data['terbit'], $data['dhuha'],
                $data['dzuhur'], $data['ashar'], $data['maghrib'], $data['isya'],
                $city->name,
            ];

            return new PrayerTime(compact(
                'imsak', 'subuh', 'sunrise', 'dhuha',
                'dzuhur', 'ashar', 'maghrib', 'isya',
                'city_name',
            ));
        }

        throw new NotFoundHttpException('Prayer time not found');
    }
}
