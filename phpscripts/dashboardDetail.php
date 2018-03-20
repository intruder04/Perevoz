<?php
			if (!isset($view)) $view="edit";
			//if (!isset($orderID) || isEmptyVar($orderID)) ShowErrorAndExit($tpl, "Заявка не найдена");			
			if (!isset($orderID) || isEmptyVar($orderID)) 
			{
				$orderID = ''; 						
				if ($sessionOrderInt != 1) ShowErrorAndExit($tpl, "Заявка не найдена");
			}
			if ($view == 'log')
			{
				$rez = pg_query($link, "select sd_number from $tableOrder where id=$orderID");
				$row = pg_fetch_array($rez);
				$orderSBID = ($row["sd_number"] ? $row["sd_number"] : "ВН$orderID");
				$tpl->assign('logClass', 'selected');
				
				$query = "select $tableLog.*, $tableUser.login as user  from $tableLog left join $tableUser on $tableLog.user_id = $tableUser.id where order_id = $orderID";
		
				$pager = new Paging($link, "datetime desc", 20);
				
				$pager->set_result_text_prefix("Записи");  
				$r = $pager->get_page( $query ); ;
				$tpl->assign('pager_info', $pager->get_result_text());
				$tpl->assign('pager_prev_link', $pager->get_prev_page_link());
				$tpl->assign('pager_next_link', $pager->get_next_page_link());
				$tpl->assign('pager_links', $pager->get_page_links());
				$log = array(); 
				while($row = pg_fetch_array($r))
				{
					$row["datetime"] = date("d.m.Y", $row["datetime"])."<br>".date("H:i", $row["datetime"]);
					array_push($log,  $row);
				}
				$tpl->assign("main_header", "Заявка $orderSBID");
				$tpl->assign("log", $log); 
			}
			else
			{
				if ($sessionUserRole == "dispatcher" && $orderID)
				{
					$rez = pg_query($link, "select id from $tableOrder where id = $orderID and status = 1 and user_id is null");
					if (pg_num_rows($rez) > 0)
					{
						pg_query($link, "update $tableOrder set status='2', user_id=$sessionUserID where id = $orderID and status = 1 and user_id is null");
						AddToLog("info", "Заявка взята в работу", $sessionUserID, $orderID);
					}
				}
				
				#SMS
				
				if (isset($_POST["clientSMS"]) || isset($_POST["driverSMS"]))
				{
				if ($driver_id)
				{	
					### SMS
					$rez = pg_query($link,"select $tableDriver.*, $tableCar.* from $tableDriver inner join $tableCar on $tableDriver.car_id = $tableCar.id where $tableDriver.id = $driver_id");
					$row = pg_fetch_array($rez);
					// print_r($row);
					$fullname = $row["fullname"];
					$regnum = $row["regnum"];
					$model = $row["model"];
					$driver_phone = preg_replace("/\D/", "", $row["phone"]);
					$customer_phone = preg_replace("/\D/", "", $customer_phone);
					$check_drv = pg_query($link,"select sms_driver_id from $tableOrder where id = '$orderID'");
					$chrow = pg_fetch_array($check_drv);
					$last_driver = $chrow["sms_driver_id"];
					$order_data = pg_query($link,"select sd_number, address_from, address_to from $tableOrder where id = '$orderID'");
					$odrow = pg_fetch_array($order_data);
					#dont send duplicates
					// if ($last_driver!=$driver_id)
					// {
					$client_smstext = "Авто ". $model . " " . $regnum . ". " . $fullname . " " . $driver_phone;
					
					$driver_smstext = $odrow["sd_number"] .";" . ($start_date_Hour < 10 ? "0".$start_date_Hour : $start_date_Hour) . ":" . ($start_date_Minute < 10 ? "0".$start_date_Minute : $start_date_Minute) . " " . $start_date_Day . "/" . $start_date_Month . "/" . $start_date_Year . ";Подача:" . $odrow["address_from"] . ";Назначение:" .$odrow["address_to"] . ";" . $customer . ";" . $customer_phone;
					if (isset($_POST["clientSMS"])) {
					SendSMS($customer_phone,$client_smstext);
					$tpl->assign("sms_message", DisplayMsg("Отправлено клиенту на номер ".$customer_phone,"green"));
					// AddToLog("info", "Отправка СМС клиенту - ".$client_smstext, $sessionUserID, $orderID);
					}
					if (isset($_POST["driverSMS"])) {
					SendSMS($driver_phone,$driver_smstext);
					$tpl->assign("sms_message", DisplayMsg("Отправлено водителю на номер ".$driver_phone,"green"));
					// AddToLog("info", "Отправка СМС водителю - ".$client_smstext, $sessionUserID, $orderID);
					}

					$insert_drv = pg_query($link,"update $tableOrder set sms_driver_id = '$driver_id' where id = '$orderID'");
					// }
					// else {
						// $tpl->assign("sms_message", DisplayMsg("СМС на номер клиента и водителя уже были отправлены ранее","red"));
					// }
					### SMS
				}
				else {
					$tpl->assign("sms_message", DisplayMsg("НЕ НАЗНАЧЕН ВОДИТЕЛЬ!","red"));
				}
				
				}
				if (isset($_POST["editButton"]) || isset($_POST["editConfirmation"])) //chrome не видит button в submit
				{		
					$error_msg = '';
					$departure_date = '';
					$end_date = '';
					
					if ($start_date_Hour || $start_date_Minute || $start_date_Month || $start_date_Day || $start_date_Year)
					{
						if (checkdate($start_date_Month, $start_date_Day, $start_date_Year))
						{
							$departure_date = mktime($start_date_Hour, $start_date_Minute, 0, $start_date_Month,  $start_date_Day, $start_date_Year);
						}
						else
						{
							$error_msg .= "Некорректная дата выезда. "; 
						}
					}
					if ($end_date_Hour || $end_date_Minute || $end_date_Month || $end_date_Day || $end_date_Year)
					{
						if (checkdate($end_date_Month, $end_date_Day, $end_date_Year))
						{
							$end_date = mktime($end_date_Hour, $end_date_Minute, 0, $end_date_Month, $end_date_Day, $end_date_Year);
						}
						else
						{
							$error_msg .= "Некорректная дата дата окончания работ. ";  
						}
					}
					if (!$error_msg)
					{
						//проверка введенных данных
						$oldStatus = '';
						if ($orderID)
						{
							$rez = pg_query($link, "select status from $tableOrder where id = $orderID");
							$row = pg_fetch_array($rez);
							$oldStatus = $row["status"];
						}			
						
						if ($orderID && ($status < $oldStatus) && $sessionUserRole != 'administrator')
						{
							$tpl->assign("main_message", DisplayError("Невозможно понизить статус с \"".$configOrderStatuses[$oldStatus]."\" на \"".$configOrderStatuses[$status]."\"!"));
						}
						elseif (in_array($status, array(3,4,5,6,7,8,9)) && isEmptyVar($solution))
						{
							$tpl->assign("main_message", DisplayError("Требуется заполненное поле \"Решение\"!"));
						}
						elseif (in_array($status, array(7)) && isEmptyVar($driver_id))
						{
							$tpl->assign("main_message", DisplayError("Статус \"".$configOrderStatuses[$status]."\" требует назначить водителя!"));
						}
						elseif (in_array($status, array(7)) && isEmptyVar($car_id))
						{
							$tpl->assign("main_message", DisplayError("Статус \"".$configOrderStatuses[$status]."\" требует назначенный автомобиль!"));
						}
						elseif (in_array($status, array(7)) && (isEmptyVar($departure_date) || isEmptyVar($end_date)))
						{
							$tpl->assign("main_message", DisplayError("Статус \"".$configOrderStatuses[$status]."\" требует указания времени начала и окончания поездки!"));
						}
						elseif ($departure_date && $end_date && $end_date < $departure_date)
						{
							$tpl->assign("main_message", DisplayError("Время начала работ больше времени окончания!"));
						}
						// elseif (in_array($status, array(3,4,5)) && $credit_id == '')
						// {
							// $tpl->assign("main_message", DisplayError("Номер кредита не может быть пустым!"));
						// }
						else
						{
							$time = time();
							$credit_id = '';
							
							//print "update $tableOrder set customer = '$customer' $userSQL, customer_phone = '$customer_phone', passenger = '$passenger', vip = ".(isset($_POST["vip"]) && $_POST["vip"] > 0 ? 1 : "null").", car_id = ".($car_id ?$car_id : 'null').", driver_id = ".($driver_id ? "$driver_id" : 'null').", location_id = ".($location_id ? $location_id : 'null').", address_from = '$address_from', address_to = '$address_to', ret_address_from = '$ret_address_from', ret_address_to = '$ret_address_to', status = '$status', solution = '$solution', update_date = $time, credit_id = '$credit_id', departure_date = ".($departure_date ? $departure_date : 'null').", end_date = ".($end_date? $end_date : 'null')." where id = '$orderID'";
							if ($orderID) //update
							{
							//testing
							
								$userSQL = ($sessionUserRole == "administrator" ? ($user_id ? ", user_id = $user_id" : ", user_id = null") : '');
								
								
								// $test = "update $tableOrder set customer = '$customer' $userSQL, customer_phone = '$customer_phone', passenger = '$passenger', vip = ".(isset($_POST["vip"]) && $_POST["vip"] > 0 ? 1 : "null").", car_id = ".($car_id ?$car_id : 'null').", driver_id = ".($driver_id ? "$driver_id" : 'null').", location_id = ".($location_id ? $location_id : 'null').", address_from = '$address_from', address_to = '$address_to', ret_address_from = '$ret_address_from', ret_address_to = '$ret_address_to', status = '$status', price = '$price', km_route = '$km_route', lagtime50km = '$lagtime50km', timeradius50km = '$timeradius50km', timeradius50km_minute = '$timeradius50km_minute', lagtime50km_minute = '$lagtime50km_minute', solution = '$solution', update_date = $time, credit_id = ".($credit_id ? "$credit_id" : 'null').", departure_date = ".($departure_date ? $departure_date : 'null').", end_date = ".($end_date? $end_date : 'null')." where id = '$orderID'";
							// print_r($test);
								
								
								if (pg_query($link, "update $tableOrder set customer = '$customer' $userSQL, customer_phone = '$customer_phone', passenger = '$passenger', vip = ".(isset($_POST["vip"]) && $_POST["vip"] > 0 ? 1 : "null").", car_id = ".($car_id ?$car_id : 'null').", driver_id = ".($driver_id ? "$driver_id" : 'null').", location_id = ".($location_id ? $location_id : 'null').", address_from = '$address_from', address_to = '$address_to', ret_address_from = '$ret_address_from', ret_address_to = '$ret_address_to', status = '$status', price = '$price', km_route = '$km_route', lagtime50km = '$lagtime50km', timeradius50km = '$timeradius50km', timeradius50km_minute = '$timeradius50km_minute', lagtime50km_minute = '$lagtime50km_minute', tariff = '$tariff', solution = '$solution', update_date = $time, credit_id = ".($credit_id ? "$credit_id" : 'null').", departure_date = ".($departure_date ? $departure_date : 'null').", end_date = ".($end_date? $end_date : 'null')." where id = '$orderID'"))
								{
									$driver = '';
									
									$user = '';
									if (isset($user_id) && $user_id)
									{
										$rez = pg_query($link, "select login from $tableUser where id = $user_id");
										$row = pg_fetch_array($rez);
										$user = $row["login"];
									}
									$logMessage = "Обновление данных заявки: заказчик => \"$customer\", телефон заказчика => \"$customer_phone\", пассажир => \"$passenger\", пункт отправлени => \"$address_from\", пункт назначения => \"$address_to\", пункт отправления (обратно) => \"$ret_address_from\", пункт назначения (обратно) => \"$ret_address_to\", статус => \"$configOrderStatuses[$status]\", водитель => \"$driver\",решение => \"$solution\", выезд => \"$departure_date\", дата завершения => \"$end_date\", водитель => \"$driver\"".(isset($user_id) ? ", диспетчер => \"$user\"" : '');
									AddToLog("info", $logMessage, $sessionUserID, $orderID);
									$tpl->assign("main_message", "Данные успешно обновлены");
								}
								else
								{
									$tpl->assign("main_message", DisplayError("Во время выполнения запроса возникла ошибка"));
								}
								
							}
							else //insert
							{
								
								if ($rez = pg_query($link, "insert into $tableOrder (start_date, customer, customer_phone, passenger, vip, car_id, driver_id, location_id, address_from, address_to, ret_address_from, ret_address_to, status, solution, update_date, credit_id, departure_date, end_date, user_id, sb_user, price, km_route, lagtime50km, timeradius50km, timeradius50km_minute, lagtime50km_minute, tariff) values ($time, '$customer', '$customer_phone', '$passenger', ".(isset($_POST["vip"]) && $_POST["vip"] > 0 ? 1 : "null").", ".($car_id ?$car_id : 'null').", ".($driver_id ? "$driver_id" : 'null').", ".($location_id ? $location_id : 'null').", '$address_from', '$address_to', '$ret_address_from', '$ret_address_to', '$status', '$solution', $time, ".($credit_id ? "$credit_id" : 'null').", ".($departure_date ? $departure_date : 'null').", ".($end_date? $end_date : 'null').", ".($sessionUserRole == 'administrator' ? ($user_id ? $user_id : 'null') : $sessionUserID). ", " . ($sessionSbUser ? "'$sessionSbUser'" : "''") . ", '$price', '$km_route', '$lagtime50km', '$timeradius50km', '$timeradius50km_minute', '$lagtime50km_minute', '$tariff') returning id" ))
								{
									$row = pg_fetch_array($rez);
									$orderID = $row["id"];
									$driver = '';
									if ($driver_id)
									{
										$rez = pg_query($link, "select nick, fullname from $tableDriver where id = $driver_id");
										$row = pg_fetch_array($rez);
										$driver = $row["nick"]."(".$row["fullname"].")";
									}
									$user = '';
									if (isset($user_id) && $user_id)
									{
										$rez = pg_query($link, "select login from $tableUser where id = $user_id");
										$row = pg_fetch_array($rez);
										$user = $row["login"];
									}
									AddToLog("info", "Добавлена заявка ВН$orderID", $sessionUserID, $orderID);
									$logMessage = "Обновление данных заявки: заказчик => \"$customer\", телефон заказчика => \"$customer_phone\", пассажир => \"$passenger\", пункт отправлени => \"$address_from\", пункт назначения => \"$address_to\", пункт отправления (обратно) => \"$ret_address_from\", пункт назначения (обратно) => \"$ret_address_to\", статус => \"$configOrderStatuses[$status]\", водитель => \"$driver\",решение => \"$solution\", выезд => \"$departure_date\", дата завершения => \"$end_date\", водитель => \"$driver\"".(isset($user_id) ? ", диспетчер => \"$user\"" : '');
									AddToLog("info", $logMessage, $sessionUserID, $orderID);
									$tpl->assign("main_message", "Данные успешно обновлены");
								}
								else
								{
									$tpl->assign("main_message", DisplayError("Во время выполнения запроса возникла ошибка"));
								}
							}
						}
					}
					// else
					// {
						// $tpl->assign("main_message", DisplayError(($error_msg ? $error_msg : "Не заполнено поле Заказчик!")));
					// }
				}
				
				if ($orderID)
				{
					$rez = pg_query($link, "select $tableOrder.*, $tableDriver.phone as driver_phone, $tableUser.phone as user_phone from $tableOrder left join $tableDriver on $tableOrder.driver_id = $tableDriver.id left join $tableUser on $tableOrder.user_id = $tableUser.id where $tableOrder.id=$orderID");
					$row = pg_fetch_array($rez);
					if ($row["departure_date"])
					{
						$row["start_date_Hour"] = date("H", $row["departure_date"]);
						$row["start_date_Minute"] = date("i", $row["departure_date"]);
					}
					else
					{
						$row["departure_date"] = "--";
						
					}
				
					if ($row["end_date"])
					{
						$row["end_date_Hour"] = date("H", $row["end_date"]);
						$row["end_date_Minute"] = date("i", $row["end_date"]);
					}
					else
					{
						$row["end_date"] = "--";
					}
					$tpl->assign('order', $row);
					
					$orderSBID = ($row["sd_number"] ? $row["sd_number"] : "ВН$orderID");
					$orderCarID = $row["car_id"];
					$orderDriverID = $row["driver_id"];
					$orderUserID = $row["user_id"];
					$tpl->assign("main_header", "Заявка $orderSBID");
				}
				else
				{
					$orderSBID = '';
					$orderCarID = '';
					$orderDriverID = '';
					$orderUserID = '';
					$row["departure_date"] = "--";
					$row["end_date"] = "--";
					$tpl->assign('order', $row);
					$tpl->assign("main_header", "Новая заявка");
				}
				
				$rez = pg_query($link, "select * from $tableCar order by model, regnum");
				$cars_options = "";
				while ( $row = pg_fetch_array($rez))
				{
						$id = $row["id"];
						$model = $row["model"];
						$regnum = $row["regnum"];
						$cars_options .= "<option value='$id' id='car_$id' ".($orderCarID && $orderCarID == $id ? "selected" : '').">$model $regnum</option>";
				}
				$tpl->assign('cars_options', $cars_options);
				
				$rez = pg_query($link, "select distinct $tableDriver.*, case when $tableOrder.id is not null then 1 else 0 end as order_id from $tableDriver inner join $tableCar on $tableDriver.car_id = $tableCar.id left join $tableOrder on ($tableDriver.id = $tableOrder.driver_id and $tableOrder.id is not null and $tableOrder.status = 4) where $tableDriver.status = 'active'".($sessionUserLocId ? " and $tableDriver.location_id = $sessionUserLocId" : "")." order by nick");
				
				$drivers_options = '';
				while ( $row = pg_fetch_array($rez))
				{
						$id = $row["id"];
						$nick = $row["nick"];
						$fullname = $row["fullname"];
						$carID = $row["car_id"];
						$driverPhone = $row["phone"];
						$order_id = $row["order_id"];
						$drivers_options .= "<option value='$id' ".($order_id && $order_id != $orderID ? "style = \"color:red\"" : '')." onclick=\"setCarAndPhone('car_$carID', '$driverPhone');\"".($orderDriverID && $orderDriverID == $id ? "selected" : '').">$nick ($fullname)</option>";
				}
				$tpl->assign('driver_options', $drivers_options);
				
				$rez = pg_query($link, "select * from $tableLocation order by name");
					$locations = array();
					while ( $row = pg_fetch_array($rez))
					{
							$id = $row["id"];
							$name = $row["name"];
							$locations[$id] = "$name";
					}
					$tpl->assign('locations', $locations);
				
				$rez = pg_query($link, "select * from $tableCredit order by name");
					$credits = array();
					while ( $row = pg_fetch_array($rez))
					{
							$id = $row["id"];
							$name = $row["name"];
							$descr = $row["descr"];
							$credits[$id] = "$name $descr";
					}
					$tpl->assign('credits', $credits);
					
					
				#SMS:

				$sms_driver_req = pg_query($link, "select distinct $tableDriver.*, case when $tableOrder.id is not null then 1 else 0 end as order_id from $tableDriver inner join $tableCar on $tableDriver.car_id = $tableCar.id left join $tableOrder on ($tableDriver.id = $tableOrder.driver_id and $tableOrder.id is not null and $tableOrder.status = 4) where $tableDriver.status = 'active' order by nick");
				
				while ( $row = pg_fetch_array($sms_driver_req))
				{
						$id = $row["id"];
						$nick = $row["nick"];
						$fullname = $row["fullname"];
						$carID = $row["car_id"];
						$driverPhone = $row["phone"];
						$order_id = $row["order_id"];
				}
				
				if ($sessionUserRole == 'administrator')
				{
					$rez = pg_query($link, "select * from $tableUser where role = 'dispatcher'");
					$users = array();
					$user_options = '';
					while ( $row = pg_fetch_array($rez))
					{
							$id = $row["id"];
							$login = $row["login"];
							$phone = $row["phone"];
							$users[$id] = "$login";
							$user_options .= "<option value='$id' onclick=\"SetUserPhone('$phone');\"".($orderUserID && $orderUserID == $id ? "selected" : '').">$login</option>";
					}
					$tpl->assign('users', $users);
					$tpl->assign('user_options', $user_options);
					$tpl->assign('sessionUserRole', $sessionUserRole);
				}
				
				
				$tpl->assign('editClass', 'selected');
				$tpl->assign("orderStatuses", $configOrderStatuses_new);
			}
			$hours = array_map("AddLeadingZeroes", range(0,23));
			$minutes = array_map("AddLeadingZeroes", range(0,59));
			// $end_minutes = array_map("AddLeadingZeroes", array(0,15,30,45));
			$end_minutes = array_map("AddLeadingZeroes", range(0,59));
			$hours_inf = array_map("AddLeadingZeroes", range(0,48));
			$tariff = array_map("AddLeadingZeroes", array('T1'=>'Груз','T2'=>'Почта и ТМЦ','T3'=>'Такси'));
			
			// $tpl->assign('minutes_d', array(
				// 00 => '00',
				// 15 => '15',
				// 30 => '30',
				// 45 => '45')
				// );
			$minutes_d = array_map("AddLeadingZeroes", range(0,59));
			$tpl->assign("minutes_d", $minutes_d);
			$tpl->assign("minutes", $minutes);
			$tpl->assign("end_minutes", $end_minutes);
			$tpl->assign("hours", $hours);
			$tpl->assign("hours_inf", $hours_inf);
			$tpl->assign("tariff", $tariff);
			
			$tpl->assign("orderID", $orderID);
			$tpl->assign('view', $view);
			$page = "dashboardDetail";
?>