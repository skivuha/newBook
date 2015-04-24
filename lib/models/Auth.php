<?php

/*
 * Class: Auth
 *
 * Model a authentication of the system. Check user login and password with
 * DB.
 */

class Auth extends Model
{
	private $error;

	/*
	 * Check date login and password from user form.
	 *
	 * @param var: flag from HomeController array
	 * @return: true or array errors
	 */
	public function logon($var)
	{
		if (true === $var)
		{
			$data_post = $this->validatorObj->clearDataArr($_POST);
			if ('' === $data_post['password'])
			{
				$this->error['ERRORPASS'] = ERROR_EMPTY;
				$pass = false;
			}
			else
			{
				if (false === $this->validatorObj
						->checkPass($data_post['password']))
				{
					$this->error['ERRORPASS'] = ERROR_WRONG_DATA;
					$pass = false;
				}
				else
				{
					$pass = $data_post['password'];
				}
			}
			if ('' === $data_post['name'])
			{
				$this->error['ERRORLOGIN'] = ERROR_EMPTY;
				$name = false;
			}
			else
			{
				if (false === $this->validatorObj
						->checkEmail($data_post['name']))
				{
					$this->error['ERRORLOGIN'] = ERROR_WRONG_DATA;
					$name = false;
				}
				else
				{
					$name = $data_post['name'];
				}
			}
			if (false !== $pass && false !== $name)
			{
				$arr = $this->queryToDbObj->getUserAllDataByName($name);
				if (empty($arr))
				{
					$this->error['ERRORSTATUS'] = ERROR_ACCESS;
				}
				else
				{
					$password = md5($arr[0]['key_employee'] . $pass . SALT);
					if ($arr[0]['passwd_employee'] === $password)
					{
						$this->sessionObj->
						setSession('id_employee', $arr[0]['id_employee']);
						$this->sessionObj->
						setSession('mail_employee', $arr[0]['mail_employee']);
						$this->sessionObj->
						setSession('name_employee', $arr[0]['name_employee']);
						$this->sessionObj->
						setSession('role', md5($arr[0]['role']));
						if (isset($data_post['remember'])
							&& 'on' === $data_post['remember'])
						{
							$code_employee = $this->encodeObj->
							generateCode($arr[0]['name_employee']);
							$this->queryToDbObj
								->setUserCodeCookie($name, $code_employee);
							$this->cookieObj
								->add('code_employee', $code_employee);
						}
						return true;
					}
					else
					{
						$this->error['ERRORSTATUS'] = ERROR_ACCESS;
					}
				}
			}
		}

		return $this->error;
	}
}
?>
