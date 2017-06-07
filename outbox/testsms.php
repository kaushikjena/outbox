<?php
	//==================================== Simple PHP code sample ==========================================//
	
	/*
	* This example requires allow_url_fopen to be enabled in php.ini. If it is not enabled, file_get_contents()
	* will return an empty result. 
	* 
	* We recommend that you use port 5567 instead of port 80, but your
	* firewall will probably block access to this port (see FAQ for more
	* details):
	* $url = 'http://usa.bulksms.com:5567/eapi/submission/send_sms/2/2.0';
	* 
	* Please note that this is only for illustrative purposes, we strongly recommend that you use our comprehensive example
	*/
	$url = 'http://usa.bulksms.com/eapi/submission/send_sms/2/2.0';
	//$msisdn = '44123123123';
	$msisdn = '918895618447,919438423292,919853247406,919583199271';
	//$msisdn = '919438423292';
	$data = 'username=boxware&password=80xW4r3!&message='.urlencode('This is Testing SMS sir.').'&msisdn='.urlencode($msisdn);

	$response = do_post_request($url, $data);

	print $response;
	$response =explode("|",$response);
	print_r($response);
	// $response = 25|FAILED_USERCREDITS|501714790

	function do_post_request($url, $data, $optional_headers = 'Content-type:application/x-www-form-urlencoded') {
		$params = array('http'      => array(
			'method'       => 'POST',
			'content'      => $data,
			));
		if ($optional_headers !== null) {
			$params['http']['header'] = $optional_headers;
		}
	
		$ctx = stream_context_create($params);


		$response = @file_get_contents($url, false, $ctx);
		if ($response === false) {
			print "Problem reading data from $url, No status returned\n";
		}
	
		return $response;
	}
?>
