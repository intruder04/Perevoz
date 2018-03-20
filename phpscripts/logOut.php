<?php
	session_unset();
	session_destroy();
	$tpl->assign("cleanURL", "clean");
	$tpl->display("login.tpl");
	pg_close($link);
	exit;
?>