<?php
	if (isset($_POST["delLocationIDs"])&& isset($_POST["deleteConfirmation"]))
	{
		//$delrez = DeleteLocations($_POST["delLocationIDs"]);
		//$tpl->assign("main_message", $delrez[1]);
	}
	
	$query = "select * from $tableLocation".($searchStr? " and name ilike '%$searchStr%'" : '');
	
	
	$pager = new Paging($link, "name asc", 20);
	
	$pager->set_result_text_prefix("Населенные пункты");  
	$r = $pager->get_page( $query ); ;
	$tpl->assign('pager_info', $pager->get_result_text());
	$tpl->assign('pager_prev_link', $pager->get_prev_page_link());
	$tpl->assign('pager_next_link', $pager->get_next_page_link());
	$tpl->assign('pager_links', $pager->get_page_links());
	$location = array(); 
	while($row = pg_fetch_array($r))
	{
		array_push($location,  $row);
	}
	
	$tpl->assign("location", $location); 
	$tpl->assign("main_header", "Населенные пункты");	
?>