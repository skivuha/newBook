<?php

/*
 * Class: Session
 *
 */

class Session
{
    static $_instance;
    private $error;

    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct()
    {
        session_start();
        $this->error='';
    }

	/*
	 * Add session
	 *
	 * @param key: key of session
	 * @param val: value of session
	 */
    public function setSession($key, $val)
    {
        $_SESSION[$key]=$val;
    }

 /*
 * Read session
 *
 * @param key: key of session
 * @return: value of current session key or false
 */
    public function getSession($key)
    {
        if(isset($_SESSION[$key]))
        {
            return $_SESSION[$key];
        }
        else
        {
            return false;
        }
    }

	 /*
	 * Remove session
	 *
	 * @param key: key of session
	 */
    public function removeSession($key)
    {
        unset($_SESSION[$key]);
    }
}
