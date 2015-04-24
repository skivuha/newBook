<?php
class Event extends Model
{
	private $dataArray;
	private $error;
	private $id;
	private $dArray;
	private $userRole;
	private $recurent;
	private $room;
	private $employee;
	private $selectedDay;
	private $selectedMonth;
	private $selectedYear;
	private $selectedStartHour;
	private $selectedStartMinute;
	private $selectedEndHour;
	private $selectedEndMinute;
	private $description;
	private $currentTime;

	private function dataFromPost()
	{
		$this->employee = $this->dataArray['employeename'];
		$this->selectedDay = (int)$this->dataArray['selectedday'];
		$this->selectedMonth = ((int)$this->dataArray['selectedmonth'])+1;
		$this->selectedYear = (int)$this->dataArray['selectedyear'];
		$this->selectedStartHour = (int)$this->dataArray['starthour'];
		$this->selectedStartMinute = (int)$this->dataArray['startmin'];
		$this->selectedEndHour = (int)$this->dataArray['endhour'];
		$this->selectedEndMinute = (int)$this->dataArray['endmin'];
		$this->description = $this->dataArray['desc'];
		$this->currentTime = time();
		$this->room = $this->sessionObj->getSession('room');
	}

	private function time($hour, $minute, $month, $day, $year)
	{
		return mktime($hour, $minute, 0, $month, $day, $year);
	}

	private function startDay($month, $day, $year)
	{
		return mktime(0, 0, 0, $month, $day, $year);
	}

	private function endDay($month, $day, $year)
	{
		return mktime(23, 59, 59, $month, $day, $year);
	}

	public function checkDateNoRecursion()
	{
		$this->dataFromPost();
		$startTime = $this->time($this->selectedStartHour,
			$this->selectedStartMinute,$this->selectedMonth,
			$this->selectedDay, $this->selectedYear);
		$endTime = $this->time($this->selectedEndHour,
			$this->selectedEndMinute,$this->selectedMonth,
			$this->selectedDay, $this->selectedYear);
		$startDay = $this->startDay($this->selectedMonth, $this->selectedDay,
			$this->selectedYear);
		$endDay = $this->endDay($this->selectedMonth, $this->selectedDay,
			$this->selectedYear);

		$dayOfWeek = date('w', $startTime);
		if(6 == $dayOfWeek || 0 == $dayOfWeek)
		{
			$this->error['ERROR'] = ERROR_WEEKEND;
			return $this->error;
		}
		$eventCurrentDay = $this->queryToDbObj->getEventSelectedDayAndRoom(
			$startDay, $endDay, $this->room);

		if(empty($eventCurrentDay))
		{
			if($endTime > $startTime
				&& $this->currentTime < $startTime
				&& $startTime != $endTime)
			{
				$arr = $this->queryToDbObj->setEvent($this->description,
					$this->employee, $startTime, $endTime, $this->room);
				return $arr;
			}
			else
			{
				$this->error['ERROR_DATA'] = ERROR_WRONG_DATA;
				return $this->error;
			}
		}
		else
		{
			$cnt = 0;
			if ($endTime > $startTime
				&& $this->currentTime < $startTime
				&& $startTime != $endTime)
			{
				foreach ($eventCurrentDay as $key => $value)
				{
					if($value['end'] <= $startTime
						|| $value['start'] >= $endTime)
					{
						$cnt++;
					}
					else
					{
						$this->error['ERROR_DATA'] = ERROR_BUSY;
					}
				}
			}
			else
			{
				$this->error['ERROR_DATA'] = ERROR_WRONG_DATA;
			}
			if(count($eventCurrentDay) == $cnt)
			{
				$this->queryToDbObj->setEvent($this->description,
					$this->employee, $startTime, $endTime, $this->room);
				return true;
			}
			else
			{
				return $this->error;
			}
		}
	}

