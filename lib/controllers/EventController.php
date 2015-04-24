<?php
class EventController extends Controller
{
	private $valid;
	private $event;
	private $data;

	public function __construct()
	{
		$this->data = Router::getInstance();
		$this->valid = new Validator();
		$this->event = new Event();
		$this->view = new View();
		$this->accessToCalendar();
		$this->arrayLang();
	}

	public function addAction()
	{
		$action = $this->valid->clearDataArr($_POST);
		$this->event->setData($action);
		$this->event->setRoom($this->room);
		if('false' == $action['recuringon'])
		{
			$status = $this->event->checkDateNoRecursion();
			$this->statusToAjax($status);
		}
		else
		{
			$status = $this->event->checkDateRecursion();
			$this->statusToAjax($status);
		}
	}

	public function editAction()
	{
		$param = $this->data->getParams();
		$param = $this->valid->numCheck($param['id']);
		$this->event->setData($param);
		$this->event->userRole($this->userRole);
		$arrayToDetails = $this->event->detailsEvent();
		$this->view->addToReplace($arrayToDetails);
		$this->view->addToReplace($this->langArr);
		$this->view->setTemplateFile('details')->templateRender();
	}

	public function updateAction()
	{
		$this->event->setRoom($this->room);
		$this->event->userRole($this->userRole);
		$param = $this->data->getParams();
		$name = $this->valid->clearData($param['do']);
		$id = $this->valid->numCheck($param['id']);
		if('delete' == $name)
			{
				if(isset($_POST['recurrences']))
				{
					$recur = $this->valid->numCheck($_POST['recurrences']);
					$this->event->setRecurent($recur);
				}
				$this->event->setData($id);
				$status = $this->event->deleteEvent();
				$this->statusToAjax($status);
			}
		if('update' == $name)
		{
			$param = $this->valid->clearDataArr($_POST);
			$param['update'] = $id;
			if(isset($_POST['recurrences']))
			{
				$recur = $this->valid->numCheck($_POST['recurrences']);
				$this->event->setRecurent($recur);
			}

			$this->event->setData($param);
			$status = $this->event->updateEvent();
			$this->statusToAjax($status);
		}
	}

	private function statusToAjax($status)
	{
		if(true === $status)
		{
			$array[0]=true;
			$this->view->ajax($array);
		}
		else
		{
			$this->view->ajax($status);
		}
	}

}
?>