<?php

// Server credentials
$hst_hostname = 'server.hestiacp.com';
$hst_port = '8083';
$hst_returncode = 'no';
$hst_username = 'admin';
$hst_password = 'p4ssw0rd';
$hst_command = 'v-list-web-domain';

// Account
$username = 'demo';
$domain = 'demo.hestiacp.com';
$format = 'json';

// Prepare POST query
$postvars = array(
    'user' => $hst_username,
    'password' => $hst_password,
    'returncode' => $hst_returncode,
    'cmd' => $hst_command,
    'arg1' => $username,
    'arg2' => $domain,
    'arg3' => $format
);

// Send POST query via cURL
$postdata = http_build_query($postvars);
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://' . $hst_hostname . ':' . $hst_port . '/api/');
curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
$answer = curl_exec($curl);

// Parse JSON output
$data = json_decode($answer, true);

// Print result
print_r($data);