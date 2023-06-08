<?php
/******************************************************************************
 * This file is part of the Weather PHP project. You can always find the latest
 * version of this class and project at: https://github.com/ionux/Weather
 *
 * Copyright (c) 2017-2023 Rich Morgan, rich@richmorgan.me
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
 * Generic communication proxy for various communication methods.
 *
 * @author Rich Morgan <rich@richmorgan.me>
 */
trait Curl
{
    private $curlResource = null;

    public function fetchData($URI, $params)
    {
        if (empty($this->curlResource)) {
            $this->initializeCommsObject();
        }

        return $this->retrieve($URI, $params);
    }

    private function initializeCommsObject()
    {
        $this->curlResource = curl_init();

        curl_setopt($this->curlResource, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curlResource, CURLOPT_RETURNTRANSFER, true);
    }
    
    private function retrieve($location, $parameters)
    {
        curl_setopt($this->curlResource, CURLOPT_URL, $location . $parameters);
        $data = curl_exec($this->curlResource);

        $this->disconnect();
        
        return $data;
    }
    
    private function disconnect()
    {
        curl_close($this->curlResource);
    }
}
