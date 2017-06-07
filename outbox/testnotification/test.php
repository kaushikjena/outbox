<?php
	$message = "Welcome to boxware!!!!";
	//$api_key= "AIzaSyAwjmzfxLWUQFDGolp-hVeMKCZLpetIZsc";
	//$api_key= "AIzaSyDtGGSA0a-sq9SV3C5UULe_p4cpTNNCDvI";
	//$api_key= "AIzaSyBBymU8axr_jTKfG0bexnOCcpLEknQCX_M";
	$api_key="AIzaSyCtVnoyCW7HhIL2Vi6za7daafxJKDFZmMc";
	
	
		//$registration_key="APA91bGfv-s8SkeJcxShkpQH0eyIJudjaTK3k4gcDzLVYPRWdWuTMMsW2xr-Cu2VccaKUXWLxtUSwXXYc5utqQD3AG2J_9VPJPmdD4dcKwNGp-nfrlGgXAel6c2olh2gr0RhyJYeMJiq";
	
	//$registration_key="APA91bFtacxnUdle0EI-BJetPTCFtLMe7iFTcAgH3ejopBZ4QT2JhPgLd_I16l6dOQ0xwjjOzFy6GDf0Mnp_71Nba5IJ6QtGisdBZi5EdXCxjuGuWnssA_Xruy26jhIne_jDksC9oC_U";
	$registration_key="f7LxizHjkYo:APA91bF1Umv0ZqM0tmoAjoJQW_7BgrrGUNl-qsRiTXNydFU_4NjCinYB_W7nVGGUUaeoy_hG9a-Tfui9cCkfxlTY3TpkS6bSamoez1l7HnPLEdbdrLqEG_-zRVusNmELqrhvHBqWScd5";
	
	include_once("GCMPushMessage.php");
	$an = new GCMPushMessage($api_key);
	$an->setDevices(array($registration_key));
	$response = $an->send($message);
	print "<pre>";
	print_r($response);exit;
?>