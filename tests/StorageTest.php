<?php

namespace Tests;

use \Weather\Communicator;
use \Weather\Storage;
use \Weather\Curl;
use \Weather\Weather;

class StorageTest extends \PHPUnit_Framework_TestCase
{
    protected $a;
    protected $b;
    protected $mock;

    public function setUp()
    {
        // Mock class for the Storage trait.
        $this->mock = $this->getMockForTrait('\Weather\Storage');
    }
    
    public function storeWeatherDataTest()
    {
        $this->mock->storeData();
    }
    
    public function getWeatherDataTest()
    {
    
    }
}
