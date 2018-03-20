<?php
	if (isset($_POST["editButton"]))
	{
		while(list($param_name, $param_value) = each($_POST))
		{
			$param_name= htmlspecialchars(trim($param_name));
			$param_value= htmlspecialchars(trim($param_value));
			pg_query($link, "update $tableSettings set param_value = '$param_value' where param_name='$param_name' and writable='1'");
		}
		$tpl->assign("main_message", "Изменения сохранены");
	}
	$rez = pg_query($link, "select * from $tableSettings order by param_name");
	$params = array();
	while($row = pg_fetch_array($rez))
	{
		$valueMappingsStr = $row["value_mappings"];
        if ($valueMappingsStr && eregi(":", $valueMappingsStr))
		{
			$valueMappings = array();
			$valuePairs = split(";", $valueMappingsStr);
			foreach($valuePairs  as $pairs)
			{
				list($key, $value) = split(":", $pairs);
				$valueMappings[$key] = $value;
			}
            $row['map'] = $valueMappings;
        }
        
        array_push($params, $row);
	}
	$tpl->assign("params", $params);
	$tpl->assign("main_header", "Настройки");
?>