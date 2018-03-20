<?php
	
	if (isset($_POST["delCarIDs"])&& isset($_POST["deleteConfirmation"]))
	{
		//$delrez = DeleteCars($_POST["delCarIDs"]);
		//$tpl->assign("main_message", $delrez[1]);
	}
	
	$query = "select * from $tableCar".($searchStr? " where regnum like '%$searchStr%' or model ilike '%$searchStr%' or color ilike '%$searchStr%'" : '');
	
	
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