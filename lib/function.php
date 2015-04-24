<?php
/**
 * Checks a file exists
 *
 * @param file: Name of file
 * @return bool: Return true if file exists, false is not.
 */
function isset_file($file)
{
	$arr = explode(PATH_SEPARATOR, get_include_path());
	foreach ($arr as $val)
	{
		if (file_exists($val . '/' . $file))
		{
			return true;
		}
	}
	return false;
}
?>