<?php
// Put your device token here (without spaces):
//$deviceToken = '21cc79b179752d498a0f8fd8fa25fe37a1796c84f5c7f65b6f5cca32966940df';
//$deviceToken ="7bb3119abfb564a1cdc8afc30d3ec6a8809f607c298e676d9c65c637cf359251";
//$deviceToken="04dc87d6d9adeceeceb47c0b7a074ae5d7f29bc7dab55da2f87a5b18904bc46a"; //Ipad device token
//$deviceToken="3c867e454967c5d37264524c0c87fd620635662de013b7a1af05f7e1a6a11eb8";//golden ipad
$deviceToken = "6868677af6e4833bea095dd11eb8098d729874b6d9a05a872adaa5e4bc29640a";
// Put your private key's passphrase here:
$passphrase = 'risktraker';
// Put your alert message here:
$message = 'Gapa helebi sata!';
$badge=2;

////////////////////////////////////////////////////////////////////////////////

$ctx = stream_context_create();
stream_context_set_option($ctx, 'ssl', 'local_cert', './pushnotificationlive/rt.pem');
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

if (!$fp)
	exit("Failed to connect: $err $errstr" . PHP_EOL);

echo 'Connected to APNS' . PHP_EOL;

// Create the payload body
$body['aps'] = array(
	'alert' => $message,
	'sound' => 'default',
	'badge' => $badge
	);

// Encode the payload as JSON
$payload = json_encode($body);

// Build the binary notification
$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
$result = fwrite($fp, $msg, strlen($msg));

if (!$result)
	echo 'Message not delivered' . PHP_EOL;
else
	echo 'Message successfully delivered' . PHP_EOL;

// Close the connection to the server
fclose($fp);
