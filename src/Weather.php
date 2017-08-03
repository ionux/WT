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
    use Communicator, Formatter;

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
     * Public constructor method.  Must pass the OWM API Key
     * as the first parameter.
     *
     * @param string $api_key  Your OWM API Key
     * @param string $format   The preferred data return format for your app.
     */
    public function construct($api_key = null, $format = 'json')
    {
        if (empty($api_key)) {
            raiseMissingAPIKeyException();
        }

        $this->apiKey = trim(strtolower($api_key));

        switch $format {
            case 'xml':
                $this->format = 'xml';
                break;
            case 'csv':
                $this->format = 'csv';
                break;
            case 'json':
            default:
                $this->format = 'json';
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

        return this->formatData($this->getResource());
    }

    /**
     * Exception handler for constructor.
     *
     * @throws \Exception;
     */
    private function raiseMissingAPIKeyException()
    {
        throw new \Exception();
    }
}
