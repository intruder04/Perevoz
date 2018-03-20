<?php
	if (isset($_POST["editButton"]))
	{		
		if ($regnum && $model)
		{
			
			if (isset($carID) && $carID)
			{
					
				if (pg_query($link, "update $tableCar set regnum='$regnum', model = '$model', color = '$color', location_id = '$location_id' where id = $carID"))
				{
					$tpl->assign("main_message", "Изменения сохранены <meta http-equiv=\"refresh\" content=\"1;url=http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?page=carList\">");	
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
				
				if (pg_query($link, "insert into $tableCar (regnum, model, color, location_id) values ('$regnum', '$model', '$color', $location_id)"))
                {
                    $tpl->assign("main_message", "Запись успешно создана <meta http-equiv=\"refresh\" content=\"1;url=http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?page=carList\">");	
                    $page = "message";
                }
                else
                {
				   $tpl->assign("main_message", DisplayError("Ошибка работы базы данных"));
                }
                                               
				
				$tpl->assign("main_header","Новая запись");
			}
					
		}
		if (isset($carID)) $_POST["id"] = $carID;
		$tpl->assign("car", $_POST);
	}
	else
	{
		if (isset($carID) && $carID)
		{
			$rez = pg_query($link, "select * from $tableCar where id=$carID");
			$row = pg_fetch_array($rez);
			$tpl->assign('car', $row);
			$tpl->assign("main_header","Редактирование записи");
		}
		else
		{
			$tpl->assign("main_header","Новая запись");
		}
	}
	
	$rez = pg_query($link, "select * from $tableLocation " . ($sessionUserLocId ? "where id = $sessionUserLocId" : ""));
	$locations = array();
	while ( $row = pg_fetch_array($rez))
	{
			$id = $row["id"];
			$name = $row["name"];
			$locations[$id] = $name;
	}
	$tpl->assign('locations', $locations);
	
?>