<?php
// Put your device token here (without spaces):
$deviceToken = '70e8de77864d4f3a183e89501c60974dd2d251d84985ffec0b45f518d139ad75';
//$deviceToken ="7bb3119abfb564a1cdc8afc30d3ec6a8809f607c298e676d9c65c637cf359251";
//$deviceToken="04dc87d6d9adeceeceb47c0b7a074ae5d7f29bc7dab55da2f87a5b18904bc46a"; //Ipad device token
// Put your private key's passphrase here:
$passphrase = 'blet';
// Put your alert message here:
$message = 'This is test notification for boxware app.';
$badge=2;

////////////////////////////////////////////////////////////////////////////////

$ctx = stream_context_create();
stream_context_set_option($ctx, 'ssl', 'local_cert', 'box.pem');
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

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
