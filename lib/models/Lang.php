<?php

/*
 * Class: Lang
 *
 */

class Lang
{
	private $file;
	private $data;

	/*
	 * Then create object, read file in variable
	 *
	 * @param lang: name of file
	 */
	public function __construct($lang)
	{
		$this->file = simplexml_load_file(LANG . $lang . '.strings');
		$this->loadData();
	}

 /*
 * Parse file for key -> value
 *
 */
	private function loadData()
	{
		$lang_key = $lang_value = array();
		foreach ($this->file as $key => $val)
		{
			foreach ((array)$val as $key => $val)
			{
				if ('KEY' == $key)
				{
					$lang_key[] = $val;
				}
				else
				{
					$lang_value[] = $val;
				}
			}
		}
		$this->data = array_combine($lang_key, $lang_value);

		return true;
	}

	/*
	 * Return array of key->value
	 * @return: array
	 */
	public function getLangArr()
	{
		return $this->data;
	}
}
?>