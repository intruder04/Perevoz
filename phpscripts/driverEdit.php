<?php
	if (isset($_POST["editButton"]))
	{		
		if ($fullname && $nick)
		{
			
			if (isset($driverID) && $driverID)
			{
					
				if (pg_query($link, "update $tableDriver set fullname='$fullname', nick = '$nick', status = '$status', phone = '$phone', car_id = $car_id, location_id = '$location_id' where id = $driverID"))
				{
					$tpl->assign("main_message", "Изменения сохранены <meta http-equiv=\"refresh\" content=\"1;url=http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?page=driverList\">");
					$page = "message";
				}
				else
				{
					$tpl->assign("main_message", DisplayError("Ошибка работы базы данных"));
				}
				$tpl->assign("main_header","Редактирование записи");
			}
			else
			{
				if (pg_query($link, "insert into $tableDriver (fullname, nick, status, phone, location_id" . ($car_id ? ", car_id" : "") . ") values ('$fullname', '$nick', '$status', '$phone', $location_id" . ($car_id ? ", $car_id" : "") . ")"))
                {
                    $tpl->assign("main_message", "Запись успешно создана <meta http-equiv=\"refresh\" content=\"1;url=http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?page=driverList\">");	
                    $page = "message";
                }
                else
                {
				   $tpl->assign("main_message", DisplayError("Ошибка работы базы данных"));
                }
				$tpl->assign("main_header","Новая запись");
			}
					
		}
		if (isset($driverID)) $_POST["id"] = $driverID;
		$tpl->assign("driver", $_POST);
	}
	else
	{
		if (isset($driverID) && $driverID)
		{
			$rez = pg_query($link, "select * from $tableDriver where id=$driverID");
			$row = pg_fetch_array($rez);
			$tpl->assign('driver', $row);
			$tpl->assign("main_header","Редактирование записи");
		}
		else
		{
			$tpl->assign("main_header","Новая запись");
		}
	}
	$rez = pg_query($link, "select * from $tableCar " . ($sessionUserLocId ? "where location_id = $sessionUserLocId" : ""));
	$cars = array();
	while ( $row = pg_fetch_array($rez))
	{
			$id = $row["id"];
			$model = $row["model"];
			$regnum = $row["regnum"];
			$cars[$id] = "$model $regnum";
	}
	$tpl->assign('cars', $cars);
	
	$rez = pg_query($link, "select * from $tableLocation " . ($sessionUserLocId ? "where id = $sessionUserLocId" : ""));
	$locations = array();
	while ( $row = pg_fetch_array($rez))
	{
			$id = $row["id"];
			$name = $row["name"];
			$locations[$id] = $name;
	}
	$tpl->assign('locations', $locations);
	$tpl->assign("driverStatuses", $configDriverStatuses);
?>