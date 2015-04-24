<?php
include("lib/models/Session.php");
class SessionTest extends PHPUnit_Framework_TestCase {
    function setUp()
    {
        $this->session =new Session();
        $this->string = 'mimimi';
        $this->number = 4;
        $this->array = array();
    }

    function tearDown()
    {
        $this->session = null;
    }

    public function testSession()
    {
      $this->assertTrue(is_string($this->session->getSession($this->string)));
      $this->assertFalse(is_string($this->session->getSession($this->array)));
      $this->assertTrue(is_string($this->session->getSession($this->number)));
    }
}
 
