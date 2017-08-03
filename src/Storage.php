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
 * Helper to persist the weather and location data.
 *
 * @author Rich Morgan <rich@richmorgan.me>
 */
 trait Storage
 {
     /**
      * @var string
      */
     private $locationFile = 'location_data.txt';

     /**
      * @var string
      */
     private $weatherFile = '_weather_data.txt';

     /**
      * Persists data to file depending on data type.
      *
      * @param  $type   string          The type of data you want to persist.
      * @param  $params string          The data itself.
      * @return         integer|string  Either the number of bytes written or error message.
      */
     public function storeData($type, $params = '')
     {
         $filename = '';

         switch (strtolower(trim($type))) {
             case 'locations':
                 $filename = $this->$locationFile;
                 break;
             case 'weather':
                 $temp_params = explode('|', $params);
                 if (count($temp_params) > 1) {
                     $filename = $temp_params[1] . $this->weatherFile;
                     $params   = $temp_params[0] . '|' . time();
                 }
                 return;
             default:
                 // Empty
         }

         return ($filename != '')
             ? file_put_contents($filename, $params, LOCK_EX)
             : 'Cannot write data to file. Missing or invalid file name.';
     }

     /**
      * Retrieves data from storage depending on data type.
      *
      * @param  $type   string  The type of data you want to retrieve.
      * @param  $params string  Additional information to identify storage area.
      * @return         string  The data requested or an error message.
      */
     public function getData($type, $params = '')
     {
         $filename = '';

         switch (strtolower(trim($type))) {
             case 'locations':
                 $filename = $this->locationFile;
                 break;
             case 'weather':
                 $filename = $params . $this->weatherFile;
                 break;
             default:
                 // Empty
         }

         return (file_exists($filename))
             ? file_get_contents($filename)
             : 'Error retrieving data for ' . $type;
     }
 }
