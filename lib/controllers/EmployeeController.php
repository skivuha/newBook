<?php

 /*
 * Class: EmployeeController
 *
 * Admin page. Only admin can run this page. Default page load by index action.
 */

class EmployeeController extends Controller
{
	private $data;
	private $employee;
	private $arrRender;
	private $view;

	public function __construct()
	{
		$this->data = Router::getInstance();
		$this->employee = new Employee();
		$this->view = new View;
		$this->valid = new Validator();
		$this->accessToCalendar();
		$this->accessToEmployee();
		$this->arrayLang();
	}

 /*
 * Default action. Check POST array from form. If isset POST, check data and
 * if all data valid, add new employee. Else show errors massage.
 */
	public function indexAction()
	{
		if (isset($_POST['submit']))
		{
			$addEmployee = $this->valid->clearDataArr($_POST);
			$this->employee->setDataArray($addEmployee);
			$this->employee->setFlag(false);
			$employee = $this->employee->setAction('add');
			$this->view->addToReplace($employee);
		}


		$this->listEmployee();
		$this->arrayToPrint();
	}

	 /*
	 * Delete action. Delete employee and if method  DB return true,
	 * send header location Employee/index/.
	 */
	public function deleteAction()
	{
		$params = $this->data->getParams();
		$params = $this->valid->clearDataArr($params);
		if (isset($params['id']))
		{
			$this->employee->setFlag(true);
		}
		if (true === $this->employee->setAction('delete'))
		{
			header('Location: ' . PATH . 'Employee/index/', true, 303);
		}
	}

 /*
 * Edit action. Edit selected employee. If all update, send header.
 */
	public function editAction()
	{
		$params = $this->data->getParams();
		$params = $this->valid->clearDataArr($params);
		if (isset($params['id']))
		{
			$this->employee->setFlag(true);
		}
		if (isset($_POST['submit']))
		{
			$action = $this->valid->clearDataArr($_POST);
			$this->employee->setDataArray($action);
			header('Location: ' . PATH . 'Employee/index/', true, 303);
		}
		$employee = $this->employee->setAction('edit');
		$this->view->addToReplace($employee);
		$this->listEmployee();
		$this->arrayToPrint();
	}

 /*
 * Create list employee
 */
	private function listEmployee()
	{
		$employees = $this->employee->getEmployee();
		$cnt = 1;
		foreach($employees as $key => $val)
		{
			$arr = array('CNT' => $cnt,
						 'EMPLOYEE_NAME' => $val['name_employee'],
						 'EMPLOYEE_ID' => $val['id_employee'],
						 'EMPLOYEE_MAIL' => $val['mail_employee']);
			$cnt ++;
			$this->view->addToReplace($arr);
			$this->arrRender['LISTEMPLOYEES'] .= $this->view->
			setTemplateFile('employeeslist')->renderFile();
		}
	}

 /*
 * Send view array to replace placeholder and print page
 */
	private function arrayToPrint()
	{
		$this->view->addToReplace($this->langArr);
		$this->view->addToReplace($this->arrRender);
		$this->arrRender['CONTENT'] = $this->view
			->setTemplateFile('employeeedit')->renderFile();
		$this->view->addToReplace($this->arrRender);
		$this->view->setTemplateFile('index')->templateRender();
	}
}
?>