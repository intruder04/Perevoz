<?php
	
	if (isset($_POST["delDriverIDs"])&& isset($_POST["deleteConfirmation"]))
	{
		//$delrez = DeleteDrivers($_POST["delDriverIDs"]);
		//$tpl->assign("main_message", $delrez[1]);
	}
	
	$query = "select $tableDriver.*, $tableCar.model || ' '|| $tableCar.regnum as car, $tableLocation.name as location from $tableDriver left join $tableCar on $tableDriver.car_id = $tableCar.id left join $tableLocation on $tableDriver.location_id = $tableLocation.id ".(($sessionUserLocId || $searchStr) ? "where" : "").($searchStr ? " fullname ilike '%$searchStr%' or nick ilike '%$searchStr%'" : '').(($searchStr && $sessionUserLocId) ? " and" : "").($sessionUserLocId ? " $tableDriver.location_id = $sessionUserLocId" : "");
		
	//print $query;
	$pager = new Paging($link, "$tableDriver.fullname asc", 20);
	
	$pager->set_result_text_prefix("Водители");  
	$r = $pager->get_page( $query ); ;
	$tpl->assign('pager_info', $pager->get_result_text());
	$tpl->assign('pager_prev_link', $pager->get_prev_page_link());
	$tpl->assign('pager_next_link', $pager->get_next_page_link());
	$tpl->assign('pager_links', $pager->get_page_links());
	$driver = array(); 
	while($row = pg_fetch_array($r))
	{
		$row["status"] = $configDriverStatuses[$row["status"]];
		array_push($driver,  $row);
	}
	
	$tpl->assign("driver", $driver); 
	$tpl->assign("main_header", "Водители");	
?>