	public function checkDateRecursion()
	{
		$duration = (int)$this->dataArray['duration'];
		$this->dataFromPost();

		if('weekly' == $this->dataArray['recuring']
			|| 'bi-weekly' == $this->dataArray['recuring']
			|| 'monthly' == $this->dataArray['recuring'])
		{
			if('weekly' == $this->dataArray['recuring'])
			{
				$interval = 7;
				$period = 0;
			}
			else
			{
				$interval = 14;
				$period = 0;
			}
			if('monthly' == $this->dataArray['recuring'])
			{
				$interval = 0;
				$period = 1;
			}

			if(1>$duration || 4<$duration || 28<$duration*$interval)
			{
				$duration = 0;
			}
			for($i = 0; $i <= $duration; $i++)
			{
				$startTime[$i] = $this->time($this->selectedStartHour,
					$this->selectedStartMinute,$this->selectedMonth
					+ $period*$i, $this->selectedDay + $interval*$i,
					$this->selectedYear);
				$endTime[$i] = $this->time($this->selectedEndHour,
					$this->selectedEndMinute,$this->selectedMonth + $period*$i,
					$this->selectedDay + $interval*$i, $this->selectedYear);
				$startDay = $this->startDay($this->selectedMonth,
					$this->selectedDay,	$this->selectedYear);
				$endDay = $this->endDay($this->selectedMonth,
					$this->selectedDay,	$this->selectedYear);

				if(6 == date('w', $startTime[$i]))
				{
					$startTime[$i] = $this->time($this->selectedStartHour,
						$this->selectedStartMinute,$this->selectedMonth
						+ $period*$i, $this->selectedDay + 2,
						$this->selectedYear);
					$endTime[$i] = $this->time($this->selectedEndHour,
						$this->selectedEndMinute,$this->selectedMonth
						+ $period*$i,
						$this->selectedDay + 2, $this->selectedYear);
					$startDay = $this->startDay($this->selectedMonth,
						$this->selectedDay + 2,	$this->selectedYear);
					$endDay = $this->endDay($this->selectedMonth,
						$this->selectedDay + 2,	$this->selectedYear);
				}elseif(0 == date('w', $startTime[$i]))
				{
					$startTime[$i] = $this->time($this->selectedStartHour,
						$this->selectedStartMinute,$this->selectedMonth
						+ $period*$i, $this->selectedDay + 1,
						$this->selectedYear);
					$endTime[$i] = $this->time($this->selectedEndHour,
						$this->selectedEndMinute,$this->selectedMonth
						+ $period*$i,
						$this->selectedDay + 1, $this->selectedYear);
					$startDay = $this->startDay($this->selectedMonth,
						$this->selectedDay + 1,	$this->selectedYear);
					$endDay = $this->endDay($this->selectedMonth,
						$this->selectedDay + 1,	$this->selectedYear);
				}
				$eventCurrentDay = $this->queryToDbObj
					->getEventSelectedDayAndRoom($startDay, $endDay,
						$this->room);
				$cnt = 0;

				if(!empty($eventCurrentDay))
				{
					if ($endTime[$i] > $startTime[$i]
						&& $this->currentTime < $startTime[$i]
						&& $startTime[$i] != $endTime[$i])
					{
						foreach ($eventCurrentDay as $key => $value)
						{
							if ($value['end'] <= $startTime[$i]
								|| $value['start'] >= $endTime[$i])
							{
								$cnt ++;
							}
						}
					}
					else
					{
						$this->error['ERROR_DATA'] = ERROR_WRONG_DATA;
					}
				}
				else
				{
					if ($endTime[$i] < $startTime[$i]
						|| $this->currentTime > $startTime[$i]
						|| $startTime[$i] == $endTime[$i])
					{
						$cnt++;
					}
				}
				if(count($eventCurrentDay) != $cnt)
				{
					$i++;
					$this->error['ERROR_DATA'] ='Wrong time on '.$i.'
					recursion!';
				}
			}
			$this->id = 0;
			if(empty($this->error))
			{
				for($i = 0; $i <= $duration; $i++)
				{
					$this->queryToDbObj->setEventWithRecur
				($this->description,
						$this->employee, $startTime[$i], $endTime[$i],
						$this->room, $this->id);
					if(0 == $i)
					{
						$this->id = $this->queryToDbObj->getLastId();
					}
				}
				$this->queryToDbObj->setParentRecur($this->id);

				return true;
			}
		}
		return $this->error;
	}

