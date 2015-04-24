<?php

 /*
 * Class: Encode
 *
 */

class Encode
{
 /*
 * Generate random string
 *
 * @param var: any string or number for generate
 * @return: string or false
 */
	public function generateCode($var)
	{
		if (is_string($var) || is_int($var))
		{
			$var = md5($var) . SALT;
			$shifr = "";
			$clen = strlen($var) - 1;
			while (strlen($shifr) < STRING_LENGHT)
			{
				$shifr .= $var[mt_rand(0, $clen)];
			}

			return $shifr;
		}
		else
		{
			return false;
		}
	}
}
?>