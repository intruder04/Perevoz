<?php
	function DisplayError($error)
	{
		return "<font color=\"red\"><b>Error:</b> $error</font>";
	}
	
	function FormatTimeTicks($time)
	{
		$days = floor($time/86400);
		$time %= 86400;

		$hours = sprintf("%02s", floor($time/3600));
		$time %= 3600;
			
		$minutes = sprintf("%02s", floor($time/60));
		$seconds = sprintf("%02s", $time%60);
		
		$time_str = join(":", array($hours, $minutes, $seconds));
		
		if ($days == 0)
		{
            return $time_str;
		}
		elseif ($days == 1)
		{
            return "1 day $time_str";
		}
	    else
		{
			return "$days days $time_str";
        }               
	}
	
	function CountTripTime($time, $type = 0)
	{
		//время округляем до ближайших 15 минут или до часа если меньше часа
		
		if ($time && $time < 3600)
		{
			$time = 3600;
		}
		else
		{
			$time = ceil($time / 900)*900;	
		}
		
		if ($type == 1)
		{
			$time_str = sprintf("%0.02f", $time/3600);
			//$time_str = round($time/3600, 2);
		}
		else
		{
			$hours = sprintf("%02s", floor($time/3600));
		    $time %= 3600;
			$minutes = sprintf("%02s", floor($time/60));
			$time_str = join(":", array($hours, $minutes));
		}
        return $time_str;
	}	
	
	function RecordExists($link, $field, $value, $dbtable, $id = null)
	{
		$rez = pg_query($link, "select id from $dbtable where $field = '$value' and $field <> '' and $field is not null".($id ? " and id<> $id" : ''));
		return (odbc_num_rows($rez)>0 ? true : false);
	}
    
	
	function DeleteUsers($userIDs)
	{
		ini_set("max_execution_time", "600");
        global $link;
		global $tableUser;
		$userIDs = CheckIDs($userIDs);
        
		$rez = pg_query($link,"select id from $tableUser where id not in (".join(",", $userIDs).") and role = 'administrator'");
		if (pg_num_rows($rez) > 0)
		{
			if (pg_query($link, "delete from $tableUser where id in (".join(",", $userIDs).")")) return array(true, "Удаление прошло успешно");
			return array(false, DisplayError("Ошибка работы базы данных"));
		}
		else
		{
			return array(false, DisplayError("Вы пытаетесь удалить всех администраторов системы. Необходимо, чтобы в системе был хотя бы один администратор"));	
		}
	}
	
	function DeleteCars($carIDs)
	{
		ini_set("max_execution_time", "600");
        global $link;
		global $tableCar;
		$carIDs = CheckIDs($carIDs);
        
		if (pg_query($link, "delete from $tableCar where id in (".join(",", $carIDs).")")) return array(true, "Удаление прошло успешно");
		return array(false, DisplayError("Ошибка работы базы данных"));
	}
    
	function DeleteDrivers($driverIDs)
	{
		ini_set("max_execution_time", "600");
        global $link;
		global $tableDriver;
		$driverIDs = CheckIDs($driverIDs);
        
		if (pg_query($link, "delete from $tableDriver where id in (".join(",", $driverIDs).")")) return array(true, "Удаление прошло успешно");
		return array(false, DisplayError("Ошибка работы базы данных"));
	}
	
	function DeleteLocations($locationIDs)
	{
		ini_set("max_execution_time", "600");
        global $link;
		global $tableLocation;
		$locationIDs = CheckIDs($locationIDs);
        
		if (pg_query($link, "delete from $tableLocation where id in (".join(",", $locationIDs).")")) return array(true, "Удаление прошло успешно");
		return array(false, DisplayError("Ошибка работы базы данных"));
	}
	
	function DeleteCredits($creditIDs)
	{
		ini_set("max_execution_time", "600");
        global $link;
		global $tableCredit;
		$creditIDs = CheckIDs($creditIDs);
        
		if (pg_query($link, "delete from $tableCredit where id in (".join(",", $creditIDs).")")) return array(true, "Удаление прошло успешно");
		return array(false, DisplayError("Ошибка работы базы данных"));
	}
	
	function CheckIDs($IDs)
	{
		$IDs = array_map("CleanID", $IDs);
		return array_filter($IDs, "EmptyArrayElement");
	}
	
	function CleanID($ID)
	{
		return preg_replace("[^0-9]",'',trim($ID));
	
	}
	
	function EmptyArrayElement($element)
	{
		if (is_null($element) || $element == '')
		{
			return false; 
		}
		else
		{
			return true;
		}
	}
	
	function ifNullStr($var)
	{
		
		if (is_null($var) || ($var == '' && $var != 0)) return 'NULL';
		return $var;
	}
	
	function ShowErrorAndExit($tpl, $message)
	{
		global $configMenuIconImages;
		$tpl->assign("main_message", DisplayError($message));
		$tpl->assign("page", "message");
		$tpl->assign("image", "$configMenuIconImages/cancel.png");
		$tpl->display("index.tpl");
		exit;
	}
	
	function isEmptyVar ($var)
	{
		if (isset($var) && $var && $var != '') return false;
		return true;
	}
	
	function AddLeadingZeroes($var)
	{
		return sprintf("%02s", $var);
	}
	
	function AddToLog($type, $event, $user_id = 'null', $order_id = 'null')
	{
		global $link;
		global $tableLog;
		global $configLogEventTypes;
		if (!in_array($type, $configLogEventTypes)) $type = "info";
		$now = time();
		pg_query($link, "insert into $tableLog(type, datetime, event, user_id, order_id) values ('$type', $now, '$event', $user_id, $order_id)"); 
	}
?>