	public function detailsEvent()
	{
		$id_appointment = $this->dataArray;
		$id_employee = $this->sessionObj->getSession('id_employee');
		$currentEvent = $this->queryToDbObj->getEventById($id_appointment);

		$name_employee = $this->queryToDbObj
			->getEmployeeById($currentEvent[0]['id_employee']);

		$startTime = date('H', $currentEvent[0]['start']).
			':'.date('i', $currentEvent[0]['start']);

		$endTime = date('H', $currentEvent[0]['end']).
			':'.date('i', $currentEvent[0]['end']);

		$currentTime = time();

		if(0 == $currentEvent[0]['recursion']){
			$this->dArray['RECURRENCE'] = 'style="display: none;"';
		}
		else
		{
			$this->dArray['RECURRENCE'] = '';
			$this->dArray['RECURID'] = $currentEvent[0]['recursion'];
		}

		$this->dArray['EMPLOYEEID'] = $currentEvent[0]['id_employee'];
		$this->dArray['START'] = $currentEvent[0]['start'];
		$this->dArray['TITLE'] = $startTime.' - '. $endTime;
		$this->dArray['STARTTIME'] = $startTime;
		$this->dArray['ENDTIME'] = $endTime;
		if(false == $this->userRole)
		{
			$listUser = '<option value="'.$name_employee[0]['id_employee'].'">
			'.$name_employee[0]['name_employee']
				.'</option>';
		}
		else
		{
			$employee = $this->queryToDbObj->getEmployeeListExeptRoot();
			foreach($employee as $key => $value)
			{
				$thisUser ='';
				if($name_employee[0]['name_employee']
					== $value['name_employee'])
				{
					$thisUser = 'selected';
				}
				$listUser .= '<option value="'.$value['id_employee']
					.'" '.$thisUser
					.'>'.
					$value['name_employee'].'</option>';
			}
		}
		$this->dArray['WHO'] = $listUser;
		$this->dArray['NOTES'] = $currentEvent[0]['description'];
		$this->dArray['SUBMITTED'] = $currentEvent[0]['submitted'];
		$this->dArray['ID'] = $id_appointment;
		if($currentTime > $currentEvent[0]['start']
			||(false == $this->userRole
				&& $currentEvent[0]['id_employee'] != $id_employee))
		{
			$this->dArray['ACTIVE'] = 'disabled';
		}
		else
		{
			$this->dArray['ACTIVE'] = '';
		}

		return $this->dArray;
	}

	public function deleteEvent()
	{
		$currentTime = time();
		$id_appointment = $this->dataArray;
		$updateData = $_POST['empl'];
		if(!isset($this->recurent))
		{
			if(true === $this->userRole
				|| $updateData == $this->sessionObj->getSession('id_employee'))
			{
				$rez = $this->queryToDbObj
					->deleteEventNoRecur($id_appointment, $currentTime);
				return $rez;
			}
			else
			{
				$this->error['ERROR_DATA'] = 'Access denied!';
			}
		}
		else
		{
			if(true === $this->userRole
				|| $updateData == $this->sessionObj->getSession('id_employee'))
			{
				$this->queryToDbObj
					->deleteEventWithRecur($this->recurent, $currentTime);

				return true;
			}
			else
			{
				$this->error['ERROR_DATA'] = ERROR_ACCESS;
			}
		}
		return $this->error;
	}

