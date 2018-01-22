<?php

$clientId	= '***-***-**';
$clientSecret	= '****';
$p24_login	= '380***';
$p24_pw		= '***';
$p24_acc	= '2600*********';

// --------------------------------------------------
function pbRequest($rr, $is_post, $token, $object) {
	$url = "https://link.privatbank.ua/api/$rr";
	$ch = curl_init();
	$arr = array('Content-Type: application/json', 'Accept: application/json');
	if ($token != null) array_push($arr, "Authorization: Token $token");
	curl_setopt($ch, CURLOPT_HTTPHEADER, $arr);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	if ($is_post) {
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($object));
		curl_setopt($ch, CURLOPT_POST, 1);
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$res = json_decode(curl_exec($ch));
	return $res;
}


// --------------------------------------------------

//create session
print "- create session:\n";
$res = pbRequest('auth/createSession', true, null, (object) array('clientId' => $clientId, 'clientSecret' => $clientSecret));
print "\tid: " . $res->id . "\n\texpires: ". date("l dS of F Y h:i:s A", $res->expiresIn) ."\n";
$session_id = $res->id;

//validate session
print "- validate session:\n";
$res = pbRequest('auth/validateSession', true, null, (object) array('sessionId' => $session_id));
print "\tclient id: " . $res->clientId ."\n";
$session_id = $res->id;

// fetch data
print "- get data:";
$res = pbRequest("p24b/statements?stdate=01.01.2018&endate=12.01.2018&acc=$p24_acc&showInf", false, $session_id, (object) array());


// ----- WORK WITH THE DATA HERE ----
print_r($res);



//remove session
print "- remove session:\n";
$res = pbRequest($url . "removeSession", true, null, (object) array('sessionId' => $session_id));
print "\tremoval result: ". $res->result ."\n";
