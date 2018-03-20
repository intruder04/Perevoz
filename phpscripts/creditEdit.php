<?php
	if (isset($_POST["editButton"]))
	{		
		if ($name)
		{
			
			if (isset($creditID) && $creditID)
			{
					
				if (pg_query($link, "update $tableCredit set name='$name', descr = '$descr' where id = $creditID"))
				{
					$tpl->assign("main_message", "Изменения сохранены <meta http-equiv=\"refresh\" content=\"1;url=http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?page=creditList\">");	
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
				
				if (pg_query($link, "insert into $tableCredit (name, descr) values ('$name', '$descr')"))
                {
                    $tpl->assign("main_message", "Запись успешно создана <meta http-equiv=\"refresh\" content=\"1;url=http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?page=creditList\">");	
                    $page = "message";
                }
                else
                {
				   $tpl->assign("main_message", DisplayError("Ошибка работы базы данных"));
                }
                                               
				
				$tpl->assign("main_header","Новая запись");
			}
					
		}
		if (isset($creditID)) $_POST["credit_id"] = $creditID;
		$tpl->assign("credit", $_POST);
	}
	else
	{
		if (isset($creditID) && $creditID)
		{
			$rez = pg_query($link, "select * from $tableCredit where id=$creditID");
			$row = pg_fetch_array($rez);
			$tpl->assign('credit', $row);
			$tpl->assign("main_header","Редактирование записи");
		}
		else
		{
			$tpl->assign("main_header","Новая запись");
		}
	}
	
?>