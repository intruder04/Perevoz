<?php

    error_reporting(E_ALL);
	date_default_timezone_set("Europe/Moscow");
	$dbHost = 'localhost';
	$dbUser = '';
	$dbPwd = '';

	$dbName = 'perevoz_krasnodar';
	$dbPort	 = 5432;


	$userRoles	= array('dispatcher' => "диспетчер", 'administrator' => "администратор", 'viewer' => "просмотр");

	$tablePrefix 			= "perevoz_";

	$tableUser 				= $tablePrefix."user";
	$tableCar				= $tablePrefix."car";
	$tableDriver			= $tablePrefix."driver";
	$tableLocation			= $tablePrefix."location";
	$tableSettings 			= $tablePrefix."settings";
	$tableOrder 			= $tablePrefix."order";
	$tableCredit 			= $tablePrefix."credit";
	$tableLog	 			= $tablePrefix."log";

	$pageDashboardSizes = array(10, 25, 50);



	$configDriverStatuses = array("active" => "Работает", "fired" => "Уволен");


	#$configDashboardSortBy =  array("Device ID" => "$tableDevice.device_name", "Device Group" => "$tableDevice.group_id");

	$configScriptsDir = "phpscripts";
	$configPagesAccess = array(
		"dashboard" 		 	=> array("all"),
		"dashboardTranspData" 	=> array("administrator", "dispatcher"),
		"dashboardDetail"  	 	=> array("administrator", "dispatcher"),
		"logOut"  	 	        => array("all"),
		"userList" 			 	=> array("administrator"),
		"userEdit"  			=> array("administrator"),
		"carList" 			 	=> array("administrator"),
		"carEdit"  			    => array("administrator"),
		"carList"  	 	        => array("administrator"),
		"carEdit"  	 	        => array("administrator"),
		"locationList"  	 	=> array("administrator"),
		"locationEdit"  	 	=> array("administrator"),
		"driverList"  	 	    => array("administrator"),
		"driverEdit"  	 	    => array("administrator"),
		"userPassword"  	 	=> array("all"),
		"settings"			    => array("administrator"),
		"creditList"  	 		=> array("administrator"),
		"tariffs"  	 			=> array("administrator"),
		"creditEdit"  	 		=> array("administrator"),
		"report"				=> array("all")
	);

	$configMenuIconImages = "images/menu";
	$configMainMenuTranslation = array ("Users" => "Пользователи", "Refs" => "Справочники");
	$configMainMenu = array(
			"Users" => array(
				"userPassword" => "Изменить пароль", "userEdit" => "Новая учетная запись", "userList" => "Управление учетными записями"
			)
			,
			"Refs" => array(
				"carEdit" => "Добавить автомобиль", "carList" => "Перечень автомобилей", "driverEdit" => "Добавить водителя", "driverList" => "Список водителей", "locationEdit" => "Добавить населенный пункт", "locationList" => "Перечень населенных пунктов", "creditEdit" => "Добавить номер кредита", "creditList" => "Номера кредитов", "settings" => "Настройки"
			)
	);

   $configOrderShowInterval = 172800;

   $configCopyrightText = 'ver. 1.3';
   $configSystemName = 'Perevozchik';

   $configSystemLogoLeft = "<a href='#' target='_blank'><img style=\"width:150px\" src='images/logo10.png'</a>";
   $configSystemLogoRight = "";
   $pageDashboardSizes = array(10, 20, 30, 50);
   //$configOrderStatuses = array("1" => "Новый", "2" => "В работе", "3" => "Ожидает", "4" => "Назначен", "5" => "Выполнен", "6" => "Отменен");
   //$configOrderStatuses = array("1" => "Новый", "5" => "Решено", "7" => "Не решено", "8" => "Отказ", "6" => "Отозвано банком");
   
   #для общего списка
   $configOrderStatuses = array("1" => "Новый", "5" => "Решено", "7" => "Решено", "9" => "Отказ", "8" => "Отозвано банком");
   #для заявки
   $configOrderStatuses_new = array("1" => "Новый", "7" => "Решено", "9" => "Отказ", "8" => "Отозвано банком");
   
   
   // $configOrderStatuses_old = array("1" => "Новый", "2" => "В работе", "3" => "Ожидает", "4" => "Назначен", "5" => "Решено", "6" => "Отклонено", "7" => "Не решено", "8" => "Отказ");


   //$configClassNameToStatus = array("NEW" => 1, "IN_PROGRESS" => 2, "DONE" => 5);
   $configOrderTypes = array("1" => "Сбербанк", "2" => "Внутренний");
   $configLogEventTypes = array("error", "info");
   
   $configMailUserName = "@mail.ru";
   $configMailUserPassword = "";
   $configMailServer = "imap.mail.ru";
   
   $configSMTPUserName = "@mail.ru";
   $configSMTPUserPassword = "";
   
   $configSMTPUserName = "@mail.ru";
   $configSMTPUserPassword = "";
   
   $configSMTPServer = "smtp.mail.ru";
   $configSMTPPort = 465;
   $configSMTPFromName = "Perevozchik Ticket System Krasnodar";
   $configMailTo = "@sberbank.ru";
   // $configMailTo = "may.viktor@gmail.com";
   $configIDPrefix = "perev_krasn_";
   
   $configAttachName = "from_perevozchik_eso_krasnodar.xml";
   $configMailSubject = "from_perevozchik_krasnodar";
   
   $configXmlInputDirectory = 'C:\xampp\xmls\krasnodar\input';
   $configXmlOutputDirectory = 'C:\xampp\xmls\krasnodar\output';
   
   $configReports = array("rep_01" => "Отчет по запросам общий", "rep_02" => "Отчет по диспетчерам", "rep_03" => "Выставить акт", "rep_04" => "SLA");
?>