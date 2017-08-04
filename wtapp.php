<?php

/**
 * Example app for OpenWeatherMap API monitor.
 *
 * Written by Rich Morgan <rich@richmorgan.me>
 */
namespace Weather;

require_once('src/Autoloader.php');

try {
    $wData = new Weather('YOUR OWM API TOKEN HERE');

    /**
     * Register the locations you'd like to monitor along with
     * a second interval pipe-separated.  For example: 15212|120
     * would check the weather for Pittsburgh no earlier than 120
     * seconds after the last check.  Additional location|time pairs
     * should be comma separated.
     */
    //$wData->registerLocations('30127|600,35079|4500,90210|120,15212|805');

    $wData->forecastByReg();
} catch (\Exception $e) {
    error_log($e->getMessage());
}
