<?php
	if (isset($_POST["editButton"]))
	{
		
		if ($login && $email)
		{
			$orderint = (isset($_POST["orderint"]) && $_POST["orderint"] == 1 ? 1 : 0);
			$sb_seeall = (isset($_POST["sb_seeall"]) && $_POST["sb_seeall"] == 1 ? 1 : 0);
			$sb_user = isset($_POST["sb_user"]) ? $sb_user : "";
			
			if (isset($userID) && $userID)
			{
					$rez = pg_query($link, "select login from $tableUser where login = '$login' and id <> $userID");
					if (pg_num_rows($rez) == 0)
					{
						
						$sb_user = isset($sb_user) ? $sb_user : "";
						
						if (pg_query($link, "update $tableUser set login='$login', phone = '$phone',".($password ? " password='".md5($password)."'," : '')." email='$email', sb_user='$sb_user', sb_seeall = $sb_seeall, location_id = '$location_id', orderint = $orderint".(isset($role) && in_array($role, array_keys($userRoles)) ? ", role = '$role'" : '')." where id = $userID"))
						{
							$tpl->assign("main_message", "Изменения сохранены <meta http-equiv=\"refresh\" content=\"1;url=http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?page=userList\">");	
							$page = "message";
						}
						else
						{
							$tpl->assign("main_message", DisplayError("Ошибка работы базы данных"));
						}
					}
					else
					{
						$tpl->assign("main_message", DisplayError("Учетная запись \"$login\" уже используется"));
					}
				
				$tpl->assign("main_header","Редактирование учетной записи");
			}
			else
			{
				$rez = pg_query($link, "select login from $tableUser where login = '$login'");
				if (pg_num_rows($rez) == 0)
				{
					
					if ($password && pg_query($link, "insert into $tableUser (login, password, email, role, phone, orderint, sb_user, sb_seeall, location_id) values ('$login', '".md5($password)."', '$email', '$role', '$phone', $orderint, '$sb_user', $sb_seeall, " . ($location_id ? $location_id : "NULL") . ")"))
                    {
                        $tpl->assign("main_message", "Учетная запись успешно создана <meta http-equiv=\"refresh\" content=\"1;url=http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?page=userList\">");	
                        $page = "message";
                    }
                    else
                    {
					    $tpl->assign("main_message", DisplayError("Ошибка работы базы данных"));
                    }
					
                                             
				}
				else
				{
					$tpl->assign("main_message", DisplayError("Учетная запись $login уже существует!"));
				}
				$tpl->assign("main_header","Новая учетная запись");
			}
				
			
			
		}
		if (isset($userID)) $_POST["id"] = $userID;
		$tpl->assign("user", $_POST);
	}
	else
	{
		if (isset($userID) && $userID)
		{
			$rez = pg_query($link, "select $tableUser.* from $tableUser where $tableUser.id=$userID");
			
			$row = pg_fetch_array($rez);
			$tpl->assign('user', $row);
			$tpl->assign("main_header","Редактирование учетной записи");
		}
		else
		{
			$tpl->assign("main_header","Новая учетная запись");
		}
	}
	
	$tpl->assign("userRoles", $userRoles);
	
	
	$rez = pg_query($link, "select * from $tableLocation");
	$locations = array();
	while ( $row = pg_fetch_array($rez))
	{
			$id = $row["id"];
			$name = $row["name"];
			$locations[$id] = $name;
	}
	$tpl->assign('locations', $locations);
	
?>