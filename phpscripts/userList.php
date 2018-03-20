<?php
	
	if (isset($_POST["delUserIDs"])&& isset($_POST["deleteConfirmation"]))
	{
		//$delrez = DeleteUsers($_POST["delUserIDs"]);
		//$tpl->assign("main_message", $delrez[1]);
	}
	
	$query = "select login, email, id, role, orderint, sb_user, sb_seeall from $tableUser".($searchStr? " where login ilike '%$searchStr%' or email ilike '%$searchStr%'" : '');
	
	
	$pager = new Paging($link, "login asc", 20);
	
	$pager->set_result_text_prefix("Пользователи");  
	$r = $pager->get_page( $query ); ;
	$tpl->assign('pager_info', $pager->get_result_text());
	$tpl->assign('pager_prev_link', $pager->get_prev_page_link());
	$tpl->assign('pager_next_link', $pager->get_next_page_link());
	$tpl->assign('pager_links', $pager->get_page_links());
	$user = array(); 
	while($row = pg_fetch_array($r))
	{
		$row["role"] = $userRoles[$row["role"]];
		array_push($user,  $row);
	}
	
	$tpl->assign("user", $user); 
	$tpl->assign("main_header", "Пользователи");	
?>