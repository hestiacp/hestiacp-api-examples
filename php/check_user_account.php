<?php
$hostname = 'server.yourdomain.tld';
$port = '8083';
$hstadmin = 'admin';
$hstadminpw = 'AdMin_pWd';

$username = $_POST['username'];
$password = $_POST['password'];

$postvars = array(
    'user' => $hstadmin,
    'password' => $hstadminpw,
    'returncode' => 'no',
    'cmd' => 'v-check-user-password',
    'arg1' => $username,
    'arg2' => $password,
    );

// Send POST query via cURL
$postdata = http_build_query($postvars);
$curl = curl_init();
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_URL, 'https://' . $hostname . ':' . $port . '/api/');
curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
$answer = curl_exec($curl);

//var_dump($answer);
// Check result

if($answer == 'OK') {
    echo "OK: User can login\n";
} else {
    echo "Error: Username or password is incorrect\n";
}
