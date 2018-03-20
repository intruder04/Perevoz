<?php
	ini_set("max_execution_time", "600");
	session_start();
	date_default_timezone_set('Europe/Moscow');
	
	//phpinfo();
	require 'config.php';
	require 'libs/Smarty.class.php';
	require 'libs/paging.pg.class.php';
	require 'libs/functions.php';
	require 'libs/sms.php';
    
	$link = pg_connect("host=$dbHost port=$dbPort dbname=$dbName user=$dbUser password=$dbPwd");
	include 'phpscripts/getSettings.php';
	$tpl = new Smarty;
		
	$tpl->assign("CopyrightText", $configCopyrightText);
	$tpl->assign("SystemName", $configSystemName);
	$tpl->assign("SystemLogoLeft", $configSystemLogoLeft);
	$tpl->assign("SystemLogoRight", $configSystemLogoRight);
	//variables resolving
	$requestVars = array(	"_POST" 
		=> array( 
			"number" => array("userGroupID", "max_users", "location_id", "car_id", "user_id", "credit_id", "driver_id", "credit", "start_date_Year", "start_date_Day", "start_date_Hour", "start_date_Minute", "end_date_Year", "end_date_Day", "end_date_Hour", "end_date_Minute", "start_date_Month","end_date_Month"),
			"string" => array("page", "userLogin", "userPassword", "login", "password", "role", "email", "oldPassword", "newPassword", "retypeNewPassword", "regnum", "model", "fullname", "status", "nick", "color", "phone", "name", "customer", "customer_phone", "address_from" , "address_to", "ret_address_from", "ret_address_to", "status", "solution", "phone", "price", "km_route", "timeradius50km", "lagtime50km", "lagtime50km_minute", "timeradius50km_minute", "tariff","sb_user","sb_srok"),
			"text"	 => array("descr", "note", "passenger"), 
			"binary" => array("")),
							"_GET"
		=> array(
			"number" => array("userID", "carID", "driverID", "locationID", "orderID", "creditID", "arch"),
			"string" => array("page", "view", "dashboardSortBy", "dashboardSortDirection"))
	);
	
	while(list($requestType, $tmpArray) = each($requestVars))
	{
		while(list($varType, $varArray) = each($tmpArray))
		{
			foreach($varArray as $requestVar)
			{
				if (isset(${$requestType}[$requestVar]))
				{
					$tmpRequestVar = ${$requestType}[$requestVar];
					if ($varType == "number")
					{
						$$requestVar = preg_replace("/[^0-9]/", '', $tmpRequestVar);
					}
					elseif ($varType == "binary")
					{
						$$requestVar = ($tmpRequestVar == ''? '': ( $tmpRequestVar == 1 ? 1 : 0));	
					}
					elseif ($varType == "text")
					{
						$$requestVar = preg_replace('/\'/','`' , htmlspecialchars(trim($tmpRequestVar)));	
					}
					else //string
					{
						$$requestVar = preg_replace('/\'/','`' , substr(htmlspecialchars(trim($tmpRequestVar)),0,3000));	
					}
				}
			}
		}
	}
	
	 if (!isset($page)) $page = 'dashboard';
	
	if (isset($_POST["sblogin"]))
	{
		if ($userLogin && $userPassword)
		{				
				$today = date("Y-m-d");
               	$rez = pg_query($link, "select $tableUser.id as user_id, $tableUser.role from $tableUser where login='$userLogin' and password='".md5($userPassword)."'");
				//echo mysql_error();
				if (pg_num_rows($rez) > 0)
				{
					$row = pg_fetch_array($rez, 0);
					$authRole = $row["role"];
					if ($authRole)
                    {
                       
                            $_SESSION['user_id']    = $row["user_id"];
                       
                    }
                    else
                    {
                        $page = "badPassword";
                        $tpl->assign("authFailed", "badPassword");
                    }
				}
				else
				{
					$page = "badPassword";
					$tpl->assign("authFailed", "badPassword");
				}
		}
		else
		{
			$page = "badPassword";
			$tpl->assign("authFailed", "badPassword");
		}
	}
	//$_SESSION['user_id'] = 'admin';
	if (isset($_SESSION['user_id']))
	{
		$rez = pg_query($link, "select $tableUser.id as user_id, $tableUser.login, $tableUser.role, $tableUser.orderint, $tableUser.sb_user, $tableUser.sb_seeall, $tableUser.location_id from $tableUser where $tableUser.id = ".$_SESSION['user_id']."");
		if (pg_num_rows($rez) > 0)
		{
			$row = pg_fetch_array($rez, 0);
            $sUserRole  = $row["role"];
            if (!$sUserRole)
            {
                session_unset();
                session_destroy();
                die("User does not exist. Exiting...");
            }
		}
        else
        {
            session_unset();
            session_destroy();
            die("User does not exist. Exiting...");
        }
        $sessionUserRole = $row['role'];
		$sessionUserID	 = $row['user_id'];
		$sessionLogin	 = $row['login'];
        $sessionOrderInt = $row['orderint'];
		$sessionSbUser = $row['sb_user'];
		$sessionSbSeeAll = $row['sb_seeall'];
		$sessionUserLocId = $row['location_id'];
        
		$tpl->assign('session_login', $sessionLogin);
		$tpl->assign('session_role', $sessionUserRole);
		
		
		
		if (isset($_POST["search_str"]))
		{
			$_GET["search_str"] = $_POST["search_str"];
			$queryArray = array();
			while (list($key, $value) = each($_GET))
			{
				if ($key != "search_str") array_push($queryArray, "$key=$value");
			}
			array_push($queryArray, "search_str=".$_POST["search_str"]);
			$_SERVER["QUERY_STRING"] = join("&", $queryArray);
			if (isset($_POST["fromAllDomains"]))
			{
				$_SESSION["fromAllDomains"] = 1;
			}
			else
			{
				$_SESSION["fromAllDomains"] = 0;
			}
		}
		$searchStr = (isset($_GET["search_str"])? substr(htmlspecialchars(trim($_GET["search_str"])),0,255) : "");
		if ($searchStr) $tpl->assign("search_str", $searchStr); 
		
		/* IDs check */
		if (isset($userID))
		{
			$rez = pg_query($link, "select $tableUser.id from $tableUser where $tableUser.id = '$userID'");
			if (pg_num_rows($rez) == 0) $userID = '';
		}
	
		if (isset($carID))
		{
			$rez = pg_query($link, "select id from $tableCar where id = $carID");
			if (pg_num_rows($rez) == 0) $carID = '';
		}
		
		if (isset($orderID) && $orderID)
		{
			$sessionSQL = ($sessionUserRole == 'dispatcher' ? " and ($tableOrder.status = 1 or $tableOrder.user_id = $sessionUserID)" : '');
			$rez = pg_query($link, "select id from $tableOrder where id = $orderID $sessionSQL");
			if (pg_num_rows($rez) == 0) ShowErrorAndExit($tpl, "Заявка не найдена".($sessionUserRole == 'dispatcher' ? " или обслуживается другим диспетчером" : ''));
		}
		
		//functions
		if (isset($page) && array_key_exists($page, $configPagesAccess) && (in_array($sessionUserRole, $configPagesAccess[$page]) || in_array("all", $configPagesAccess[$page])))
		{
			require($configScriptsDir."/".$page.".php");
		}
		else
		{
			require($configScriptsDir."/dashboard.php");
			$page = "dashboard";
		}
		
		$tpl->assign("page", $page);
		$tpl->assign("server_time", date("n/j/Y H:i T")." (".gmdate("n/j/Y H:i T").")");
		//help system
		/*$helpIndex = "default";
		if ($page)
		{
			$helpIndex = $page;
		}
		#$help_link = (isset($configHelpPages[$helpIndex]) ? $configHelpDir ."/". $configHelpPages[$helpIndex] :  $configHelpDir ."/". $configHelpPages["default"] );
		#$help_link = (file_exists($help_link) ? $help_link : $configHelpDir ."/". $configHelpPages["default"]);
		
        $help_link = (file_exists("$configHelpDir/$page.html") ? "$configHelpDir/$page.html" : "$configHelpDir/index.html");
        $tpl->assign("help_link", $help_link);*/
		
		/*Main menu*/
		$emptyMenuImage = (preg_match("/MSIE/", $_SERVER["HTTP_USER_AGENT"]) ? "EmptyMenuIE_16x16.png" : "EmptyMenu_16x16.png");
        $mainMenu = array();
		foreach(array_keys($configMainMenu) as $mainMenuItem)
		{
			$submenuExists = 0;
			$subMenuStr = "<ul>";
			while(list($subMenu, $label) = each($configMainMenu[$mainMenuItem]))
			{
					
					if (file_exists($configMenuIconImages."/".$subMenu."_16x16.png"))
					{
						$img = "<img src=\"$configMenuIconImages/".$subMenu."_16x16.png\" align=\"absmiddle\" border=\"0\">";
					}
					else
					{
                        $img = "<img src=\"$configMenuIconImages/$emptyMenuImage\" align=\"absmiddle\" border=\"0\">";
					}
                    
                    $subMenuStr .= "<li>";
					
                    if (in_array($sessionUserRole, $configPagesAccess[$subMenu]) || in_array("all", $configPagesAccess[$subMenu]))
					{
						
						$subMenuStr .= "<a href=\"$_SERVER[PHP_SELF]?page=$subMenu\">$img".$label."</a>";
						$submenuExists = 1;
					}
					else
					{
						#$subMenuStr .= "<span>$img&nbsp;".$label."</span>";
					}
					$subMenuStr .= "</li>";

			}
			$subMenuStr .= "</ul>";
			$subMenuLinks["name"] = $mainMenuItem;
			$subMenuLinks["translation"] = $configMainMenuTranslation["$mainMenuItem"];
			
			$subMenuLinks["submenu"] = $subMenuStr;
			if ($submenuExists) array_push($mainMenu, $subMenuLinks);
		}
        $tpl->assign('mainmenu', $mainMenu);
        
        
		
        if ($sessionUserRole == "administrator")
        {
            $sessionInfo = " - system administrator";
        }
		else
		{
			$sessionInfo = preg_replace("/_/",' ', " - $sessionUserRole");
		}
		$tpl->assign("session_info", $sessionInfo);
		# main icon
		if (($page == "dashboard") or ($page == "dashboardTranspData"))
		{
			$image = $configMenuIconImages."/Home_32x32.png";
		}
		if ($page == "report")
		{
			$image = $configMenuIconImages."/Home_32x32.png";
		}
		
		elseif ($page == "dashboardDetail")
		{
			$image = $configMenuIconImages."/Devices_32x32.png";
		}
		elseif ($page == "message")
		{
			$image = $configMenuIconImages."/information_32x32.png";
		}
		else
		{
			while(list($key, $menu) = each($configMainMenu))
			{
				if (array_key_exists($page, $menu))
				{
					if (file_exists($configMenuIconImages."/".$page."_32x32.png"))
					{
						$image = $configMenuIconImages."/".$page."_32x32.png";
					}
					elseif(file_exists($configMenuIconImages."/".$key."_32x32.png"))
					{
						$image = $configMenuIconImages."/".$key."_32x32.png";
					}
					else
					{
						$image = $configMenuIconImages."/Devices_32x32.png";
					}
					break;
				}
			}
		}
		
		$users_req = pg_query($link, "select distinct sb_user as sb_user from $tableUser where sb_user is not null and sb_user <> ''");	
		$locations = array();
		while ( $row = pg_fetch_array($users_req))
			{
			$locations[$row["sb_user"]] = $row["sb_user"];
			}
		$tpl->assign('locations_otchet', $locations);
		
		
		$tpl->assign("image", $image);
		#$tpl->assign("dashboardRefresh", $settingsDashboardRefresh);
		if ($page != "dashboardTranspData") 
		{
		$tpl->assign("dashboardRefresh", 300);
		}
		$tpl->display("index.tpl");
	}
	else
	{
		$tpl->display("login.tpl");
	}
	
	
	pg_close($link);
?>