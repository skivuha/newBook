<?php

 /*
 * Class: HomeController
 *
 * Start page. If user success authentication redirect to CalendarController
 * else wait for authentication. If data incorrect, show errors.
 */

class HomeController extends Controller
{
	private $mArray;
	private $view;
	private $auth;

	public function __construct()
	{
		$this->view = new View();
		$this->auth = new Auth();
		$this->check = new Check();
		$this->checkUser();
		$this->arrayLang();
	}

 /*
 * Default action. Check POST array from login form. If Auth model return
 * false, print error and do nothing, if true - send header location
 * Calendar/index.
 */
	public function indexAction()
	{
		$signin = false;
		if (isset($_POST['signin']))
		{
			$signin = true;
		}
		$resultAuth = $this->auth->logon($signin);
		if (true === $resultAuth || true === $this->userAuth)
		{
			header('Location: '.PATH.'Calendar/index', true, 303);
		}
		else
		{
			$this->view->addToReplace($resultAuth);
			$this->mArray['CONTENT'] = $this->view->setTemplateFile('login')
				->renderFile();
			$this->arrayToPrint();
		}
	}

	 /*
	 * Send view array to replace placeholder and print page
	 *
	 */
	private function arrayToPrint()
	{
		$this->view->addToReplace($this->mArray);
		$this->view->addToReplace($this->langArr);
		$this->view->setTemplateFile('index')->templateRender();
	}
}
?>