<?php

 /*
 * Class: Base controller
 *
 */

abstract class Controller
{
	protected $session;
	protected $check;
	protected $cookie;
	protected $userAuth;
	protected $langArr;
	protected $userRole;
	protected $room;

	public function __construct()
	{
		$this->userAuth = false;
		$this->session = Session::getInstance();
		$this->check = new Check();
		$this->checkUser();
		$this->arrayLang();
	}

 /*
 * Get number of room from session
 *
 * @return: number;
 */
	protected function getRoom()
	{
		$this->room = $this->session->getSession('room');
		return $this->room;
	}

 /*
 * Get format start week day from cookie. (monday or sunday)
 *
 * @return: string;
 */
	protected function getFirstDay()
	{
		$this->cookie = new Cookie();
		return $this->cookie->read('user2_firstday');
	}

 /*
 * Get time format from cookie. (12 hour or 24 hour)
 *
 * @return: string;
 */
	protected function getTimeFormat()
	{
		$this->cookie = new Cookie();
		return $this->cookie->read('user2_timeFormat');
	}

 /*
 * Set variable checkUser
 *
 * @return: boolean;
 */
	protected function checkUser()
	{
		return $this->userAuth = $this->check->getUserStatus();
	}

 /*
 * Get lang from cookie. ('en' or 'ru')
 *
 * @return: array;
 */
	protected function arrayLang()
	{
		$this->cookie = new Cookie();
		$lang = $this->cookie->read('langanator');
		$langObj = new Lang($lang);
		$this->langArr = $langObj->getLangArr();
		return $this->langArr;
	}

 /*
 * Get user role from session and set to variable
 */
	protected function accessToCalendar()
	{
		$this->check = new Check();
		$session = Session::getInstance();
		$role = $session->getSession('role');
		$valueRole = md5(1);
		if($valueRole == $role)
		{
			$this->userRole = true;
		}
		else
		{
			$this->userRole = false;
		}
		if(false === $this->check->getUserStatus())
		{
			header('Location: '.PATH.'Home/index', true, 303);
		}
	}

 /*
 * Get user role and redirect if not root
 */
	protected function accessToEmployee()
	{
		$this->accessToCalendar();
		if(false === $this->userRole)
		{
			header('Location: '.PATH.'Home/index', true, 303);
		}
	}
}
?>
