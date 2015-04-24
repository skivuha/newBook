<?php
include("lib/models/Validator.php");
class ValidatorTest extends PHPunit_Framework_TestCase

{
  protected $validator;
  protected $string;
  protected $number;
  protected $array;
  protected $emptyData;

  function setUp()
  {
    $this->validator =new Validator();
    $this->string = 'mimimi';
    $this->number = 4;
    $this->array = array();
    $this->arrayData = array('test');
    $this->emptyData = '';
  }

  function tearDown()
  {
    $this->validator=NULL;
    $this->string=NULL;
    $this->number=NULL;
    $this->array=NULL;
    $this->arrayData=NULL;
  }

  public function testClearDataTrueString()
  {
    $this->assertTrue(is_string($this->validator->clearData($this->string)));
  }

  public function testClearDataFalseNumber()
  {
    $this->assertTrue(is_string($this->validator->clearData($this->number)));
  }

  public function testClearDataFalseArray()
  {
    $this->assertFalse(is_string($this->validator->clearData($this->array)));
  }

  public function testClearDataArrFalseEmpty()
  {
    $this->assertFalse(is_array($this->validator->clearDataArr($this->array)));
  }

  public function testClearDataArrTrueData()
  {
    $this->assertTrue(is_array($this->validator->clearDataArr($this->arrayData)));
  }

  public function testCheckFormTrue()
  {
    $this->assertTrue($this->validator->checkForm($this->number));
  }

  public function testCheckFormTrueEmpty()
  {
    $this->assertTrue($this->validator->checkForm($this->emptyData));
  }

  public function testCheckFormFalse()
  {
    $this->assertFalse($this->validator->checkForm('$@lala'));
  }
  
  public function testCheckPassFalseMinimumSixValue()
  {
    $this->assertFalse($this->validator->checkPass($this->number));
  }

  public function testCheckPassTrueMinimumSixValue()
  {
    $this->assertTrue($this->validator->checkPass('123456'));
  }

  public function testNumCheckTrue()
  {
    $this->assertTrue(is_int($this->validator->numCheck($this->number)));
  }

  public function testNumCheckFalse()
  {

    $this->assertFalse($this->validator->numCheck($this->array));
    $this->assertFalse($this->validator->numCheck($this->string));
  }

  public function testCheckEmailFalse()
  {
    $this->assertFalse($this->validator->checkEmail($this->number));
    $this->assertFalse($this->validator->checkEmail($this->array));
    $this->assertFalse($this->validator->checkEmail('test@test'));
  }

  public function testCheckEmailTrue()
  {
    $this->assertTrue($this->validator->checkEmail('test@test.com'));
  }
}
?>
