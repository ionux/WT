<?php
/******************************************************************************
 * This file is part of the Weather PHP project. You can always find the latest
 * version of this class and project at: https://github.com/ionux/Weather
 *
 * Copyright (c) 2017 Rich Morgan, rich@richmorgan.me
 *
 * The MIT License (MIT)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 ******************************************************************************/

namespace Weather;

/**
 * Helper class to wrap the Open WeatherMap API.
 *
 * @author Rich Morgan <rich@richmorgan.me>
 */
class Weather
{
    use Communicator, Storage;

    /**
     * Application API Key from your OpenWeatherMap account.
     *
     * @var string
     */
    private $apiKey = '';

    /**
     * Current OWM API Endpoint.
     * I.e. api.openweathermap.org/data/2.5/weather?zip={zip code},{country code} 
     *
     * @var string
     */
    private $apiURI = 'http://api.openweathermap.org/data/2.5/weather';

    /**
     * Public constructor method.  Must pass the OWM API Key
     * as the first parameter.
     *
     * @param string $api_key  Your OWM API Key
     */
    public function __construct($api_key = null)
    {
        if (empty($api_key)) {
            raiseMissingAPIKeyException();
        }

        date_default_timezone_set('UTC');

        $this->apiKey = trim(strtolower($api_key));
    }

    /**
     * Helper function to retrieve & store weather data by zip code.
     *
     * @param string $zip_code  The zip code you wish to look up.
     * @return string $weatherData  String containing weather information for the requested zip.
     * @throws \Exception
     */
    public function forcastByZip($zip_code = '')
    {
        if (empty($zip_code)) {
            throw new \Exception('Missing or invalid zip code parameter.');            
        }

        $weatherData = $this->getResource($this->apiURI, '?zip=' . $zip_code . ',us&APPID=' . $this->apiKey);

        $this->storeData('weather', $weatherData . '|' . $zip_code);

        return $weatherData;
    }

    private function formatData($data, $format)
    {
        return $data;
    }

    public function forecastByReg()
    {
        $regData_raw = $this->getData('locations');
        $regData_arr = explode(',', $regData_raw);

        foreach ($regData_arr as $key => $val) {
            $temp_loc = explode('|', $val);

            if (file_exists($temp_loc[0] . '_weather_data.txt')) {
                $temp_data     = $this->getData('weather', $temp_loc[0]);
                $temp_data_arr = explode('|', $temp_data);

                if ((time() - intval($temp_data_arr[1])) > intval($temp_loc[1])) {
                    $this->forcastByZip($temp_loc[0]);
                    sleep(1);
                }
            }
        }
    }

    /**
     * Exception handler for constructor.
     *
     * @throws \Exception;
     */
    private function raiseMissingAPIKeyException()
    {
        throw new \Exception("Missing or invalid OpenWeatherMap API key parameter.  Cannot continue.");
    }

    public function registerLocations($zip_codes)
    {
        $this->storeData('locations', $zip_codes);
    }

    public function retrieveLocations()
    {
        return $this->getData('locations');
    }

    public function storeWeatherData($weather, $location)
    {
        $this->storeData('weather', $weather . '|' . $location);
    }
}
