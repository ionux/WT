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
class Helper
{
    use Communicator, Formatter, Storage;

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
     * Format of returned data, i.e. xml, json, csv.
     *
     * @var string
     */
    private $returnFormat = 'json';

    /**
     * Array of locations to check at specified intervals.
     *
     * @var array
     */
    private $locationData = array();

    /**
     * Public constructor method.  Must pass the OWM API Key
     * as the first parameter.
     *
     * @param string $api_key  Your OWM API Key
     * @param string $format   The preferred data return format for your app.
     */
    public function __construct($api_key = null, $format = '')
    {
        if (empty($api_key)) {
            raiseMissingAPIKeyException();
        }

        date_default_timezone_set('UTC');

        $this->apiKey = trim(strtolower($api_key));

        switch ($format) {
            case 'xml':
                $this->returnFormat = 'xml';
                break;
            case 'csv':
                $this->returnFormat = 'csv';
                break;
            case 'json':
            default:
                $this->returnFormat = 'json';
        }
    }

    /**
     * Helper function to retrieve weather data by zip code.
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

        $weatherData = $this->formatData($this->getResource($this->apiURI, '?zip=' . $zip_code . ',us&APPID=' . $this->apiKey), $this->returnFormat);

        $this->storeWeatherData($weatherData, $zip_code);

        return $weatherData;
    }

    public function forecastByReg()
    {
        $regData_raw = file_get_contents('location_data.txt');
        $regData_arr = explode(',', $regData_raw);

        foreach ($regData_arr as $key => $val) {
            $temp_loc = explode('|', $val);

            if (file_exists($temp_loc[0] . '_weather_data.txt')) {
                $temp_data     = file_get_contents($temp_loc[0] . '_weather_data.txt');
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
        //file_put_contents('location_data.txt', $zip_codes, LOCK_EX);
        $this->storeData($zip_codes, 'locations');
    }

    public function retrieveLocations()
    {
        //return file_get_contents('location_data.txt');
        return $this->getData('locations');
    }

    public function storeWeatherData($weather, $location)
    {
        //file_put_contents($location . '_weather_data.txt', $weather . '|' . time(), LOCK_EX);
        $this->storeData($weather . '|' . $location, 'weather')
    }
}
