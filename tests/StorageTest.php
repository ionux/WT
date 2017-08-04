<?php

namespace Tests;

use \Weather\Communicator;
use \Weather\Storage;
use \Weather\Curl;
use \Weather\Weather;

class StorageTest extends \PHPUnit_Framework_TestCase
{
    protected $weather;
    protected $timeNow;
    protected $timeThen;
    protected $interval;
    protected $location;
    protected $mock;

    public function setUp()
    {
        // Mock class for the Storage trait.
        $this->mock = $this->getMockForTrait('\Weather\Storage');
        $this->weather = '{"coord":{"lon":-118.41,"lat":34.09},"weather":[{"id":701,"main":"Mist","description":"mist","icon":"50d"}],"base":"stations","main":{"temp":295.54,"pressure":1014,"humidity":88,"temp_min":294.15,"temp_max":297.15},"visibility":16093,"wind":{"speed":1.5,"deg":190},"clouds":{"all":75},"dt":1501853640,"sys":{"type":1,"id":412,"message":0.0046,"country":"US","sunrise":1501852066,"sunset":1501901460},"id":0,"name":"Beverly Hills","cod":200}';
        $this->timeNow = '11111111';
        $this->interval = '120';
        $this->location = '11111';
    }
    
    public function storeWeatherDataTest()
    {
        $this->mock->storeData();
    }
    
    public function getWeatherDataTest()
    {
        $this->mock->getData();
    }
}
