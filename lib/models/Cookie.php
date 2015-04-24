<?php

/*
 * Class: Cookie
 *
 */

class Cookie
{
 /*
 * Add cookie for one week
 *
 * @param key: key of cookie
 * @param val: value of cookie
 */
    public function add($key, $val)
    {
        $_COOKIE[$key]=$val;
        setcookie($key, $val, time()+3600*24*7, '/');
    }

 /*
 * Read cookie
 *
 * @param key: key of cookie
 * @return: value of current cookie key or false
 */
    public function read($key)
    {
		if(isset($_COOKIE[$key]))
		{
			return $_COOKIE[$key];
		}
		else
		{
			return false;
		}
    }

 /*
 * Remove cookie
 *
 * @param key: key of cookie
 */
    public function remove($key)
    {
        setcookie($key, '', -1,'/');
    }
}
?>