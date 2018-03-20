<?php
	$tariff_dict = array('T1'=>'Груз','T2'=>'Почта и ТМЦ','T3'=>'Такси');
	ini_set("max_execution_time", "600");
	session_start();
	date_default_timezone_set('Europe/Moscow');
	
	//phpinfo();
	require 'config.php';
	require 'libs/functions.php';
    require_once 'libs/PHPExcel.php';
	
	$link = pg_connect("host=$dbHost port=$dbPort dbname=$dbName user=$dbUser password=$dbPwd");
	include 'phpscripts/getSettings.php';
	if (isset($_SESSION['user_id']) && $_SESSION["user_id"])
    {
		$report = (isset($_POST["report"]) ? $_POST["report"] : '');
		$credit_id = (isset($_POST["credit_id"]) ? preg_replace("/[^0-9]/", '', $_POST["credit_id"]) : '');
		$driver_id = (isset($_POST["driver_id"]) ? preg_replace("/[^0-9]/", '', $_POST["driver_id"]) : '');
		$user_id = (isset($_POST["user_id"]) ? preg_replace("/[^0-9]/", '', $_POST["user_id"]) : '');
		$status = (isset($_POST["status"]) ? preg_replace("/[^0-9]/", '', $_POST["status"]) : '');
		$type = (isset($_POST["type"]) ? $_POST["type"] : '');
		$sb_user = (isset($_POST["sb_user"]) ? $_POST["sb_user"] : '');
		$instance_name = 'Краснодар ' . $sb_user;
		
		
		$report_start_Day = (isset($_POST["report_start_Day"]) ? preg_replace("/[^0-9]/", '', $_POST["report_start_Day"]) : '');
		$report_start_Month = (isset($_POST["report_start_Month"]) ? preg_replace("/[^0-9]/", '', $_POST["report_start_Month"]) : '');
		$report_start_Year = (isset($_POST["report_start_Year"]) ? preg_replace("/[^0-9]/", '', $_POST["report_start_Year"]) : '');
		
		
		$report_end_Day = (isset($_POST["report_end_Day"]) ? preg_replace("/[^0-9]/", '', $_POST["report_end_Day"]) : '');
		$report_end_Month = (isset($_POST["report_end_Month"]) ? preg_replace("/[^0-9]/", '', $_POST["report_end_Month"]) : '');
		$report_end_Year = (isset($_POST["report_end_Year"]) ? preg_replace("/[^0-9]/", '', $_POST["report_end_Year"]) : '');
		
		if (checkdate($report_start_Month, $report_start_Day, $report_start_Year) && checkdate($report_end_Month, $report_end_Day, $report_end_Year))
		{
			$report_start_date = mktime(0, 0, 0, $report_start_Month, $report_start_Day, $report_start_Year);
			$report_end_date = mktime(23, 59, 59, $report_end_Month, $report_end_Day, $report_end_Year);
			$pExcel = new PHPExcel();
					
			
			$sharedUnderlineStyle = new PHPExcel_Style();	
			$sharedUnderlineStyle->applyFromArray(
			
							array(
									'font'=>array(
														'name'=>'Calibri',
														'size'=>'11',
														'underline'=>true),
									'alignment'=>array(
														'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER
													)
		     ));
			 
			 
			$sharedJustBoldStyle = new PHPExcel_Style();	
			$sharedJustBoldStyle->applyFromArray(
			
							array(
									'font'=>array(
														'name'=>'Calibri',
														'size'=>'11',
														'bold'=>true),
									'alignment'=>array(
														'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER
													)
		     ));
			 
			$sharedJustCenteredStyle = new PHPExcel_Style();	
			$sharedJustCenteredStyle->applyFromArray(
			
							array(
									'font'=>array(
														'name'=>'Calibri',
														'size'=>'11'),
									'alignment'=>array(
														'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER
													)
		     ));
			
			$sharedUnderlineCellStyle = new PHPExcel_Style();	
			$sharedUnderlineCellStyle->applyFromArray(
			
							array(
									'font'=>array(
														'name'=>'Calibri',
														'size'=>'11'),
									'borders' => array(
														'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
														
													),
									'alignment'=>array(
														'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
													)
		     ));
			
			$sharedHeaderStyle = new PHPExcel_Style();	
			$sharedHeaderStyle->applyFromArray(
			
							array(
									'font'=>array(
														'name'=>'Arial Cyr',
														'size'=>'10',
														'bold'=>true ),
								  'borders' => array(
														'allborders'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
														
													),
									'alignment'=>array(
														'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
														'vertical'=>PHPExcel_Style_Alignment::VERTICAL_TOP
													)
		     ));
			 
			$sharedHeaderTableStyleAct = new PHPExcel_Style();	
			$sharedHeaderTableStyleAct->applyFromArray(
			
							array(
									'font'=>array(
														'name'=>'Calibri',
														'size'=>'9',
														'bold'=>true ),
									'alignment'=>array(
														'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
														'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,
														'wrap'=>true
													),
									'borders' => array(
														'allborders'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
													)
		     ));
			 
			 $sharedBodyTableStyleAct = new PHPExcel_Style();
			 $sharedBodyTableStyleAct->applyFromArray(
			
							array(
									'font'=>array(
														'name'=>'Calibri',
														'size'=>'9'
														),
									'alignment'=>array(
														'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
														'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER
													),
									'borders' => array(
														'allborders'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
													)
		     ));
			 
			$sharedHeaderStyleAct = new PHPExcel_Style();	
			$sharedHeaderStyleAct->applyFromArray(
			
							array(
									'font'=>array(
														'name'=>'Calibri',
														'size'=>'14',
														'bold'=>true ),
									'alignment'=>array(
														'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
														'vertical'=>PHPExcel_Style_Alignment::VERTICAL_TOP
													)
		     ));
			 
			 
			$sharedDisclaimerStyleAct = new PHPExcel_Style();	
			$sharedDisclaimerStyleAct->applyFromArray(
			
							array(
									'font'=>array(
														'name'=>'Calibri',
														'size'=>'11',
														'bold'=>false ),
									'alignment'=>array(
														'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
														'vertical'=>PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
														'wrap'=>true
													)
		     ));
			 
			
			$sharedBoldStyle = new PHPExcel_Style();	
			$sharedBoldStyle->applyFromArray(
			
							array(
									'font'=>array(
														'name'=>'Arial Cyr',
														'size'=>'10',
														'bold'=>true ),
								  'borders' => array(
														'allborders'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
														
													)
		     ));
			
			$sharedRowStyle = new PHPExcel_Style();	
			$sharedRowStyle->applyFromArray(
			
							array(
									'font'=>array(
														'name'=>'Arial Cyr',
														'size'=>'10'
														 ),
								  'borders' => array(
														'allborders'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
														
													)));
			$sharedAlignRightStyle = new PHPExcel_Style();	
			$sharedAlignRightStyle->applyFromArray(
			
							array(
									'alignment'=>array(
														'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
														'vertical'=>PHPExcel_Style_Alignment::VERTICAL_TOP )
									));
			$sharedAlignLeftStyle = new PHPExcel_Style();	
			$sharedAlignLeftStyle->applyFromArray(
			
							array(
									'alignment'=>array(
														'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
														'vertical'=>PHPExcel_Style_Alignment::VERTICAL_TOP )
									));
			
			if ($report == "rep_01")
			{
				$pExcel->setActiveSheetIndex(0);
				$aSheet = $pExcel->getActiveSheet();
				$aSheet->setTitle($configReports[$report]);
				$i = 1;
				$headerRow = $i;
				$aSheet->setCellValue("A$i",'Дата');
				$aSheet->setCellValue("B$i",'Заявка');
				$aSheet->setCellValue("C$i",'Фамилия');
				$aSheet->setCellValue("D$i",'Позывной');
				$aSheet->setCellValue("E$i",'Ответственный');
				$aSheet->setCellValue("F$i",'Маршрут');
				$aSheet->setCellValue("G$i",'Начало заявки');
				$aSheet->setCellValue("H$i",'Конец заявки');
				$aSheet->setCellValue("I$i",'Время работы');
				$aSheet->setCellValue("J$i",'Простой');
				$aSheet->setCellValue("K$i",'Километраж');
				$aSheet->setCellValue("L$i",'Стоимость');
				$aSheet->setCellValue("M$i",'Тариф');
				$aSheet->setCellValue("N$i",'Исполнитель в СБ');
				$aSheet->getColumnDimension('A')->setWidth(15);
				$aSheet->getColumnDimension('B')->setWidth(10);
				$aSheet->getColumnDimension('C')->setWidth(30);
				$aSheet->getColumnDimension('D')->setWidth(10);
				$aSheet->getColumnDimension('E')->setWidth(40);
				$aSheet->getColumnDimension('F')->setWidth(60);
				$aSheet->getColumnDimension('G')->setWidth(15);
				$aSheet->getColumnDimension('H')->setWidth(15);
				$aSheet->getColumnDimension('I')->setWidth(20);
				$aSheet->getColumnDimension('J')->setWidth(20);
				$aSheet->getColumnDimension('K')->setWidth(15);
				$aSheet->getColumnDimension('L')->setWidth(15);
				$aSheet->getColumnDimension('M')->setWidth(20);
				$aSheet->getColumnDimension('N')->setWidth(20);
				
				
				$pExcel->getActiveSheet()->setSharedStyle($sharedHeaderStyle, "A$i:M$i");
													
				$i++;
				$firstRow = $i;
				$typeSQL = ($type == 1 ? "and $tableOrder.sd_number is not null" : ($type == 2? "and $tableOrder.sd_number is null" : ''));
				$rez = pg_query($link, "select $tableOrder.id, $tableOrder.sd_number, $tableOrder.customer, $tableOrder.start_date, $tableOrder.price, $tableOrder.tariff, $tableOrder.km_route, $tableOrder.timeradius50km, $tableOrder.timeradius50km_minute, $tableOrder.lagtime50km, $tableOrder.lagtime50km_minute, $tableDriver.nick, $tableDriver.fullname, $tableOrder.address_from, $tableOrder.address_to, $tableOrder.ret_address_from, $tableOrder.ret_address_to, $tableOrder.departure_date, $tableOrder.end_date, $tableOrder.status, $tableOrder.sb_user, $tableCar.regnum from $tableOrder left join $tableDriver on $tableOrder.driver_id = $tableDriver.id left join $tableCredit on $tableOrder.credit_id = $tableCredit.id left join $tableUser on $tableOrder.user_id = $tableUser.id left join $tableCar on $tableOrder.car_id = $tableCar.id where departure_date between $report_start_date and $report_end_date ".($status ? " and $tableOrder.status = $status" : '').($credit_id ? " and $tableOrder.credit_id = $credit_id" : '').($driver_id ? " and $tableOrder.driver_id = $driver_id" : '').($user_id ? " and $tableOrder.user_id = $user_id" : '').($sb_user ? " and $tableOrder.sb_user = '$sb_user'" : '')." $typeSQL order by sd_number");
				$sum_time = 0;
				while ($row = pg_fetch_array($rez))
				{
					$sd_number = ($row["sd_number"] ? $row["sd_number"] : "ВН".$row["id"]);
					$customer = $row["customer"];
					$start_date = $row["start_date"];
					$nick = $row["nick"];
					$fullname = $row["fullname"];
					$status = $row["status"];
					$status = $configOrderStatuses[$status];
					$route = $row["address_from"]." - ".$row["address_to"].($row["ret_address_to"] ? " - ".$row["ret_address_from"]." - ".$row["ret_address_to"] :'');
					$departure_date = $row["departure_date"];
					$end_date = $row["end_date"];
					$time = $end_date - $departure_date;
					$regnum = $row["regnum"];
					$price = $row["price"];
					$km_route = $row["km_route"];
					$timeradius50km = $row["timeradius50km"];
					$timeradius50km_minute = $row["timeradius50km_minute"];
					$lagtime50km = $row["lagtime50km"];
					$lagtime50km_minute = $row["lagtime50km_minute"];
					$tariff = $row["tariff"];
					$sb_user = $row["sb_user"];
				
					$aSheet->setCellValue("A$i", date("d.m.Y", $start_date));
					$aSheet->setCellValue("B$i", $sd_number);
					$aSheet->setCellValue("C$i", $fullname);
					$aSheet->setCellValue("D$i", ($nick ? "$nick / $regnum": "$nick $regnum"));
					$aSheet->setCellValue("E$i", $customer);
					$aSheet->setCellValue("F$i", $route);
					// $aSheet->setCellValue("F$i", $price);
					if ($departure_date) $aSheet->setCellValue("G$i", date("d.m.Y H:i", $departure_date));
					if ($end_date) 
					{
						$aSheet->setCellValue("H$i", date("d.m.Y H:i", $end_date));
					}
					else
					{	
						$aSheet->setCellValue("H$i", $status);
					}
					
					$aSheet->setCellValue("I$i", ($timeradius50km?sprintf('%02d',$timeradius50km):"00").":".($timeradius50km_minute?sprintf('%02d',$timeradius50km_minute):"00"));
					// $aSheet->setCellValue("I$i", ($timeradius50km?sprintf('%02d',$timeradius50km):"-").":".($timeradius50km_minute > 0)?($timeradius50km_minute):(sprintf('%02d',$timeradius50km_minute)));
					// $aSheet->setCellValue("J$i", $lagtime50km.":".$lagtime50km_minute);
					$aSheet->setCellValue("J$i", ($lagtime50km?sprintf('%02d',$lagtime50km):"00").":".($lagtime50km_minute?sprintf('%02d',$lagtime50km_minute):"00"));
					$aSheet->setCellValue("K$i", $km_route);
					$aSheet->setCellValue("L$i", $price);
					$aSheet->setCellValue("M$i", $tariff ? $tariff_dict[$tariff] : '');
					$aSheet->setCellValue("N$i", $sb_user ? $sb_user : '');
					
					// if ($departure_date && $end_date && $time > 0)
					// {
						
						// $aSheet->setCellValue("I$i", CountTripTime($time));
						// $roundTime = CountTripTime($time, 1);
						// $aSheet->setCellValue("J$i", $roundTime);
						// $aSheet->setCellValue("K$i", $settingsPriceOneHour * $roundTime);
						// $sum_time += $roundTime;
						
					// }
					// else
					// {
						// $pExcel->setActiveSheetIndex(0)->mergeCells("H$i:L$i");
					// }
					$i++;
				}
				
				$pExcel->getActiveSheet()->setSharedStyle($sharedRowStyle, "A$firstRow:N".($i-1));
				$aSheet->setCellValue("A$i", "Итого");
				/*$aSheet->setCellValue("I$i", CountTripTime($sum_time));
				$aSheet->setCellValue("J$i", CountTripTime($sum_time, 1));
				$aSheet->setCellValue("K$i", $settingsPriceOneHour * CountTripTime($sum_time, 1));*/
				
				// $aSheet->setCellValue("I$i", CountTripTime($sum_time*3600));
				// $aSheet->setCellValue("J$i", $sum_time);
				// $aSheet->setCellValue("K$i", $settingsPriceOneHour * $sum_time);
				// $aSheet->setCellValue("K$i", $price);
				
				$pExcel->getActiveSheet()->setSharedStyle($sharedBoldStyle, "A$i:N$i");
				
			}
			elseif ($report == "rep_02")
			{
				
				$rez = pg_query($link, "select $tableUser.login, $tableLog.datetime from $tableLog inner join $tableUser on $tableLog.user_id = $tableUser.id join $tableOrder on $tableLog.order_id = $tableOrder.id where $tableUser.role = 'dispatcher' and $tableLog.datetime between $report_start_date and $report_end_date");
				
				$stat = array();
				while ($row = pg_fetch_array($rez))
				{
					$day = date("Ymd", $row["datetime"]);
					$day_str = date("d.m.Y", $row["datetime"]);
					$login = $row["login"];
					$stat[$login][$day]["day_str"] = $day_str;
					if (!array_key_exists("order", $stat[$login][$day])) $stat[$login][$day]["order"] = array(); 
				}
				
				$rez = pg_query($link, "select $tableUser.login, $tableOrder.sd_number, min($tableLog.datetime) as order_start from $tableLog inner join $tableUser on $tableLog.user_id = $tableUser.id inner join $tableOrder on $tableLog.order_id = $tableOrder.id where $tableUser.role = 'dispatcher' and $tableLog.datetime between $report_start_date and $report_end_date group by $tableUser.login, $tableOrder.sd_number");
				
				while ($row = pg_fetch_array($rez))
				{
					$day = date("Ymd", $row["order_start"]);
					$login = $row["login"];
					$order = $row["sd_number"];
					//if (!array_key_exists("order", $stat[$login][$day])) $stat[$login][$day]["order"] = array(); 
					array_push($stat[$login][$day]["order"], $order);
				}
				
				$pExcel->setActiveSheetIndex(0);
				$aSheet = $pExcel->getActiveSheet();
				$aSheet->setTitle($configReports[$report]);
				$i = 1;
				
				$aSheet->getColumnDimension('A')->setWidth(15);
				$aSheet->getColumnDimension('B')->setWidth(20);
				$aSheet->getColumnDimension('C')->setWidth(30);

				ksort($stat);
				while (list($login, $array) = each($stat))
				{
					
					$aSheet->setCellValue("A$i",'Диспетчер');
					$aSheet->setCellValue("B$i",'Рабочие дни');
					$aSheet->setCellValue("C$i",'Взято в работу заявок');
					
					$pExcel->getActiveSheet()->setSharedStyle($sharedHeaderStyle, "A$i:C$i");
					$i++;
					$firstRow = $i;
					$sum = 0;
					ksort($array);
					while (list($day, $array1) = each($array))
					{
						$day_str = $array1["day_str"];
						$orders = $array1["order"];
						
						$aSheet->setCellValue("A$i", $login);
						$aSheet->setCellValue("B$i", $day_str);
						$aSheet->setCellValue("C$i", count($orders));
						$sum += count($orders);
						$i++;
					}
					$pExcel->getActiveSheet()->setSharedStyle($sharedRowStyle, "A$firstRow:C".($i-1));
					$aSheet->setCellValue("A$i", "Итого");
					$aSheet->setCellValue("C$i", $sum);
					$pExcel->getActiveSheet()->setSharedStyle($sharedBoldStyle, "A$i:C$i");
					$i +=2;
				}
			}
			# АКТ
			elseif ($report == "rep_03") {
				$tableArray = array('gruz'=>'Таблица 1 (Режим "Доставка грузов")',
									'post'=>'Таблица 2 (Режим "Доставка ТМЦ, сбор документов, почта")',
									'everyday'=>'Таблица 3 ("Еженедельная доставка")',
									'taxi'=>'Таблица 4 (Режим "Такси")',
									'wait'=>'Таблица 5 (Режим "Ожидания")',
									'zakr'=>'Таблица 6 (Режим "Аренда закрепления")'
									);
				$priceArrayVr = array('gruz'=>464,
								'post'=>424,
								'everyday'=>424,
								'taxi'=>448,
								'wait'=>240,
								'zakr'=>600
				);
				$priceArrayKm = array('gruz'=>17,
								'post'=>20,
								'everyday'=>20,
								'taxi'=>16.6,
								'wait'=>"",
								'zakr'=>424.8
				);
			
			
				$pExcel->setActiveSheetIndex(0);
				$aSheet = $pExcel->getActiveSheet();
				$aSheet->setTitle($configReports[$report]);
				$i = 1;
				$headerRow = $i;
				$aSheet->setCellValue("A$i",'Акт сдачи-приемки оказанных услуг');
				$aSheet->mergeCells("A$i".":J$i");
				// $aSheet->getColumnDimension('A')->setWidth(15);
				$pExcel->getActiveSheet()->setSharedStyle($sharedHeaderStyleAct, "A$i");
								
				$i += 1;
				$aSheet->setCellValue("F$i",'от');
				$aSheet->setCellValue("G$i", $report_end_Day.'.'.$report_end_Month.'.'.$report_end_Year);
				$pExcel->getActiveSheet()->setSharedStyle($sharedUnderlineStyle, "G$i");
				
				
				$i += 2;
				$aSheet->setCellValue("A$i",'	Общество с ограниченной ответственностью «ПеревозчикЪ-НН», в дальнейшем «Исполнитель», в лице директора Сомова Сергея Викторовича, действующего на основании Устава, и Юго-Западный банка ПАО Сбербанк, именуемый в дальнейшем «Заказчик», в лице  Кравченко В. А. с другой стороны, подписали настоящий Акт о нижеследующем:');
				$aSheet->mergeCells("A$i".":J$i");
				$pExcel->getActiveSheet()->setSharedStyle($sharedDisclaimerStyleAct, "A$i");
				$aSheet->getRowDimension($i)->setRowHeight(60);
				
				$i += 1;
				$aSheet->setCellValue("A$i",'	В соответствии с договором №  5200-12817 от  30.12.2016 г Исполнитель и Заказчик засвидетельствовали выполнение транспортных услуг в полном объеме в период');
				$aSheet->mergeCells("A$i".":J$i");
				$pExcel->getActiveSheet()->setSharedStyle($sharedDisclaimerStyleAct, "A$i");
				$aSheet->getRowDimension($i)->setRowHeight(30);
				
				$i += 1;
				$aSheet->setCellValue("B$i",'с '.$report_start_Day.'.'.$report_start_Month.'.'.$report_start_Year);
				$aSheet->mergeCells("B$i".":C$i");
				$aSheet->setCellValue("D$i",'по');
				$aSheet->setCellValue("E$i", $report_end_Day.'.'.$report_end_Month.'.'.$report_end_Year);
				$aSheet->setCellValue("G$i",'.');
				$aSheet->mergeCells("E$i".":F$i");
				$pExcel->getActiveSheet()->setSharedStyle($sharedDisclaimerStyleAct, "B$i:F$i");
				$pExcel->getActiveSheet()->setSharedStyle($sharedUnderlineStyle, "B$i");
				$pExcel->getActiveSheet()->setSharedStyle($sharedUnderlineStyle, "E$i");
				
									

				#get data about calls:
				
				
				// $typeSQL = ($type == 1 ? "and $tableOrder.sd_number is not null" : ($type == 2? "and $tableOrder.sd_number is null" : ''));
				$rez = pg_query($link, "select $tableOrder.id, $tableOrder.sd_number, $tableOrder.customer, $tableOrder.start_date, $tableOrder.price, $tableOrder.tariff, $tableOrder.km_route, $tableOrder.timeradius50km, $tableOrder.timeradius50km_minute, $tableOrder.lagtime50km, $tableOrder.lagtime50km_minute, $tableDriver.nick, $tableDriver.fullname, $tableOrder.address_from, $tableOrder.address_to, $tableOrder.ret_address_from, $tableOrder.ret_address_to, $tableOrder.departure_date, $tableOrder.end_date, $tableOrder.status, $tableOrder.sb_user, $tableCar.regnum from $tableOrder left join $tableDriver on $tableOrder.driver_id = $tableDriver.id left join $tableCredit on $tableOrder.credit_id = $tableCredit.id left join $tableUser on $tableOrder.user_id = $tableUser.id left join $tableCar on $tableOrder.car_id = $tableCar.id where departure_date between $report_start_date and $report_end_date".($status ? " and $tableOrder.status = $status" : '').($credit_id ? " and $tableOrder.credit_id = $credit_id" : '').($driver_id ? " and $tableOrder.driver_id = $driver_id" : '').($sb_user ? " and $tableOrder.sb_user = '$sb_user'" : '').($user_id ? " and $tableOrder.user_id = $user_id" : '')." order by sd_number");
				
				
				$distance_sum_gruz = 0;
				$work_time_sum_gruz = 0;
				
				$distance_sum_post = 0;
				$work_time_sum_post = 0;
				
				$distance_sum_taxi = 0;
				$work_time_sum_taxi = 0;
				$lag_time_sum_taxi = 0;
				
				while ($row = pg_fetch_array($rez))
				{	
					
					$tariff = $row["tariff"];
					$price = $row["price"];
					$km_route = $row["km_route"];
					$timeradius50km = $row["timeradius50km"];
					$timeradius50km_minute = $row["timeradius50km_minute"];
					$lagtime50km = $row["lagtime50km"];
					$lagtime50km_minute = $row["lagtime50km_minute"];
					
					
					
					if ($tariff == "T1") {
						$distance_sum_gruz += $km_route;
						$work_time_sum_gruz += $lagtime50km + round($lagtime50km_minute / 60, 2) + $timeradius50km + round($timeradius50km_minute / 60, 2);
					}
					elseif ($tariff == "T2") {
						$distance_sum_post += $km_route;
						$work_time_sum_post += $lagtime50km + round($lagtime50km_minute / 60, 2) + $timeradius50km + round($timeradius50km_minute / 60, 2);
					}
					elseif ($tariff == "T3") {
						$distance_sum_taxi += $km_route;
						$work_time_sum_taxi += $timeradius50km + round($timeradius50km_minute / 60, 2);
						$lag_time_sum_taxi += $lagtime50km + round($lagtime50km_minute / 60, 2);
					}
					
				}
				
				$i += 2;
				
				foreach($tableArray as $key=>$value) {
				$aSheet->setCellValue("A$i",$value);
				$aSheet->mergeCells("A$i".":J$i");
				$i += 1;
				$aSheet->setCellValue("A$i",'Наименование подразделения');
				$aSheet->mergeCells("A$i".":G$i");
				$aSheet->setCellValue("H$i",'Количество отработанных часов/ км');
				$aSheet->setCellValue("I$i",'Цена, руб/час/км (НДС не облагается)');
				$aSheet->setCellValue("J$i",'Общая стоимость, руб (НДС не облагается)');	
				$pExcel->getActiveSheet()->setSharedStyle($sharedHeaderTableStyleAct, "A$i:J$i");	
				$aSheet->getRowDimension($i)->setRowHeight(48);	

				$aSheet->getColumnDimension('H')->setWidth(11);
				$aSheet->getColumnDimension('I')->setWidth(11);		
				$aSheet->getColumnDimension('J')->setWidth(15);	
				
				$work_time = $distance = "";
				
				if ($key == 'gruz'){
					$work_time = $work_time_sum_gruz;
					$distance = $distance_sum_gruz;
				}
				elseif ($key == 'post'){
					$work_time = $work_time_sum_post;
					$distance = $distance_sum_post;
				}
				elseif ($key == 'taxi'){
					$work_time = $work_time_sum_taxi;
					$distance = $distance_sum_taxi;
				}
				elseif ($key == 'wait'){
					$work_time = $lag_time_sum_taxi;
					$distance = "";
				}
				
				$i += 1;
				$aSheet->setCellValue("A$i",$instance_name);
				$aSheet->mergeCells("A$i".":G$i");
				// $aSheet->setCellValue("H$i",round($work_time,3));
				$aSheet->setCellValue("H$i",$work_time);
				$aSheet->setCellValue("I$i",$priceArrayVr[$key]);
				$aSheet->setCellValue("J$i","=H$i*I$i");
				// $priceArrayVr[$key]
				$i += 1;
				$aSheet->setCellValue("A$i",$instance_name);
				$aSheet->mergeCells("A$i".":G$i");
				$aSheet->setCellValue("H$i",$distance);
				// $aSheet->setCellValue("H$i",round($distance,3));
				$aSheet->setCellValue("I$i",$priceArrayKm[$key]);
				$aSheet->setCellValue("J$i","=H$i*I$i");
				
				$i += 1;
				$aSheet->setCellValue("H$i","Всего");
				$aSheet->mergeCells("A$i".":G$i");
				$aSheet->mergeCells("H$i".":I$i");
				$aSheet->setCellValue("J$i","=J".($i-1)."+J".($i-2));
					
				$pExcel->getActiveSheet()->setSharedStyle($sharedBodyTableStyleAct, "A".($i-2).":J$i");
				$i += 1;
				}
			
			
			}
			elseif ($report == "rep_04") {
				$rez = pg_query($link, "select $tableUser.login, $tableOrder.sd_number, $tableOrder.id, $tableOrder.sb_user, $tableOrder.departure_date, $tableOrder.sb_srok, $tableOrder.price, min($tableLog.datetime) as done_time from $tableLog inner join $tableUser on $tableLog.user_id = $tableUser.id inner join $tableOrder on $tableLog.order_id = $tableOrder.id where $tableLog.event like '%статус => \"Решено\"%' and $tableLog.datetime between $report_start_date and $report_end_date group by $tableOrder.sd_number, $tableUser.login, $tableOrder.id, $tableOrder.sb_user, $tableOrder.departure_date, $tableOrder.sb_srok, $tableOrder.price");
				// group by $tableUser.login, $tableOrder.sd_number
				
				$pExcel->setActiveSheetIndex(0);
				$aSheet = $pExcel->getActiveSheet();
				$aSheet->setTitle($configReports[$report]);
				$i = 1;
				$aSheet->getColumnDimension('A')->setWidth(15);
				$aSheet->getColumnDimension('B')->setWidth(30);
				$aSheet->getColumnDimension('C')->setWidth(30);
				$aSheet->getColumnDimension('D')->setWidth(30);
				$aSheet->getColumnDimension('E')->setWidth(30);
				$aSheet->getColumnDimension('F')->setWidth(20);
				$aSheet->getColumnDimension('G')->setWidth(20);
				
				$aSheet->setCellValue("A$i","Номер");
				$aSheet->setCellValue("B$i","Исполнитель в ПО ДРУГА");
				$aSheet->setCellValue("C$i","Кто выполнил заявку");
				$aSheet->setCellValue("D$i","Время выполнения");
				$aSheet->setCellValue("E$i","Дата выезда");
				$aSheet->setCellValue("F$i","Срок в ПО ДРУГА");
				$aSheet->setCellValue("G$i","Цена");
				
				$i += 1;
				while ($row = pg_fetch_array($rez))
				{
					$done_time = date("d.m.Y H:i", $row["done_time"]);
					$login = $row["login"];
					$order = ($row["sd_number"] ? $row["sd_number"] : "ВН".$row["id"]);
					$price = $row["price"];
					$sb_user = $row["sb_user"];
					$sb_user = preg_replace('/Перевозчик/', 'ПеревозчикЪ', $sb_user);
					$sb_srok = date("d.m.Y H:i", $row["sb_srok"]);
					$departure_date = date("d.m.Y H:i", $row["departure_date"]);
					
					$aSheet->setCellValue("A$i",$order);
					$aSheet->setCellValue("B$i",$sb_user);
					$aSheet->setCellValue("C$i",$login);
					$aSheet->setCellValue("D$i",$done_time);
					$aSheet->setCellValue("E$i",$departure_date);
					$aSheet->setCellValue("F$i",$sb_srok);
					$aSheet->setCellValue("G$i",$price);
					
					$i += 1;
				}
					
		}
			
			$i += 2;
			$aSheet->setCellValue("A$i","Общая стоимость услуг составляет:");
			$aSheet->mergeCells("G$i".":I$i");
			$pExcel->getActiveSheet()->setSharedStyle($sharedUnderlineCellStyle, "G$i:I$i");
			$aSheet->setCellValue("G$i","=J12+J17+J22+J27+J32+J37");
			$aSheet->setCellValue("J$i","руб., НДС не");
			
			$i += 1;
			$aSheet->setCellValue("A$i","облагается.");
			
			$i += 1;
			$aSheet->setCellValue("A$i","Стороны взаимных претензий не имеют.");
			
			$i += 3;
			$aSheet->setCellValue("B$i","От Заказчика:");
			$aSheet->setCellValue("I$i","От Иполнителя:");
			$pExcel->getActiveSheet()->setSharedStyle($sharedJustBoldStyle, "A$i:I$i");
			
			
			$i += 3;
			$pExcel->getActiveSheet()->setSharedStyle($sharedUnderlineCellStyle, "A$i:D$i");
			$pExcel->getActiveSheet()->setSharedStyle($sharedUnderlineCellStyle, "H$i:J$i");
			
			$i += 1;
			$aSheet->setCellValue("A$i","Подпись ФИО");
			$aSheet->setCellValue("H$i","Подпись ФИО");
			$aSheet->mergeCells("A$i".":D$i");
			$aSheet->mergeCells("H$i".":J$i");
			$pExcel->getActiveSheet()->setSharedStyle($sharedJustCenteredStyle, "A$i:J$i");
			
			$i += 1;
			$aSheet->setCellValue("A$i","м.п");
			$aSheet->setCellValue("H$i","м.п");
			
			
			//exit;
			$objWriter = new PHPExcel_Writer_Excel5($pExcel);
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="report.xls"');
			header('Cache-Control: max-age=0');
			$objWriter->save('php://output');
		}
				
		
		/*
		//устанавливаем данные
		//номера по порядку
		$aSheet->setCellValue('A1','№');
		$aSheet->setCellValue('A2','1');
		$aSheet->setCellValue('A3','2');
		$aSheet->setCellValue('A4','3');
		$aSheet->setCellValue('A5','4');
		//названия сайтов
		$aSheet->setCellValue('B1','Названия');
		$aSheet->setCellValue('B2','http://www.web-junior.net');
		$aSheet->setCellValue('B3','http://www.google.com');
		$aSheet->setCellValue('B4','http://www.yandex.ru');
		$aSheet->setCellValue('B5','http://www.twitter.com');
		//мой личный рейтинг
		$aSheet->setCellValue('C1','Рейтинг');
		$aSheet->setCellValue('C2','100');
		$aSheet->setCellValue('C3','99');
		$aSheet->setCellValue('C4','90');
		$aSheet->setCellValue('C5','85');
		//устанавливаем ширину
		$aSheet->getColumnDimension('B')->setWidth(25);
		//отдаем пользователю в браузер */
		
		
		

	}
	pg_close($link);	
?>