<?php
/**
 * Class: Router
 *
 * Parse url for controller, action, params
 */
class Router
{
	private $_controller, $_action, $_params;
	static $_instance;

	public static function getInstance()
	{
		if ( ! (self::$_instance instanceof self))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct()
	{
		Session::getInstance();
		$request = $_SERVER['REQUEST_URI'];
		$splits = explode('/', trim($request, '/'));
		if(!empty($splits[CONTROLLER]))
		{
			$this->_controller = ucfirst($splits[CONTROLLER]).'Controller';
		}
		else
		{
			$this->_controller = 'HomeController';
		}

		if(!empty($splits[ACTION]))
		{
			$this->_action = $splits[ACTION].'Action';
		}
		else
		{
			$this->_action = 'indexAction';
		}

		if (!empty($splits[PARAM]) && ! empty($splits[PARAM + 1]))
		{
			$keys = $values = array();
			for ($i = PARAM, $cnt = count($splits); $i < $cnt; $i ++)
			{
				if (0 != $i % 2)
				{
					$keys[] = $splits[$i];
				}
				else
				{
					$values[] = $splits[$i];
				}
			}
			if (count($keys) == count($values))
			{
				$this->_params = array_combine($keys, $values);
			}
			else
			{
				$this->_controller = 'Error404Controller';
			}
		}
	}

	/**
	 * Load class
	 *
	 */
	public function route()
	{
		if (class_exists($this->getCtrl()))
		{
			$rc = new ReflectionClass($this->getCtrl());
			if ($rc->isSubclassOf('Controller'))
			{
				if ($rc->hasMethod($this->getAction()))
				{
					$controller = $rc->newInstance();
				}
				else
				{
					$this->_controller = 'Error404Controller';
				}
				$method = $rc->getMethod($this->getAction());
				$method->invoke($controller);
			}
			else
			{
				$this->_controller = 'Error404Controller';
			}
		}
	}

	public function getParams()
	{
		return $this->_params;
	}

	public function getCtrl()
	{
		return $this->_controller;
	}

	public function getAction()
	{
		return $this->_action;
	}
}
?>
