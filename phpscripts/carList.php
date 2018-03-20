<?php
	
	if (isset($_POST["delCarIDs"])&& isset($_POST["deleteConfirmation"]))
	{
		$delrez = DeleteCars($_POST["delCarIDs"]);
		$tpl->assign("main_message", $delrez[1]);
	}
	
	$query = "select $tableCar.*, $tableLocation.name as loc_name from $tableCar left join $tableLocation on $tableCar.location_id = $tableLocation.id ".(($sessionUserLocId || $searchStr) ? "where" : "").($searchStr? " regnum like '%$searchStr%' or model ilike '%$searchStr%' or color ilike '%$searchStr%'" : '').(($searchStr && $sessionUserLocId) ? " and" : "").($sessionUserLocId ? " $tableCar.location_id = $sessionUserLocId" : "");
	//print $query;
	
	$pager = new Paging($link, "regnum asc", 20);
	
	$pager->set_result_text_prefix("Автомобили");  
	$r = $pager->get_page( $query ); ;
	$tpl->assign('pager_info', $pager->get_result_text());
	$tpl->assign('pager_prev_link', $pager->get_prev_page_link());
	$tpl->assign('pager_next_link', $pager->get_next_page_link());
	$tpl->assign('pager_links', $pager->get_page_links());
	$car = array(); 
	while($row = pg_fetch_array($r))
	{
		array_push($car,  $row);
	}
	
	$tpl->assign("car", $car);
	$tpl->assign("main_header", "Автомобили");	
?>