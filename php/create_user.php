<?php

// Server credentials
$hst_hostname = 'server.hestiacp.com';
$hst_port = '8083';
$hst_username = 'admin';
$hst_password = 'p4ssw0rd';
$hst_returncode = 'yes';
$hst_command = 'v-add-user';

// New Account
$username = 'demo';
$password = 'd3m0p4ssw0rd';
$email = 'demo@gmail.com';
$package = 'default';
$first_name = 'Rust';
$last_name = 'Cohle';

// Prepare POST query
$postvars = array(
    'user' => $hst_username,
    'password' => $hst_password,
    'returncode' => $hst_returncode,
    'cmd' => $hst_command,
    'arg1' => $username,
    'arg2' => $password,
    'arg3' => $email,
    'arg4' => $package,
    'arg5' => $first_name,
    'arg6' => $last_name
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

// Check result
if($answer === 0) {
    echo "User account has been successfuly created\n";
} else {
    echo "Query returned error code: " .$answer. "\n";
}