<?php
include('TextMagicAPI.php');
$api = new TextMagicAPI(array(
    "username" => "debasisacharya",
    "password" => "MYugQUXyce"
));

$text = "Testing textmagic send message.";

// Use this number for testing purposes. This is absolutely free.
//$phones = array(9991234567);99912345678, 99987654321
$phones = array(99912345678);

$results = $api->send($text, $phones, true);
print "<pre>";
print_r($results);
?>