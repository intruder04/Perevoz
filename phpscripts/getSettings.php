<?php
        $rez = pg_query($link, "select * from $tableSettings order by param_name");
        while($row = pg_fetch_array($rez))
        {
            $paramName = $row['param_name'];
            $paramValue = $row['param_value'];
			${"settings" . $paramName} = $paramValue; 
			//echo "$paramName -> $paramValue".${"settings" . $paramName}."<br>";
		}
?>