<?php

 /*
 * Class: Base model
 *
 */

abstract class Model
{
	protected $validatorObj;
	protected $encodeObj;
	protected $queryToDbObj;
	protected $cookieObj;
	protected $sessionObj;
	protected $data;

	public function __construct()
	{
		$this->sessionObj = Session::getInstance();
		$this->queryToDbObj = new QueryToDb;
		$this->validatorObj = new Validator();
		$this->encodeObj = new Encode();
		$this->cookieObj = new Cookie();
		$this->data = Router::getInstance();
	}
}
?>