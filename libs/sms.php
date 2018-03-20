<?php
// mayorov 2017
	function SendSMS($number,$text)
	{	
		// remove all non numeric symbols:
		$number = preg_replace("/[^0-9,.]/", "", $number);
		if (strlen($number) > 12) {
			return "error! number is too big";
		}
		
		
		$body=file_get_contents("https://sms.ru/sms/send?api_id=&to=" . $number . "&text=". urlencode($text).'&partner_id=195526');
		return "ok";
		
		// in case you dont use utf:
		// .urlencode(iconv("windows-1251","utf-8",$text)));
	}
	
	function DisplayMsg($msg,$color)
	{
		return "<font color=\"$color\"><b>SMS: </b> $msg</font>";
	}
?>