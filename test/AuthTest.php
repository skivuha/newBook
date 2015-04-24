<?php
include("lib/models/Session.php");
include("config.php");
include("lib/models/MyPdo.php");
include("lib/models/Cookie.php");
include("lib/models/Auth.php");
class CalendarTest extends PHPUnit_Framework_TestCase {
    function setUp()
    {
        $this->calendar = new Calendar();
        $this->string = 'mimimi';
        $this->number = 4;
        $this->array = array();
    }

    function tearDown()
    {
        $this->calendar = null;
    }

    public function testGetCurrentData()
    {
      $this->assertEquals();
    }


}
