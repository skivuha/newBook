<?php

 /*
 * Class: View
 *
 */

class View
{
	private $forRender;
	private $file;
	private $template;

	public function __construct()
	{
		$this->forRender = array();
	}

 /*
 * Take incoming file, read and save file value in to variable
 *
 * @param template: name of file
 * @return: this or throw exception
 */
	public function setTemplateFile($template)
	{
		$this->template = $template;
		$template = TEMPLATE . $template . '.html';
		if (is_file($template))
		{
			$this->file = file_get_contents($template);

			return $this;
		}
		else
		{
			throw new Exception('No template file');
		}
	}

	/*
	 * Adding incoming array to replace
	 *
	 * @param mArray: array
	 */
	public function addToReplace($mArray)
	{
		if (is_array($mArray))
		{
			foreach ($mArray as $key => $val)
			{
				$this->forRender[$key] = $val;
			}
		}
	}

 /*
 * Send to ajax data
 *
 * @param arr: array
 */
	public function ajax($arr)
	{
		echo json_encode($arr);
	}

 /*
 * Rendering template for replace placeholder 'LANG_'
 *
 */
	private function langRender()
	{
		foreach ($this->forRender as $key => $val)
		{
			$this->file = preg_replace('/%LANG_'.$key.'%/i',
				$val, $this->file);
		}
	}

 /*
 * Rendering template for replace placeholder
 *
 * @return: string variable 'file'
 */
	public function renderFile()
	{
		foreach ($this->forRender as $key => $val)
		{
			$this->file = preg_replace('/{{'.$key.'}}/i', $val, $this->file);
		}
		return $this->file;
	}

 /*
 * Rendering template for unknow placeholder and print our template
 */
	public function templateRender()
	{
		$this->renderFile();
		$this->langRender();

		$default = '';
		$this->file = preg_replace('/{{(.*)}}/Uis', $default, $this->file);
		echo $this->file;
	}
}
?>