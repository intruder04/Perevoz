<?php
	if (isset($_POST["editButton"]))
	{		
		if ($name)
		{
			
			if (isset($locationID) && $locationID)
			{
					
				if (pg_query($link, "update $tableLocation set name='$name' where id = $locationID"))
				{
					$tpl->assign("main_message", "Изменения сохранены <meta http-equiv=\"refresh\" content=\"1;url=http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?page=locationList\">");	
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
				
				if (pg_query($link, "insert into $tableLocation (name) values ('$name')"))
                {
                    $tpl->assign("main_message", "Запись успешно создана <meta http-equiv=\"refresh\" content=\"1;url=http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?page=locationList\">");	
                    $page = "message";
                }
                else
                {
				   $tpl->assign("main_message", DisplayError("Ошибка работы базы данных"));
                }
                                               
				
				$tpl->assign("main_header","Новая запись");
			}
					
		}
		if (isset($locationID)) $_POST["location_id"] = $locationID;
		$tpl->assign("location", $_POST);
	}
	else
	{
		if (isset($locationID) && $locationID)
		{
			$rez = pg_query($link, "select * from $tableLocation where id=$locationID");
			$row = pg_fetch_array($rez);
			$tpl->assign('location', $row);
			$tpl->assign("main_header","Редактирование записи");
		}
		else
		{
			$tpl->assign("main_header","Новая запись");
		}
	}
	
?>