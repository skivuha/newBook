<?php

 /*
 * Class: CalendarController
 *
 * Calendar. Default page after authorisation.
 */

class CalendarController extends Controller
{

	private $arrRender;
	private $calendarObj;
	private $viewObj;
	private $mArray;
	private $dataObj;

	public function __construct()
	{
		$this->accessToCalendar();
		$this->arrayLang();
		$this->calendarObj = new Calendar();
		$this->viewObj = new View;
		$this->dataObj = Router::getInstance();
	}

 /*
 * Default action. Check employee role and build calendar
 */
	public function indexAction()
	{
		$this->calendarObj->setUserRole($this->userRole);
		$this->calendarObj->setFirstDay($this->getFirstDay());
		$this->calendarObj->setTimeFormat($this->getTimeFormat());

		$this->arrayToPrint();
	}

 /*
 * Get params next or previous month from GET array.
 */
	public function anotherAction()
	{
		$this->calendarObj->setFirstDay($this->getFirstDay());
		$this->calendarObj->setTimeFormat($this->getTimeFormat());
		$this->calendarObj->setUserRole($this->userRole);
		$getParam = false;
		$params = $this->dataObj->getParams();
		if(isset($params))
		{
			$getParam = true;
		}
		$this->calendarObj->setFlagParams($getParam);

		$this->arrayToPrint();
	}

 /*
 * Exit from calendar.
 */
	public function logoutAction()
	{
		session_destroy();
		$this->cookie->remove('code_employee');
		header('Location: '.PATH.'');
	}

 /*
 * Create list of room
 */
	private function listRoom()
	{
		$room = $this->calendarObj->getListRoom();
		foreach($room as $key => $val)
		{
			$arr = array('ROOM_ID' => $val['id_room'],
						 'ROOM_NAME' => $val['name_room']);
			$this->viewObj->addToReplace($arr);
			$this->arrRender['ROOMLIST'] .= $this->viewObj->
			setTemplateFile('room')->renderFile();
		}
	}

 /*
 * Send view array to replace placeholder and print page
 */
	private function arrayToPrint()
	{
		$this->mArray = $this->calendarObj->printCalendar();
		if(true === $this->userRole)
		{
			$this->mArray['ADMIN'] = $this->viewObj
				->setTemplateFile('employee')->renderFile();
		}
		$this->viewObj->addToReplace($this->langArr);
		$this->viewObj->addToReplace($this->mArray);
		$this->viewObj->addToReplace($this->arrRender);
		$this->arrRender['CONTENT'] = $this->viewObj
			->setTemplateFile('calendar')->renderFile();
		$this->listRoom();
		$this->viewObj->addToReplace($this->arrRender);

		$this->arrRender['CONTENT'] = $this->viewObj
			->setTemplateFile('workpage')->renderFile();
		$this->viewObj->addToReplace($this->arrRender);

		$this->viewObj->setTemplateFile('index')->templateRender();
	}
}
?>