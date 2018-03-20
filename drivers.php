<?php
	ini_set("max_execution_time", "600");
	date_default_timezone_set('Europe/Moscow');
	require 'config.php';
	require 'libs/Smarty.class.php';
	require 'libs/paging.pg.class.php';
	require 'libs/functions.php';
    
	$link = pg_connect("host=$dbHost port=$dbPort dbname=$dbName user=$dbUser password=$dbPwd");
	$tpl = new Smarty;
	
	
	
	$query = "select $tableDriver.nick, $tableDriver.fullname, count($tableOrder.id) as orders, $tableCar.model, $tableCar.regnum from $tableDriver left join $tableOrder on ($tableDriver.id = $tableOrder.driver_id and $tableOrder.status = 4) left join $tableCar on $tableDriver.car_id = $tableCar.id  where $tableDriver.status = 'active'".($sessionUserLocId ? " and $tableDriver.location_id = $sessionUserLocId" : "")." group by $tableDriver.nick, $tableDriver.fullname, $tableCar.model, $tableCar.regnum";
	
	
	$pager = new Paging($link, "nick asc", 200);
	
	$pager->set_result_text_prefix("Водители");  
	$r = $pager->get_page( $query ); ;
	$tpl->assign('pager_info', $pager->get_result_text());
	$tpl->assign('pager_prev_link', $pager->get_prev_page_link());
	$tpl->assign('pager_next_link', $pager->get_next_page_link());
	$tpl->assign('pager_links', $pager->get_page_links());
	$driver = array(); 
	while($row = pg_fetch_array($r))
	{
		if ($row["orders"] > 0) $row["driverclass"] = "style=\"background-color: red\"";
		array_push($driver,  $row);
	}
	
	$tpl->assign("driver", $driver); 
	
	$tpl->display("drivers.tpl");
	pg_close($link);
?>