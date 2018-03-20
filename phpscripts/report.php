<?php
	
	
	
	$rez = pg_query($link, "select id, login from $tableUser where role = 'dispatcher'");
	$users = array();
	$user_options = '';
	while ( $row = pg_fetch_array($rez))
	{
		$id = $row["id"];
		$login = $row["login"];
		$users[$id] = "$login";
	}
	$tpl->assign('users', $users);	
	
	$rez = pg_query($link, "select nick, fullname, id from $tableDriver order by nick");
	$drivers = array();
	while ( $row = pg_fetch_array($rez))
	{
		$id = $row["id"];
		$nick = $row["nick"];
		$fullname = $row["fullname"];
		$drivers[$id] = "$nick($fullname)";
	}
	$tpl->assign('drivers', $drivers);
	
	$rez = pg_query($link, "select * from $tableCredit order by name");
	$credits = array();
	while ( $row = pg_fetch_array($rez))
	{
		$id = $row["id"];
		$name = $row["name"];
		$descr = $row["descr"];
		$credits[$id] = "$name $descr";
	}
	$tpl->assign('credits', $credits);
	
	$tpl->assign("reports", $configReports);
	$tpl->assign("statuses", $configOrderStatuses);
	$tpl->assign("types", $configOrderTypes);
	$tpl->assign("main_header", "Отчеты");
?>