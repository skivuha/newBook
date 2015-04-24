<?php

 /*
 * Class: Calendar
 *
 */

class Calendar extends Model
{
	private $currentMonth;
	private $newMonth;
	private $currentYear;
	private $newYear;
	private $currentDay;
	private $countDayOfCurrentMonth;
	private $nextMonth;
	private $nextYear;
	private $subMonth;
	private $subYear;
	private $startDay;
	private $flagParams;
	private $saturday;
	private $sunday;
	private $headTable;
	private $disable;
	private $calendar;
	private $firstDayTimeStampChoiseMonth;
	private $lastDayTimeStampChoiseMonth;
	private $currentDayTimeStamp;
	private $timeFormat;
	private $room;
	private $userRole;

 /*
 * Get data from GET array and build new link next and previous month
 */
	private function getNewData()
	{
		$this->getCurrentData();
		if(true === $this->flagParams)
		{
			$getParam = $this->data->getParams();
			$this->newMonth = $this->validatorObj
				->numCheck($getParam['month']);
			$this->newYear = $this->validatorObj
				->numCheck($getParam['year']);
		}
		else
		{
			$this->newMonth = $this->currentMonth;
			$this->newYear = $this->currentYear;
		}
		$this->subYear = $this->newYear;
		$this->subMonth = $this->newMonth - 1;
		if(0 >= $this->subMonth)
		{
			$this->subYear = $this->subYear - 1;
			$this->subMonth = 12;
		}
		$this->nextMonth = $this->newMonth + 1;
		$this->nextYear = $this->newYear;
		if ($this->nextMonth > 12)
		{
			$this->nextYear = $this->nextYear + 1;
			$this->nextMonth = 1;
		}
	}

 /*
 * Get main timestamp
 */
 	private function getTimeStamp()
	{
		$this->countDayOfCurrentMonth = date('t',
			strtotime($this->newYear.'-'.$this->newMonth));
		$this->firstDayTimeStampChoiseMonth = mktime(0, 0, 0,
			$this->newMonth, 1, $this->newYear);
		$this->lastDayTimeStampChoiseMonth = mktime(23, 59, 59,
			$this->newMonth, $this->countDayOfCurrentMonth, $this->newYear);
		$this->currentDayTimeStamp = time();
	}

 /*
 * Get main array witch content day of week and number of day
 *
 * @return: array;
 */
	private function getCalendar()
	{
		$this->getNewData();
		$this->getTimeStamp();
		$day_count = 0;
		for($j=1; $j<=6; $j++)
		{
			for ($i = 0; $i < 7; $i++) {
				$dayOfWeek = date('w', mktime(0, 0, 0, $this->newMonth,
					$day_count,	$this->newYear));
				$this->saturday = 6;
				$this->sunday = 0;
				$this->disable = false;
				$this->headTable = array(0,1,2,3,4,5,6);
				if('monday' === $this->startDay)
				{
					$this->disable = true;
					$this->saturday = 5;
					$this->sunday = 6;
					$this->headTable = array(1,2,3,4,5,6,0);
					$dayOfWeek = $dayOfWeek - 1;
					if (- 1 == $dayOfWeek)
					{
						$dayOfWeek = 6;
					}
				}
				if ($i == $dayOfWeek
					&& $day_count <= $this->countDayOfCurrentMonth)
				{
					$week[$j][$i] = $day_count;
					if($day_count <= $this->countDayOfCurrentMonth)
					{
						$day_count ++;
					}
				}
				else
				{
					$week[$j][$i] = '';
				}
			}
		}
	return $week;
	}

