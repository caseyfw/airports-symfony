<?php

namespace App\Service;

class Airports
{
    /**
     * @var array $airports The airports data store.
     */
    private $airports;

    public function __construct()
    {
        $this->airports = json_decode(
            file_get_contents(getenv('AIRPORTS_JSON_PATH')),
            true
        );
    }

    public function airports() : array
    {
        return $this->airports;
    }

    public function airport(string $code) : ?array
    {
        foreach ($this->airports as $airport) {
            if ($airport['code'] === $code) {
                return $airport;
            }
        }
        return null;
    }

    public function cities() : array
    {
        $cities = [];
        foreach ($this->airports as $airport) {
            $cities[$airport['city']['code']] = $airport['city'];
        }
        return array_values($cities);
    }

    public function city(string $code) : ?array
    {
        // Collate airports associated with given city.
        $airportsNearCity = [];
        foreach ($this->airports as $airport) {
            if ($airport['city']['code'] === $code) {
                if (!isset($city)) {
                    $city = $airport['city'];
                }
                $airportsNearCity[] = $airport;
            }
        }

        if (!isset($city)) {
            return null;
        }

        // Strip city key from nearby airports.
        $city['airports'] = array_map(function ($airport) {
            unset($airport['city']);
            return $airport;
        }, $airportsNearCity);

        return $city;
    }
}
