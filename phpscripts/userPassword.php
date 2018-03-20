<?php
	if (isset($_POST["changePasswordButton"]))
	{
		if ($oldPassword && $newPassword && $retypeNewPassword)
		{
			$oldPassword=md5($oldPassword);
			$newPassword=md5($newPassword);
			$retypeNewPassword=md5($retypeNewPassword);
		
			if ($newPassword == $retypeNewPassword)
			{
				$rez = pg_query($link, "select id from $tableUser where id=$sessionUserID and password='$oldPassword'");
				if (pg_num_rows($rez) == 1)
				{
					if (pg_query($link, "update $tableUser set password='$newPassword' where id=$sessionUserID"))
					{
						$tpl->assign('main_message', 'Пароль успешно изменен!');
						$page='message';
					}
					else
					{
						$tpl->assign('main_message', DisplayError('При изменении пароля возникла ошибка!'));
					}
				}
				else
				{
					$tpl->assign('main_message', DisplayError('Неверный текущий пароль!'));
				}
			}
			else
			{
				$tpl->assign('main_message', DisplayError('Новый пароль и подтверждение не совпадают!'));
			}
		}
		else
		{
			$tpl->assign('main_message', DisplayError('Все поля обязательны для заполнения!'));
		}
	}
	$tpl->assign("main_header", "Изменение пароля");
?>