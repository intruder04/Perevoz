<?php
	require 'config.php';
	require 'libs/PHPMailer/class.phpmailer.php';
	require 'libs/functions.php';
	//date_default_timezone_set('Europe/Moscow');

	/*
		Скачиваем новые файлы, помещаем их в папку и парсим
	*/
	$inbox = imap_open("{".$configMailServer.":143/novalidate-cert}", $configMailUserName, $configMailUserPassword);
	if (!$inbox)
	{
		print "не могу соединиться!";
		exit;
	}
	$link = pg_connect("host=$dbHost port=$dbPort dbname=$dbName user=$dbUser password=$dbPwd");
	$xmlFiles = array();

	/* get all new emails. If set to 'ALL' instead
	 * of 'NEW' retrieves all the emails, but can be
	 * resource intensive, so the following variable,
	 * $max_emails, puts the limit on the number of emails downloaded.
	 *
	 */
	$emails = imap_search($inbox,'ALL');
	//$emails = imap_search($inbox,'UNSEEN');

	/* useful only if the above search is set to 'ALL' */
	$max_emails = 1;


	/* if any emails found, iterate through each email */
	if($emails) {

		$count = 1;

		/* put the newest emails on top */
		rsort($emails);

		/* for every email... */
		foreach($emails as $email_number)
		{

			/* get information specific to this email */
			$overview = imap_fetch_overview($inbox,$email_number,0);

			/* get mail message */
			$message = imap_fetchbody($inbox,$email_number,2);

			/* get mail structure */
			$structure = imap_fetchstructure($inbox, $email_number);

			$attachments = array();

			/* if any attachments found... */
			if(isset($structure->parts) && count($structure->parts))
			{
				for($i = 0; $i < count($structure->parts); $i++)
				{
					$attachments[$i] = array(
						'is_attachment' => false,
						'filename' => '',
						'name' => '',
						'attachment' => ''
					);

					if($structure->parts[$i]->ifdparameters)
					{
						foreach($structure->parts[$i]->dparameters as $object)
						{
							if(strtolower($object->attribute) == 'filename')
							{
								$attachments[$i]['is_attachment'] = true;
								$attachments[$i]['filename'] = $object->value;
							}
						}
					}

					if($structure->parts[$i]->ifparameters)
					{
						foreach($structure->parts[$i]->parameters as $object)
						{
							if(strtolower($object->attribute) == 'name')
							{
								$attachments[$i]['is_attachment'] = true;
								$attachments[$i]['name'] = $object->value;
							}
						}
					}

					if($attachments[$i]['is_attachment'])
					{
						$attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);

						/* 4 = QUOTED-PRINTABLE encoding */
						if($structure->parts[$i]->encoding == 3)
						{
							$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
						}
						/* 3 = BASE64 encoding */
						elseif($structure->parts[$i]->encoding == 4)
						{
							$attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
						}
					}
				}
			}

			/* iterate through each attachment and save it */
			foreach($attachments as $attachment)
			{
				if($attachment['is_attachment'] == 1)
				{
					$filename = $attachment['name'];
					if(empty($filename)) $filename = $attachment['filename'];

					if(empty($filename)) $filename = time() . ".dat";

					/* prefix the email number to the filename in case two emails
					 * have the attachment with the same file name.
					 */
					$xmlFilePath = $configXmlInputDirectory."\\".date("YmdHis") . "_" . $filename;
					$fp = fopen($xmlFilePath, "w+");
					if (fwrite($fp, $attachment['attachment'])) array_push($xmlFiles, $xmlFilePath);
					fclose($fp);
					AddToLog("info", "Файл $xmlFilePath успешно получен от ВВБ");

				}
				sleep(2); //чтобы файлы не перезаписывали друг друга
			}
			//print "selected => $email_number\n";
			if($count++ >= $max_emails) break;
		}

		foreach($emails as $email_number)
		{
			//print "num => $email_number\n";
			imap_delete($inbox, $email_number);
		}
		imap_expunge($inbox);
	}

	/* close the connection */
	imap_close($inbox);



	$tickets = array();
	foreach ($xmlFiles as $xmlFile)
	{
		//Обработка каждого вложенного файла
		libxml_use_internal_errors(true);
		$xml = simplexml_load_file($xmlFile);

		//var_dump(get_object_vars($xml->DECLARATION));

		$sb_tickets = array();

		/*foreach ($xml->DECLARATION as $xmlDecl)
		{
			print "ok============\n";
			var_dump($xmlDecl);

		}
		exit;*/

		//foreach ($xml->DECLARATION->DECLGROUP->{"VALUE.OBJECT"} as $valueObject)
		foreach ($xml->DECLARATION as $xmlDecl)
		{
		foreach ($xmlDecl->DECLGROUP->{"VALUE.OBJECT"} as $valueObject)
		{

			$classname = $valueObject->INSTANCE[0]["CLASSNAME"];
			$sb_ticket = array();
			if ($classname == "Header") continue;

			$sb_ticket["classname"] = (string)$classname;
			foreach ($valueObject->INSTANCE[0] as $property)
			{
				if ($property["NAME"] == "СБ_ID")
				{
					$sb_ticket["sb_id"] = (string)$property->VALUE;
				}
				elseif (($property["NAME"] == "ИНФОРМАЦИЯ"))
				{
					$sb_ticket["info"] = (string)$property->VALUE;
				}
				elseif (($property["NAME"] == "ФЛАГ"))
				{
					$sb_ticket["flag"] = (string)$property->VALUE;
				}
				elseif (($property["NAME"] == "КС"))
				{
					$datetime = (string)$property->VALUE;
					//list($year, $month, $day, $hour, $minute, $second) = preg_split("/\./", $datetime);
					//$sb_ticket["start_date"] = (string)$property->VALUE;
					
					list($date, $time) = preg_split("/\s+/", $datetime);
					list($year,$month,$day) = preg_split("/\./", $date);
					list($hour, $minute, $second) = preg_split("/:/", $time);

					$sb_ticket["sb_srok"] = mktime($hour, $minute, $second, $month, $day, $year);
				}
				elseif (($property["NAME"] == "ИНИЦИАТОР"))
				{
					$sb_ticket["customer"] = (string)$property->VALUE;
				}
				elseif (($property["NAME"] == "ИСПОЛНИТЕЛЬ"))
				{
					$sb_ticket["sb_user"] = (string)$property->VALUE;
				}

			}
			array_push($sb_tickets, $sb_ticket);

		}
		}
		#var_dump($sb_tickets);

		#занесение в базу данных


		foreach ($sb_tickets as $sb_ticket)
		{
			$classname = $sb_ticket["classname"];
			$sb_id = $sb_ticket["sb_id"];

			print "Обрабатываем заявку $sb_id\n";
			if ($classname == "REJECT") //закрытие заявки
			{
				
				$rez = pg_query("select $tableOrder.id, $tableOrder.status, $tableOrder.start_date, $tableOrder.end_date, $tableOrder.departure_date, $tableOrder.solution, $tableOrder.price, $tableOrder.timeradius50km, $tableOrder.lagtime50km, $tableOrder.timeradius50km_minute, $tableOrder.lagtime50km_minute, $tableOrder.km_route, $tableOrder.sb_user from $tableOrder where sd_number = '$sb_id'");
				if (pg_num_rows($rez) > 0)
				{
					$row = pg_fetch_array($rez);
					$id = $row["id"];
					$status = $row["status"];
					$start_date = $row["start_date"];
					$end_date = $row["end_date"];
					$departure_date = $row["departure_date"];
					$solution = $row["solution"];
					$price = $row["price"];
					$timeradius50km = $row["timeradius50km"];
					$lagtime50km = $row["lagtime50km"];
					$timeradius50km_minute = $row["timeradius50km_minute"];
					$lagtime50km_minute = $row["lagtime50km_minute"];
					$km_route = $row["km_route"];
					$sb_user = $row["sb_user"];
					echo "TESTING reject - $id $status $solution";

					#массив для отправки:
					array_push($tickets, array("id" => $id, "sb_id" => $sb_id, "status" => $status, "start_date" => $start_date, "end_date" => $end_date, "departure_date" => $departure_date, "solution" => $solution, "price" => $price, "timeradius50km" => $timeradius50km, "lagtime50km" => $lagtime50km, "timeradius50km_minute" => $timeradius50km_minute, "lagtime50km_minute" => $lagtime50km_minute, "km_route" => $km_route, "sb_user" => $sb_user));
					
					#снятие заявки
					pg_query($link, "update $tableOrder set status = 8, solution = 'Отозвана из ПО ДРУГА' where sd_number = '$sb_id' and status <> 8");
					AddToLog("info", "Заявка $sb_id отозвана из ПО ДРУГА");
				}
				
				
			}
			else// NEW & IN_PROGRESS
			{
				$rez = pg_query("select $tableOrder.id, $tableOrder.status, $tableOrder.start_date, $tableOrder.end_date, $tableOrder.departure_date, $tableOrder.solution, $tableOrder.price, $tableOrder.timeradius50km, $tableOrder.lagtime50km, $tableOrder.timeradius50km_minute, $tableOrder.lagtime50km_minute, $tableOrder.km_route, $tableOrder.sb_user from $tableOrder where sd_number = '$sb_id'");
				if (pg_num_rows($rez) > 0)
				{
					$row = pg_fetch_array($rez);
					$id = $row["id"];
					$status = $row["status"];
					$start_date = $row["start_date"];
					$end_date = $row["end_date"];
					$departure_date = $row["departure_date"];
					$solution = $row["solution"];
					$price = $row["price"];
					$timeradius50km = $row["timeradius50km"];
					$lagtime50km = $row["lagtime50km"];
					$timeradius50km_minute = $row["timeradius50km_minute"];
					$lagtime50km_minute = $row["lagtime50km_minute"];
					$km_route = $row["km_route"];
					$sb_user = $row["sb_user"];
					echo "TESTING 1 - $timeradius50km $timeradius50km_minute $lagtime50km $lagtime50km_minute";

					#массив для отправки:
					array_push($tickets, array("id" => $id, "sb_id" => $sb_id, "status" => $status, "start_date" => $start_date, "end_date" => $end_date, "departure_date" => $departure_date, "solution" => $solution, "price" => $price, "timeradius50km" => $timeradius50km, "lagtime50km" => $lagtime50km, "timeradius50km_minute" => $timeradius50km_minute, "lagtime50km_minute" => $lagtime50km_minute, "km_route" => $km_route, "sb_user" => $sb_user));
				}
				else
				{	
					if ($classname == "NEW") {
					# исполнитель в по друга:
					$sb_user = $sb_ticket["sb_user"];
					$sb_srok = $sb_ticket["sb_srok"];
					$info = $sb_ticket["info"];
					$info = preg_replace("/\'/", "/ /", $info);
					$customer = $sb_ticket["customer"];
					// $start_date = $sb_ticket["start_date"];
					// if (!$start_date) $start_date = time();
					$start_date = time();
					$array = preg_split("/\n/", $info);
					$pairs = array();
					foreach($array as $pair)
					{
						if (preg_match("/:/", $pair))
						{
							$arr = preg_split("/:/", $pair, 2);
							if (isset($pairs[$arr[0]])) {
								#don't ovverride values
								echo "there's already pair for $arr[0]\n";
								
							}
							else {
							$pairs[trim($arr[0])] = trim($arr[1]);
							echo "arr - $arr[0] = $arr[1]\n";
								}
						}
					}
					//выставляем переменные
					
					// $address_from        = isset($pairs["Пункт отправления/адрес"]) ? trim($pairs["Пункт отправления/адрес"]) : '';
					// $address_to		     = isset($pairs["Пункт назначения/адрес"]) ? trim($pairs["Пункт назначения/адрес"]): '';
					// $ret_address_from    = isset($pairs["Пункт отправления (обратно)/адрес"]) ? trim($pairs["Пункт отправления (обратно)/адрес"]) : '';
					// $ret_address_to		 = isset($pairs["Пункт назначения (обратно)/адрес"]) ? trim($pairs["Пункт назначения (обратно)/адрес"]) : '';
					// $customer_phone		 = isset($pairs["Номер сотового телефона"]) ? trim($pairs["Номер сотового телефона"]) : '';
					// // $passenger			 = isset($pairs["Дополнительная информация"]) ? trim($pairs["Дополнительная информация"]) : '';
					// $dep_date			 = isset($pairs["Дата выезда"]) ? trim($pairs["Дата выезда"]) : '';
					// $dep_time			 = isset($pairs["Время выезда"]) ? trim($pairs["Время выезда"]) : '';
					// $ret_dep_date		 = isset($pairs["Дата выезда (обратно)"]) ? trim($pairs["Дата выезда (обратно)"]) : '';
					// $ret_dep_time		 = isset($pairs["Время выезда (обратно)"]) ? trim($pairs["Время выезда (обратно)"]) : '';
					// $direction			 = isset($pairs["Поездка"]) ? trim($pairs["Поездка"]) : '';
					
					#Для ЕСО (service manager)
					$address_from        = isset($pairs["Адрес пункта отправления"]) ? trim($pairs["Адрес пункта отправления"]) : '';
					$address_to		     = isset($pairs["Адрес пункта назначения"]) ? trim($pairs["Адрес пункта назначения"]): '';
					$ret_address_from    = isset($pairs["Адрес пункта отправления (обратно)"]) ? trim($pairs["Адрес пункта отправления (обратно)"]) : '';
					$ret_address_to		 = isset($pairs["Адрес пункта назначения (обратно)"]) ? trim($pairs["Адрес пункта назначения (обратно)"]) : '';
					$customer_phone		 = isset($pairs["Контактный телефон"]) ? trim($pairs["Контактный телефон"]) : trim($pairs["Мобильный телефон перевозимого сотрудника"]);
					//$passenger			 = isset($pairs["Дополнительная информация"]) ? trim($pairs["Дополнительная информация"]) : '';
					// $dep_date			 = isset($pairs["Дата выезда"]) ? trim($pairs["Дата выезда"]) : substr(trim($pairs["Время и дата выезда (ддммгггг ЧЧММ)"]), 0, -6);
					// $dep_time			 = isset($pairs["Время выезда (ЧЧММ)"]) ? trim($pairs["Время выезда (ЧЧММ)"]) : substr(trim($pairs["Время и дата выезда (ддммгггг ЧЧММ)"]), 10);
					
					// if (isset($pairs["Время выезда"])){
					// $dep_time			 = trim($pairs["Время выезда"]);
					// }
					$dep_date			 = isset($pairs["Дата и Время выезда"]) ? substr(trim($pairs["Дата и Время выезда"]), 0, 10) : substr(trim($pairs["Время и Дата выезда"]), 0, 10);
					echo "DATA - $dep_date";
					$dep_time			 = isset($pairs["Дата и Время выезда"]) ? substr(trim($pairs["Дата и Время выезда"]), 10).':00' : substr(trim($pairs["Время и Дата выезда"]), 10).':00';
					$ret_dep_date		 = isset($pairs["Дата выезда (обратно)"]) ? trim($pairs["Дата выезда (обратно)"]) : '';
					$ret_dep_time		 = isset($pairs["Время выезда (обратно) (ЧЧММ)"]) ? trim($pairs["Время выезда (обратно) (ЧЧММ)"]) : '';
					$direction			 = isset($pairs["Поездка"]) ? trim($pairs["Поездка"]) : '';

					//доп. информация может быть много строчной
					$array = preg_split("/Дополнительная информация:/", $info, 2);
					$passenger			 = isset($array[1]) ? trim($array[1]) : '';


					//echo "$sb_id => $dep_date => $dep_time\n";
					if ($dep_date && $dep_time)
					{
						list($day, $month, $year) = preg_split("/\.|\,/", $dep_date, 3);
						if (preg_match("/^\d{4}$/", $dep_time))
						{
							$hour = substr($dep_time , 0, 2);
							$minute = substr($dep_time, 2, 2);
						}
						else
						{
							list($hour, $minute) = preg_split("/(\.|\:|\-)/", $dep_time, 2);
						}
						//print "=$day=>$month=>$year==>$hour==>>$minute==>".(int)$minute."\n";
						if (!$departure_date = mktime($hour, (int)$minute, 0, $month, $day, $year)) $departure_date = "null";
					}
					else
					{
						$departure_date = 'null';
					}

					if ($ret_dep_date && $ret_dep_time)
					{
						list($day, $month, $year) = preg_split("/\.|\,/", $ret_dep_date, 3);
						if (preg_match("/^\d{4}$/", $ret_dep_time))
						{
							$hour = substr($ret_dep_time , 0, 2);
							$minute = substr($ret_dep_time, 2, 2);
						}
						else
						{
							list($hour, $minute) = preg_split("/(\.|\:|\-)/", $ret_dep_time, 2);
						}

						//list($hour, $minute) = preg_split("/(\.|\:|\-)/", $ret_dep_time, 2);
						if (!$ret_departure_date = mktime($hour, $minute, 0, $month, $day, $year)) $ret_departure_date = "null";
					}
					else
					{
						$ret_departure_date = 'null';
					}
					if ($rez = pg_query($link, "insert into $tableOrder (start_date, end_date, update_date, sd_number, customer, passenger, customer_phone, note, address_from, address_to, ret_address_from, ret_address_to, status, departure_date, ret_departure_date, dep_date, dep_time, ret_dep_date, ret_dep_time, sync, direction, sb_user, sb_srok) values ($start_date, null, ".time().", '$sb_id', '$customer', '$passenger', '$customer_phone', '$info', '$address_from', '$address_to', '$ret_address_from', '$ret_address_to', 1, $departure_date, $ret_departure_date, '$dep_date', '$dep_time', '$ret_dep_date', '$ret_dep_time', 1, '$direction', '$sb_user', '$sb_srok') returning id"))
					{
						$row = pg_fetch_array($rez);
						$insertID = $row["id"];
						//формируем  файл, информирующий о регистрации
						//передаем только номер
						array_push($tickets, array("id" => $insertID, "sb_id" => $sb_id, "status" => 1, "start_date" => $start_date, "end_date" => '', "departure_date" => $departure_date, "timeradius50km" => (isset($timeradius50km) ? $timeradius50km : ''), "timeradius50km_minute" => (isset($timeradius50km_minute) ? $timeradius50km_minute : ''), "lagtime50km" => (isset($lagtime50km) ? $lagtime50km : ''), "lagtime50km_minute" => (isset($lagtime50km_minute) ? $lagtime50km_minute : ''), "solution" => "", "sb_user" => $sb_user));

						AddToLog("info", "Добавлена заявка $sb_id, исполнитель - $sb_user", $user_id = 'null', $order_id = $insertID);
					}

					}
				}
			}

		}

	}


	//формируем файл для отправки

	if ($tickets) {
		
		//Отдельный файл для каждой территории (для каждого исполнителя в БД)
$users_req = pg_query($link, "select distinct sb_user as sb_user from $tableUser where sb_user is not null and sb_user <> ''");	
while ( $row = pg_fetch_array($users_req))
	{
			$sb_user = $row["sb_user"];
			echo "Processing user - $sb_user";
	
	$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<!DOCTYPE CIM PUBLIC \"SYSTEM\" \"CIM_DTD_V20.dtd\"[
<!ENTITY lt      \"&#38;#60;\">
<!ENTITY gt      \"&#62;\">
<!ENTITY amp     \"&#38;#38;\">
<!ENTITY apos    \"&#39;\">
<!ENTITY quot    \"&#34;\">]>
<CIM CIMVERSION=\"2.0\" DTDVERSION=\"2.2\">
 <DECLARATION>
  <DECLGROUP>
   <VALUE.OBJECT>
    <INSTANCE CLASSNAME=\"Header\">
     <PROPERTY NAME=\"Date\" TYPE=\"string\">
      <VALUE>".date("Y.m.d.H.i.s")."</VALUE>
     </PROPERTY>
     <PROPERTY NAME=\"Application\" TYPE=\"string\">
      <VALUE>perevozchik.System - ".$sb_user."</VALUE>
     </PROPERTY>
    </INSTANCE>
   </VALUE.OBJECT>";
	$counter = 0;

	
	foreach($tickets as $ticket)
	{	
		#пропускать заявки если исполнитель не соответствует
		$sb_user_c = $ticket["sb_user"];
		if ( $sb_user_c != $sb_user ) { continue; }
		$counter++;
		
		$id = $configIDPrefix.$ticket["id"];
		echo "id - $id";
		$sb_id = $ticket["sb_id"];
		$status = $ticket["status"];
		$start_date = ($ticket["start_date"] ? date("Y-m-d H:i:s", $ticket["start_date"]) : '');
		$end_date = ($ticket["end_date"] ? date("Y-m-d H:i:s", $ticket["end_date"]) : '');
		$departure_date = ($ticket["departure_date"] ? date("Y-m-d H:i:s", $ticket["departure_date"]) : '');
		$solution = $ticket["solution"];
		$price = isset($ticket["price"]) ? $ticket["price"] : '';
		$timeradius50km = isset($ticket["timeradius50km"]) ? $ticket["timeradius50km"] : '';
		$lagtime50km = isset($ticket["lagtime50km"]) ? $ticket["lagtime50km"] : '';
		$km_route = isset($ticket["km_route"]) ? $ticket["km_route"] : '';
		$timeradius50km_minute = isset($ticket["timeradius50km_minute"]) ? $ticket["timeradius50km_minute"] : '';
		$lagtime50km_minute = isset($ticket["lagtime50km_minute"]) ? $ticket["lagtime50km_minute"] : '';
		
		echo "TESTING 2 - $timeradius50km $timeradius50km_minute $lagtime50km $lagtime50km_minute";
		
		/*if ($status == 4) //назначен автомобиль
		{
			$xml .= "<VALUE.OBJECT>
			<INSTANCE CLASSNAME=\"WAIT\">
			<PROPERTY NAME=\"ID\" TYPE=\"string\">
			  <VALUE>$counter</VALUE>
			 </PROPERTY>
			 <PROPERTY NAME=\"СБ_ID\" TYPE=\"string\">
			  <VALUE>$sb_id</VALUE>
			 </PROPERTY>
			 <PROPERTY NAME=\"ИДЕНТИФИКАТОР\" TYPE=\"string\">
			  <VALUE>$id</VALUE>
			 </PROPERTY>
			 <PROPERTY NAME=\"СТАТУС\" TYPE=\"string\">
			  <VALUE>5</VALUE>
			 </PROPERTY>
			 <PROPERTY NAME=\"РЕШЕНИЕ\" TYPE=\"string\">
			  <VALUE>$solution</VALUE>
			 </PROPERTY>
			</INSTANCE>
		   </VALUE.OBJECT>";
		}*/
		if ($status == 5 || $status == 6 || $status == 7 || $status == 8 || $status == 9) //заявка выполнена(7) или снята(8)
		{
			echo "status - $status\n";
			if ($status == 7)
			{
				$code = 1;
			}
			elseif ($status == 8)
			{
				$code = 4;
			}
			elseif ($status == 9)
			{
				$code = 2;
			}
			else
			{
				$code = 1;
			}
			#$timeradius50km		 = isset($timeradius50km) ? $timeradius50km : '00';
			#$timeradius50km_minute = isset($timeradius50km_minute) ? $timeradius50km_minute : '00';
			#$lagtime50km = isset($lagtime50km) ? $lagtime50km : '00';
			#$lagtime50km_minute = isset($lagtime50km_minute) ? $lagtime50km_minute : '00';
			
			echo "TESTING DONE - $timeradius50km $timeradius50km_minute $lagtime50km $lagtime50km_minute\n";
			
			$xml .= " 
			<VALUE.OBJECT>
			<INSTANCE CLASSNAME=\"DONE\">
			 <PROPERTY NAME=\"ID\" TYPE=\"string\">
			  <VALUE>$counter</VALUE>
			 </PROPERTY>
			 <PROPERTY NAME=\"СБ_ID\" TYPE=\"string\">
			  <VALUE>$sb_id</VALUE>
			 </PROPERTY>
			 <PROPERTY NAME=\"ИДЕНТИФИКАТОР\" TYPE=\"string\">
			  <VALUE>$id</VALUE>
			 </PROPERTY>
			 <PROPERTY NAME=\"СТАТУС\" TYPE=\"string\">
			  <VALUE>2</VALUE>
			 </PROPERTY>
			 <PROPERTY NAME=\"НАЧАЛО_РАБОТ\" TYPE=\"string\">
			  <VALUE>".($status == 5 ? $departure_date : date("d/m/Y H:i", (time()-1)))."</VALUE>
			 </PROPERTY>
			 <PROPERTY NAME=\"ВЫПОЛНЕНО\" TYPE=\"string\">
			  <VALUE>".($status == 5 ? $end_date : date("d/m/Y H:i"))."</VALUE>
			 </PROPERTY>
			 <PROPERTY NAME=\"КОД_ЗАКРЫТИЯ\" TYPE=\"string\">
			  <VALUE>".$code."</VALUE>
			 </PROPERTY>
			 <PROPERTY NAME=\"РЕШЕНИЕ\" TYPE=\"string\">
			  <VALUE>$solution</VALUE>
			 </PROPERTY>
			 <PROPERTY NAME=\"PRICE\" TYPE=\"string\">
			  <VALUE>$price</VALUE>
			 </PROPERTY>
			 <PROPERTY NAME=\"NOLAG_RADIUS_50\" TYPE=\"string\">
			  <VALUE>".$timeradius50km.":".$timeradius50km_minute."</VALUE>
			 </PROPERTY>
			 <PROPERTY NAME=\"LAGTIME_50\" TYPE=\"string\">
			  <VALUE>".$lagtime50km.":".$lagtime50km_minute."</VALUE>
			 </PROPERTY>
			 <PROPERTY NAME=\"KILOM\" TYPE=\"string\">
			  <VALUE>$km_route</VALUE>
			 </PROPERTY>
			</INSTANCE>
			</VALUE.OBJECT>";
		}
		
		// Удалил выше:
		// <PROPERTY NAME=\"ЗАРЕГИСТРИРОВАНО\" TYPE=\"string\">
		// <VALUE>$start_date</VALUE>
		// </PROPERTY>
		
		/*elseif ($status == 3) //ожидает
		{
			$xml .= "<VALUE.OBJECT>
			<INSTANCE CLASSNAME=\"APPROVE\">
			<PROPERTY NAME=\"ID\" TYPE=\"string\">
			  <VALUE>$counter</VALUE>
			 </PROPERTY>
			 <PROPERTY NAME=\"СБ_ID\" TYPE=\"string\">
			  <VALUE>$sb_id</VALUE>
			 </PROPERTY>
			 <PROPERTY NAME=\"ИДЕНТИФИКАТОР\" TYPE=\"string\">
			  <VALUE>$id</VALUE>
			 </PROPERTY>
			  <PROPERTY NAME=\"СТАТУС\" TYPE=\"string\">
			  <VALUE>3</VALUE>
			 </PROPERTY>
			 <PROPERTY NAME=\"РЕШЕНИЕ\" TYPE=\"string\">
			  <VALUE>$solution</VALUE>
			 </PROPERTY>
			</INSTANCE>
		   </VALUE.OBJECT>";
		}*/
		elseif ($status == 1 || $status == 2)//новый и в работе
		{
			$xml .= "
			<VALUE.OBJECT>
			<INSTANCE CLASSNAME=\"IN_PROGRESS\">
			<PROPERTY NAME=\"ID\" TYPE=\"string\">
			  <VALUE>$counter</VALUE>
			 </PROPERTY>
			 <PROPERTY NAME=\"СБ_ID\" TYPE=\"string\">
			  <VALUE>$sb_id</VALUE>
			 </PROPERTY>
			 <PROPERTY NAME=\"ИДЕНТИФИКАТОР\" TYPE=\"string\">
			  <VALUE>$id</VALUE>
			 </PROPERTY>
			</INSTANCE>
			</VALUE.OBJECT>";
		}
		else
		{
			//для непонятного статуса
		}
	}
	$xml .= " 
			</DECLGROUP>
			</DECLARATION>
			</CIM>\n";
	
	if ($counter >= 1 ) {
	//print $xml;
	$file = "$configXmlOutputDirectory/" . substr($configAttachName, 0, -4) . date("YmdHis") . ".xml";
	$fp = fopen($file,"w");
	// below is where the log message has been written to a file.
	fputs($fp,$xml);
	// close the open said file after writing the text
	fclose($fp);

	//отправляем
	$mail = new PHPMailer;
	$mail->IsSMTP();                                      // Set mailer to use SMTP
	$mail->Host = $configSMTPServer;  // Specify main and backup server
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = $configSMTPUserName;                            // SMTP username
	$mail->Password = $configSMTPUserPassword;                           // SMTP password
	$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'tls' 'ssl' also accepted
	$mail->Port       = $configSMTPPort;
	//$mail->SMTPDebug  = 2;
	$mail->From = $configSMTPUserName;
	$mail->FromName = $configSMTPFromName;
	$mail->AddAddress($configMailTo);  // Add a recipient
	// $mail->AddAddress("neo.anderson@bk.ru");  // Add a recipient
	// $mail->AddCC('may.viktor@gmail.com');
	// Name is optional
	$mail->AddReplyTo($configSMTPUserName, $configSMTPFromName);


	$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
	$mail->AddAttachment($file, $configAttachName);         // Add attachments
	$mail->IsHTML(true);                                  // Set email format to HTML

	# тема из конфига, словарь для каждого пользователя
	
	$mail->Subject = $configMailSubject;
	$mail->Body    = $configMailSubject;
	$mail->AltBody = $configMailSubject;

	if(!$mail->Send()) {
	   echo 'Message could not be sent.';
	   echo 'Mailer Error: ' . $mail->ErrorInfo;
	   AddToLog("error", "Не удалось отослать файл $file");
	   exit;
	}
	AddToLog("info", "Файл $file успешно отослан");
	sleep(5);
	echo 'Message has been sent';
	echo "Done";
	}
	}
}
pg_close($link);
?>
