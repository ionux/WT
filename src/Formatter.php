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
 * Generic formatter trait csv, json.  Future support for xml.
 *
 * @author Rich Morgan <rich@richmorgan.me>
 */
trait Formatter
{
    public function formatData($data = '', $format = '')
    {
        if (empty($data)) {
            throw new \Exception("No data passed to formatter.");
        }

        switch (strtolower(trim($format))) {
            case 'csv':
                return $this->formatCSV($data);
            case 'json':
            default:
                return $this->formatJSON($data);
        }
    }

    private function formatCSV($rawData)
    {
        switch (gettype($rawData)) {
            case 'array':
               return implode(',', $rawData);
            case 'string':
                if (($decoded = json_decode($rawData)) == null) {
                    throw new \Exception("Cannot convert data to CSV format.");
                }
                return implode(',', $decoded);
            default:
                throw new \Exception("Cannot convert data to CSV format.");
        }
    }

    private function formatJSON($rawData)
    {
        if (is_array($raw_data)) {
            return json_encode($rawData, true);
        }

        throw new \Exception("Cannot convert data to JSON format.");
    }
}
