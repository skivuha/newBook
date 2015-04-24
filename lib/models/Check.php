<?php

 /*
 * Class: Check
 * All main check before choose controller
 *
 */

class Check
{
	private $validObj;
	private $cookieObj;
	private $session;
	private $queryToDbObj;
	private $redirect;
	private $data;

	public function __construct()
	{
		$this->validObj = new Validator();
		$this->data = Router::getInstance();
		$this->cookieObj = new Cookie();
		$this->session = Session::getInstance();
		$this->queryToDbObj = new QueryToDb();
		$this->choiseLang();
		$this->setFirstDay();
		$this->setTimeFormat();
		$this->setRoom();
	}

	 /*
	 * Set in cookie first day of week from POST array. (sunday or monday)
	 *
	 */
	private function setFirstDay()
	{
		$this->redirect();
		$post_clear = $this->validObj->clearDataArr($_POST);
		if ('sunday' === $post_clear['firstday'])
		{
			$this->cookieObj->add('user2_firstday', 'sunday');
			header('Location:'.$this->redirect);
		}
		elseif ('monday' === $post_clear['firstday'])
		{
			$this->cookieObj->add('user2_firstday', 'monday');
			header('Location:'.$this->redirect);
		}
	}

	 /*
	 * Set in cookie time format from POST array. (24 or 12 hour)
	 *
	 */
	private function setTimeFormat()
	{
		if (!isset($_COOKIE['user2_timeFormat']))
		{
			$this->cookieObj->add('user2_timeFormat', '24h');
		}
		else
		{
			$this->redirect();
			$post_clear = $this->validObj->clearDataArr($_POST);
			if ('12h' === $post_clear['timeFormat'])
			{
				$this->cookieObj->add('user2_timeFormat', '12h');
				header('Location:'.$this->redirect);
			}
			elseif ('24h' === $post_clear['timeFormat'])
			{
				$this->cookieObj->add('user2_timeFormat', '24h');
				header('Location:'.$this->redirect);
			}
		}
	}

	/*
	 * Set in session number of default room.
	 * If isset params room, set current number.
	 *
	 */
	private function setRoom()
	{
		if (!isset($_SESSION['room']))
		{
			$this->session->setSession('room', '1');
		}
		else
		{
			$this->redirect();
			$params = $this->data->getParams();
			if (isset($params['room']))
			{
				$params = abs((int)$params['room']);
				$this->session->setSession('room', $params);
				header('Location:' . $this->redirect);
			}
		}
	}

	/*
	 * If not set cookie 'langanator', set default 'en'
	 * If isset POST array leng, set current leng.
	 *
	 */
	private function choiseLang()
	{
		if (!isset($_COOKIE['langanator']))
		{
			$this->cookieObj->add('langanator', 'en');
		}
		else
		{
			$this->redirect();
			$post_clear = $this->validObj->clearDataArr($_POST);
			if ('ru' === $post_clear['leng'])
			{
				$this->cookieObj->add('langanator', 'ru');
				header('Location:' . $this->redirect);
			}
			elseif ('en' === $post_clear['leng'])
			{
				$this->cookieObj->add('langanator', 'en');
				header('Location:' . $this->redirect);
			}
		}
	}

	/*
	 * If not set cookie 'langanator', set default 'en'
	 * If isset POST array leng, set current leng.
	 *
	 */
	private function redirect()
	{
		if (isset($_SERVER['HTTP_REFERER']))
		{
			$this->redirect = $_SERVER['HTTP_REFERER'];
		}
		else
		{
			$this->redirect = PATH;
		}
	}

	/*
	 * Get user status
	 * Check session and cookie. If false, set authentication page.
	 *
	 * @return: boolean
	 */
	public function getUserStatus()
	{
		if (isset($_SESSION['id_employee'])
			&& isset($_SESSION['name_employee'])
			&& isset($_SESSION['mail_employee'])
			&& isset($_SESSION['role']))
		{
			return true;
		}
		else
		{
			if (isset($_COOKIE['code_employee']))
			{
				$code_user = $this->validObj
					->clearData($_COOKIE['code_employee']);
				$arr = $this->queryToDbObj->getUserAllDataByCode($code_user);
				if (!empty($arr))
				{
					$this->session->setSession('id_employee',
						$arr[0]['id_employee']);
					$this->session->setSession('mail_employee',
						$arr[0]['mail_employee']);
					$this->session->setSession('name_employee',
						$arr[0]['name_employee']);
					$this->session->setSession('role', md5($arr[0]['role']));
					$this->cookieObj->add("code_employee", $code_user);
				}
				else
				{
					$this->cookieObj->remove('code_employee');

					return false;
				}
			}
			else
			{
				$this->cookieObj->remove('code_employee');

				return false;
			}
		}

		return true;
	}
}
?>
