<?php

if (isset($_POST["saveButton"])){
	function get_numerics ($str) {
    preg_match_all('/\d*(\d{5})/', $str, $matches);
    return $matches[0];
}
function startsWith($haystack, $needle) {
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}
$values = "(";
// $flag_val = '0';
foreach ($_POST as $key => $value) {
$or_number_val = '';
$or_number = get_numerics($key);

if (isset($or_number[0])){
  $or_number_val = $or_number[0];
}

if (startsWith($key,"tariff") or startsWith($key,"time") or startsWith($key,"price") or startsWith($key,"km_") or startsWith($key,"lag")) {
$values .= isset($value) ? "'"."$value"."'," : "";

}
if (startsWith($key,"order")) {
$values .= isset($value) ? ("$value")."),(" : "";

}

}
$values = substr($values, 0, -2);
#echo $values;

$sqlTempl_1 = "update $tableOrder as dub set tariff = dub2.tariff, timeradius50km = dub2.timeradius50km, timeradius50km_minute = dub2.timeradius50km_minute, km_route = dub2.km_route, lagtime50km = dub2.lagtime50km, lagtime50km_minute = dub2.lagtime50km_minute, price = dub2.price from (values";
$sqlTempl_2 =") as dub2(tariff,timeradius50km,timeradius50km_minute,km_route,lagtime50km,lagtime50km_minute,price,id) where dub2.id = dub.id";

$sqlTranspUpdate = $sqlTempl_1.$values.$sqlTempl_2;
// echo $sqlTranspUpdate."<br>";
#echo "Field ".htmlspecialchars($key)." is ".htmlspecialchars($value)."<br>";

$rez = pg_query($link, $sqlTranspUpdate);

} 


	if (!isset($_SESSION["pageDashboardSize"])) $_SESSION["pageDashboardSize"] = $pageDashboardSizes[2];
	if  (isset($_POST["pageDashboardSize"]))
	{
		$pageDashboardSize = preg_replace("/[^0-9]/i",'',$_POST["pageDashboardSize"]);
		if ($pageDashboardSize) 
		{	
			$_SESSION['pageDashboardSize'] = $pageDashboardSize;
		}
	}
	
	if (!isset($_SESSION["filterStatus"])) $_SESSION["filterStatus"] = '';
	if  (isset($_POST["filterStatus"]))
	{
		
		$filterStatus = preg_replace("/[^0-9]/i",'',$_POST["filterStatus"]);
		if ($filterStatus && in_array($filterStatus, array_keys($configOrderStatuses))) 
		{	
			$_SESSION['filterStatus'] = $filterStatus;
		}
		else
		{
			$_SESSION['filterStatus'] = '';
		}
	}
	
	if (!isset($_SESSION["filterType"])) $_SESSION["filterType"] = '';
	if  (isset($_POST["filterType"]))
	{
		
		$filterType = preg_replace("/[^0-9]/i",'',$_POST["filterType"]);
		if ($filterType && in_array($filterType, array_keys($configOrderTypes))) 
		{	
			$_SESSION['filterType'] = $filterType;
		}
		else
		{
			$_SESSION['filterType'] = '';
		}
	}
	
	if (!isset($_SESSION["filter_date"])) {
	$_SESSION["filter_date"] = '';
	}
	else {
	$filterDate = $_SESSION["filter_date"];
	}
	if  (isset($_POST["filter_date"]))
	{
		
		$filterDate = $_POST["filter_date"];
		if ($filterDate) 
		{	
			$_SESSION['filter_date'] = $filterDate;
		}
		else
		{
			$_SESSION['filter_date'] = '';
		}
	}
	
	$tpl->assign("orderint", $sessionOrderInt);
	
	//сортировка
	if (!isset($_SESSION["dashboardSortBy"])) $_SESSION["dashboardSortBy"]= "$tableOrder.departure_date";
	if (!isset($_SESSION["dashboardSortDirection"])) $_SESSION["dashboardSortDirection"]= "desc";
	if (!isset($_SESSION["dashboardSortByLink"])) $_SESSION["dashboardSortByLink"] = 'departure_date';
	
	//echo "$dashboardSortby $dashboardSortDirection";
	if (isset($dashboardSortBy) && isset($dashboardSortDirection) && $dashboardSortBy && $dashboardSortDirection)
	{
		$_SESSION["dashboardSortByLink"] = $dashboardSortBy;
		if ($dashboardSortDirection == 'desc')
		{
			$_SESSION["dashboardSortDirection"] = 'desc';
			
		}
		else
		{
			$_SESSION["dashboardSortDirection"] = 'asc';
			
		}
		
		if ($dashboardSortBy == 'sd_number')
		{
			$_SESSION["dashboardSortBy"] = "$tableOrder.sd_number";
		}
		elseif ($dashboardSortBy == 'start_date')
		{
			$_SESSION["dashboardSortBy"] = "$tableOrder.start_date";
		}
		elseif ($dashboardSortBy == 'departure_date')
		{
			$_SESSION["dashboardSortBy"] = "$tableOrder.departure_date";
		}
		elseif ($dashboardSortBy == 'regnum')
		{
			$_SESSION["dashboardSortBy"] = "$tableCar.regnum";
		}
		else
		{
			$_SESSION["dashboardSortBy"]= "$tableOrder.status";
		}
	}
	if ($_SESSION["dashboardSortDirection"] == 'desc')
	{
		$tpl->assign("dashboardSortDirection", 'asc');
		$tpl->assign("sortImage", '<img style="vertical-align: middle" src="/images/sort-up-icon.png" />');
	}
	else
	{
		$tpl->assign("dashboardSortDirection", 'desc');
		$tpl->assign("sortImage", '<img style="vertical-align: middle" src="/images/sort-down-icon.png" />');
	}
	$tpl->assign('dashboardSortBy', $_SESSION["dashboardSortByLink"]);
	
	$sessionSQL = ($sessionUserRole == 'dispatcher' ? " and ($tableOrder.status = 1 or $tableOrder.user_id = $sessionUserID)" : '');
	$sessionFilterStatus = $_SESSION["filterStatus"];
	// для фильтрации по пользователю:
	if ($sessionSbSeeAll != 1) {
	$sessionUserSQL = " and ($tableOrder.sb_user = '$sessionSbUser')";
	}
	$now = time();
	//$query = "select $tableOrder.id, $tableOrder.status, $tableOrder.sd_number, $tableOrder.start_date, $tableOrder.end_date, $tableOrder.update_date, $tableOrder.departure_date, $tableDriver.nick || '(' || $tableDriver.fullname || ')' as driver, $tableCar.model || '(' || $tableCar.regnum || ')' as car, $tableUser.login as user, $tableOrder.address_to, $tableOrder.address_from, $tableOrder.ret_address_to, $tableOrder.ret_address_from, $tableOrder.dep_date, $tableOrder.dep_time, $tableOrder.ret_dep_date, $tableOrder.ret_dep_time, $tableOrder.direction, $tableOrder.vip  from $tableOrder left join $tableDriver on $tableOrder.driver_id = $tableDriver.id left join $tableCar on $tableOrder.car_id = $tableCar.id left join $tableUser on $tableOrder.user_id = $tableUser.id where ".(isset($arch) && $arch ? "(($now - start_date) > $configOrderShowInterval and $tableOrder.status in (5,6) )" : "(($now - start_date) < $configOrderShowInterval or $tableOrder.status not in (5,6) )")." $sessionSQL".($sessionFilterStatus ? " and $tableOrder.status = $sessionFilterStatus" : '').($searchStr? " and ( cast($tableOrder.sd_number as text) ilike '%$searchStr%' or $tableOrder.address_from ilike '%$searchStr%' or $tableOrder.address_to ilike '%$searchStr%')" : '');
	//if ($searchStr) $arch = '';
	$filterSQL = (isset($arch) && $arch ? "($now > $configOrderShowInterval)" : "(($now - start_date) < $configOrderShowInterval or $tableOrder.status not in (5,6) )");
	if ($searchStr) 
	{
		$filterSQL = "( cast($tableOrder.sd_number as text) ilike '%$searchStr%' or $tableOrder.address_from ilike '%$searchStr%' or $tableOrder.address_to ilike '%$searchStr%' or $tableCar.regnum ilike '%$searchStr%' or $tableDriver.nick ilike '%$searchStr%' or $tableDriver.fullname ilike '%$searchStr%' or cast($tableOrder.id as text ) ilike '%$searchStr%')";
		$arch ='';
	}
	
	$filterSQL .= $sessionSQL;
	$filterSQL .= isset($sessionUserSQL) ? $sessionUserSQL : '';
	
	$filterSQL .= $_SESSION["filterType"] == 1 ? " and $tableOrder.sd_number is not null" : ($_SESSION["filterType"] == 2 ? " and $tableOrder.sd_number is null" : ''); 
	$query = "select $tableOrder.id, $tableOrder.customer, $tableOrder.status, $tableOrder.sd_number, $tableOrder.start_date, $tableOrder.end_date, $tableOrder.update_date, $tableOrder.departure_date, $tableDriver.nick || '(' || $tableDriver.fullname || ')' as driver, $tableCar.model || '(' || $tableCar.regnum || ')' as car, $tableUser.login as user, $tableOrder.address_to, $tableOrder.address_from, $tableOrder.ret_address_to, $tableOrder.ret_address_from, $tableOrder.dep_date, $tableOrder.dep_time, $tableOrder.ret_dep_date, $tableOrder.ret_dep_time, $tableOrder.direction, $tableOrder.vip, $tableOrder.tariff, $tableOrder.price, $tableOrder.km_route, $tableOrder.lagtime50km, $tableOrder.timeradius50km, $tableOrder.timeradius50km_minute, $tableOrder.lagtime50km_minute, $tableCar.regnum from $tableOrder left join $tableDriver on $tableOrder.driver_id = $tableDriver.id left join $tableCar on $tableOrder.car_id = $tableCar.id left join $tableUser on $tableOrder.user_id = $tableUser.id where $filterSQL".($sessionFilterStatus ? " and $tableOrder.status = $sessionFilterStatus" : '').($filterDate ? " and (substring(dep_date from 1 for 10) = '".$filterDate."' or to_timestamp($tableOrder.departure_date)::date = '".$filterDate."')" : "");
	// print $query;
	
	$pager = new Paging($link, $_SESSION["dashboardSortBy"]." ". $_SESSION["dashboardSortDirection"], $_SESSION['pageDashboardSize'], $_SESSION["filter_date"]);
	$pager->set_result_text_prefix("Заявки");  
	$r = $pager->get_page( $query ); ;
	$tpl->assign('pager_info', $pager->get_result_text());
	$tpl->assign('pager_prev_link', $pager->get_prev_page_link());
	$tpl->assign('pager_next_link', $pager->get_next_page_link());
	$tpl->assign('pager_links', $pager->get_page_links());
	$order = array(); 
	while($row = pg_fetch_array($r))
	{
		if ($row["status"] == 1)
		{
			$row["order_row_class"] = "order_row_new";
		}
		elseif ($row["status"] == 2)
		{
			$row["order_row_class"] = "order_row_work";
		}
		elseif ($row["status"] == 3)
		{
			$row["order_row_class"] = "order_row_approve";
		}
		elseif ($row["status"] == 4)
		{
			$row["order_row_class"] = "order_row_wait";
		}
		else
		{
			$row["order_row_class"] = "order_row_closed";
		}
		$row["regnum"] = ($row["regnum"] ? $row["regnum"] : "Не назначен");	
		$row["start_date"] = date("d.m.Y H:i", $row["start_date"]);
		//if ($row["start_date"]) $row["start_date"] = date("d.m.Y H:i:s", $row["start_date"]);
		if ($row["end_date"]) $row["end_date"] = date("d.m.Y H:i", $row["end_date"]);
		if ($row["departure_date"]) $row["departure_date"] = date("d.m.Y H:i", $row["departure_date"]);
		$row["status_str"] = $configOrderStatuses[$row["status"]];
		$customerNameArray = preg_split("/\s+/", trim($row["customer"]));
		$row["customer"] = $customerNameArray[0];
		$row["sd_number"] = ($row["sd_number"] ? $row["sd_number"] : "ВН".$row["id"]);
		array_push($order,  $row);
	}
	
	$tpl->assign("order", $order); 
    $tpl->assign("selectedDashboardPageSize", $_SESSION['pageDashboardSize']);
	$tpl->assign("filterStatuses", $configOrderStatuses);
	$tpl->assign("selectedFilterStatus", $_SESSION["filterStatus"]);
	$tpl->assign("filterTypes", $configOrderTypes);
	$tpl->assign("selectedFilterType", $_SESSION["filterType"]);
	$tpl->assign("selectedFilterDate", $_SESSION["filter_date"]);
	$tpl->assign("pageDashboardSizes", $pageDashboardSizes);
	if (isset($arch) && $arch > 0)
	{
		$tpl->assign("arch", "&arch=1");
		$tpl->assign("main_header", "Заявки");
	}
	else
	{
		$tpl->assign("main_header", "Заявки");
	}
	$page = "dashboardTranspData";
	
	
	$hours = array_map("AddLeadingZeroes", range(0,23));
		$minutes = array_map("AddLeadingZeroes", range(0,59));
		$end_minutes = array_map("AddLeadingZeroes", array(0,15,30,45));
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
	
	
?>