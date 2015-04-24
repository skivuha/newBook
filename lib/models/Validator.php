<?php

/*
 * Class: Validator
 *
 */

class Validator
{

  private $value;

  public function __construct()
  {
  }

	 /*
	 * Clear data from user
	 *
	 * @param data: number of string
	 * @return: data
 	 */
  public function clearData($data)
  {
    if (is_array($data))
	{
      return $this->clearDataArr($data);
    }
	else
	{
      $data = trim(strip_tags($data));
      return $data;
    }
  }

 /*
 * Clear data from user
 *
 * @param arr: array of data
 * @return: data or false
 */
  public function clearDataArr(array $arr)
  {
    if (!empty($arr) && is_array($arr))
	{
      foreach ($arr as $key => $value)
	  {
        $data[$key] = $this->clearData($value);
      }
      return $data;
    }
	else
	{
      return false;
    }
  }

 /*
 * Validate name field
 *
 * @param val: check string
 * @return: boolean
 */
  public function checkForm($val)
  {
    $this->value = '';
    $val = $this->clearData($val);
    if (!preg_match("/^[a-zA-Z0-9]*$/", $val))
	{
      return false;
    }
	else
	{
      return true;
    }
  }

 /*
 * Validate password field
 *
 * @param val: check string
 * @return: boolean
 */
  public function checkPass($val)
  {
    $this->value = '';
    $val = $this->clearData($val);
    if (!preg_match("/^[a-zA-Z0-9_-]{6,18}$/", $val))
	{
      return false;
    }
	else
	{
      return true;
    }
  }

 /*
 * To positive number
 *
 * @param val: check string
 * @return: integer
 */
  public function numCheck($val)
  {
      return $this->value = abs((int)($val));
  }

  public function getValue()
  {
    return $this->value;
  }

 /*
 * Validate e-mail
 *
 * @param val: check string
 * @return: boolean
 */
  public function checkEmail($val)
  {
    $this->value = '';
    $val = $this->clearData($val);
    if (!filter_var($val, FILTER_VALIDATE_EMAIL))
	{
      return false;
    } else {
      return true;
    }
  }
}
?>