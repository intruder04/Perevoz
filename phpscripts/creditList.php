<?php
	if (isset($_POST["delCreditIDs"])&& isset($_POST["deleteConfirmation"]))
	{
		//$delrez = DeleteCredits($_POST["delCreditIDs"]);
		//$tpl->assign("main_message", $delrez[1]);
	}
	
	$query = "select * from $tableCredit".($searchStr? " where (name ilike '%$searchStr%' or descr ilike '%$searchStr%')" : '');
	
	
	$pager = new Paging($link, "name asc", 20);
	
	$pager->set_result_text_prefix("Номера кредитов");  
	$r = $pager->get_page( $query ); ;
	$tpl->assign('pager_info', $pager->get_result_text());
	$tpl->assign('pager_prev_link', $pager->get_prev_page_link());
	$tpl->assign('pager_next_link', $pager->get_next_page_link());
	$tpl->assign('pager_links', $pager->get_page_links());
	$credit = array(); 
	while($row = pg_fetch_array($r))
	{
		array_push($credit,  $row);
	}
	
	$tpl->assign("credit", $credit); 
	$tpl->assign("main_header", "Номера кредитов");	
?>