 /*
 * Get main data calendar
 *
 * @return: array
 */
	public function printCalendar()
	{
		$var = $this->getCalendar();
		$data = $head = '';
		foreach($this->headTable as $val)
		{
			$head .= '<th>%LANG_HEAD_'.$val.'%</th>';
		}

		foreach($var as $key=>$val)
		{
			$data .= '<tr>';
			foreach($val as $key2=>$value)
			{
				if(!empty($value))
				{
						if ($this->saturday == $key2 || $this->sunday == $key2)
						{
							$data .= '<td style="color: red">'.$value.'
							<br>&nbsp;</td>';
						}
					else
					{
						$data .= '<td>' . $value . '<div>&nbsp;
						{{EVENT'.$value.'}}</div></td>';
					}
				}
				else
				{
					$data .= '<td class="active"> &nbsp;<br>&nbsp;</td>';
				}
			}
			$data.= '</tr>';
		}
		$this->getCurrentData();

		if(true === $this->disable)
		{
			$this->calendar['FIRSTDAY'] = 'sunday';
			$this->calendar['FIRSTDAYB'] = '%LANG_SUNDAY%';
		}
		else
		{
			$this->calendar['FIRSTDAY'] = 'monday';
			$this->calendar['FIRSTDAYB'] = '%LANG_MONDAY%';
		}

		if('24h' == $this->timeFormat)
		{
			$this->timeFormat = false;
			$this->calendar['TIMEFORMAT'] = '12h';
			$this->calendar['TIMEFORMATB'] = '%LANG_12H%';
		}
		else
		{
			$this->timeFormat = true;
			$this->calendar['TIMEFORMAT'] = '24h';
			$this->calendar['TIMEFORMATB'] = '%LANG_24H%';
		}
		$this->room = $this->sessionObj->getSession('room');
		$this->calendar['HEADCALENDAR'] = $head;
		$this->calendar['LOWER'] = 'year/'.$this->subYear.'/month/'
			.$this->subMonth;
		$this->calendar['HIGHER'] = 'year/'.$this->nextYear.'/month/'
			.$this->nextMonth;
		$this->calendar['CURRENT'] = '%LANG_'.$this->newMonth.'% - '
			.$this->newYear;
		$this->calendar['CONTENT'] = $data;
		$this->calendar['NAME_EMPL']= $this->sessionObj
			->getSession('name_employee');
		$this->calendar['ROOM'] = $this->room;
		$this->getAppointments();
		$this->getDataToBookIt();

	return $this->calendar;
	}

 /*
 * Get main array witch content day of week and number of day
 *
 * @return boolean
 */
	private function getAppointments()
	{
    $arr = $this->queryToDbObj
		->getCalendarAppointmentsSelectedMonth(
			$this->firstDayTimeStampChoiseMonth,
			$this->lastDayTimeStampChoiseMonth,
			$this->room);

      foreach($arr as $key=>$val)
      {
				if(true === $this->userRole
					|| $val['id_employee'] == $this->sessionObj->getSession
					('id_employee'))
				{
					$role = '';
				}
				else
				{
					$role = 'id = "roleC"';
				}
		$day = (int)date('d',$val['start']);
				$timeForm = 12;
		$startTime = 	date('H', $val['start']).':'.date('i', $val['start']);
				$startTimeFormat = 	date('h', $val['start']).':'.date('i',
						$val['start']);
		$endTime = date('H', $val['end']).':'.date('i', $val['end']);
				$endTimeFormat = date('h', $val['end']).':'.date('i',
						$val['end']);
				if(true === $this->timeFormat)
				{
					if($timeForm > $startTime)
					{
						$startTime = $startTime.'AM';
					}
					if($timeForm > $endTime)
					{
						$endTime = $endTime.'AM';
					}
					if($timeForm <= $startTime)
					{
						$startTime = $startTimeFormat.'PM';
					}
					if($timeForm <= $endTime)
					{
						$endTime = $endTimeFormat.'PM';
					}
				}
	 $this->calendar['EVENT'.$day].= '<br><a href="" name="'
		 .$val['id_appointment'].'"
	  class="event" '.$role.'>'.$startTime.' - '.$endTime.'</a>';
		}
		return true;
	}

 /*
 * Get list of employee
 *
 * @return: boolean
 */
	private function getDataToBookIt()
	{

		$employee = $this->queryToDbObj->getCalendarListEmployee();
		if(false === $this->userRole)
		{
			$this->calendar['USER'] = '<option value="'
				.$this->sessionObj->getSession
			('id_employee').'">'.$this->sessionObj->getSession('name_employee')
				.'</option>';
		}
		else
		{
			foreach($employee as $key => $value)
			{
				$this->calendar['USER'].= '<option value="'.
					$value['id_employee'].'">'.
					$value['name_employee'].'</option>';
			}
		}
		return true;
	}

	private function getCurrentData()
	{
		$this->currentDay = date('d');
		$this->currentMonth = date('n');
		$this->currentYear = date('Y');
	}

	public function getListRoom()
	{
		return $this->queryToDbObj->getCalendarRoomList();
	}

	public function setUserRole($var)
	{
		$this->userRole = $var;
		return true;
	}

	public function setFirstDay($var)
	{
		$this->startDay = $var;
		return true;
	}

	public function setFlagParams($var)
	{
		$this->flagParams = $var;
	}

	public function setTimeFormat($var)
	{
		$this->timeFormat = $var;
	}
}
?>