	public function updateEvent()
	{
		$currentTime = time();
		$updateData = $this->dataArray;
		if (!isset($this->recurent))
		{
			$currentDay = $updateData['start'];
			$day = date('j', $currentDay);
			$mon = date('m', $currentDay);
			$year = date('Y', $currentDay);

			$arrayStartTime = explode(':', $updateData['starttime']);
			$arrayEndTime = explode(':', $updateData['endtime']);

			$startTimeUpdate = mktime($arrayStartTime[0], $arrayStartTime[1],
				0, $mon, $day, $year);
			$endTimeUpdate = mktime($arrayEndTime[0], $arrayEndTime[1],
				0, $mon, $day, $year);
			$startDay = $this->startDay($mon, $day, $year);
			$endDay = $this->endDay($mon, $day, $year);

			$check = $this->queryToDbObj->getEventFromDay($startDay,
				$endDay, $updateData['update'], $this->room);

			if (isset($check))
			{
				$cnt = 0;
				foreach ($check as $key => $value)
				{
					if ($value['end'] <= $startTimeUpdate
						|| $value['start'] >= $endTimeUpdate)
					{
						$cnt ++;
					}
				}
			}

			if (count($check) != $cnt)
			{
				$this->error['ERROR_DATA'] = ERROR_BUSY;
			}
			else
			{
				if ($currentTime < $startTimeUpdate
					&& $currentTime < $currentDay
					&& $startTimeUpdate < $endTimeUpdate)
				{
					if(true === $this->userRole
						|| $updateData['employee'] ==
						$this->sessionObj->getSession('id_employee'))
					{
						$this->queryToDbObj->setNewDataInEvent
						($updateData['description'],
							$updateData['employee'], $startTimeUpdate,
							$endTimeUpdate, $updateData['update']);

						return true;
					}
					else
					{
						$this->error['ERROR_DATA'] = ERROR_ACCESS;
					}
				}
				else
				{
					$this->error['ERROR_DATA'] = ERROR_WRONG_DATA;
				}
			}
		}
		else
		{
			$allEvent = $this->queryToDbObj->getAllEvents($this->recurent);
			//$new_employee = $updateData['employee'];
			$newStartTime = explode(':', $updateData['starttime']);
			$newEndTime = explode(':', $updateData['endtime']);
			//$id_employee = $allEvent[0]['id_employee'];
			$startChangeRecur = $updateData['start'];

			foreach ($allEvent as $value)
			{
				$id_appointment = $value['id_appointment'];
				$currentDay = $value['start'];
				$day = date('j', $currentDay);
				$mon = date('m', $currentDay);
				$year = date('Y', $currentDay);

				$startDay = $this->startDay($mon, $day, $year);
				$endDay = $this->endDay($mon, $day, $year);

				$startTimeUpdate = $this->time($newStartTime[0],
					$newStartTime[1], $mon, $day, $year);
				$endTimeUpdate = $this->time($newEndTime[0],
					$newEndTime[1], $mon, $day, $year);

				$check = $this->queryToDbObj->getEventFromDay($startDay,
					$endDay, $id_appointment, $this->room);

				if (isset($check))
				{
					$cnt = 0;
					foreach ($check as $key => $val)
					{
						if ($val['end'] <= $startTimeUpdate
							|| $val['start'] >= $endTimeUpdate)
						{
							$cnt ++;
						}
					}
				}

				if (count($check) != $cnt)
				{
					$this->error['ERROR_DATA'] .= 'Warning, '.$day.' '.
						date('F', $currentDay).' alredy	busy!<br>';
				}
				else
				{
						    if ($startChangeRecur <= $startTimeUpdate
							    && $currentTime < $currentDay
		        				    && $startTimeUpdate < $endTimeUpdate)
					{
						if(true === $this->userRole
						    || $updateData['employee'] ==
						$this->sessionObj->getSession('id_employee'))
					{
					    $this->queryToDbObj->setNewDataInEvent
					($updateData['description'],
					$updateData['employee'], $startTimeUpdate,
					$endTimeUpdate, $id_appointment);
					}
					else
					{
			    		    $this->error['ERROR_DATA'] = ERROR_ACCESS;
					}
					}
				        else
					{
			$this->error['ERROR_DATA'] .= 'Warning, '.$day.' '.
				date('F', $currentDay).' alredy	busy!<br>';
			    }
			}
		    }
		}
		    if(empty($this->error))
		
		{	
		    return true;
		}
		else
		{
		    return $this->error;
		}
	}

	public function setRecurent($var)
	{
		$this->recurent = $var;
	}

	public function userRole($var)
	{
		$this->userRole = $var;
	}

	public function setData($var)
	{
		$this->dataArray = $var;
	}

	public function setRoom($var)
	{
		$this->room = $var;
	}
}
?>