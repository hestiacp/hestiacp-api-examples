<?php

// Server credentials
$hst_hostname = 'server.hestiacp.com';
$hst_port = '8083';
$hst_username = 'admin';
$hst_password = 'p4ssw0rd';
$hst_returncode = 'yes';
$hst_command = 'v-add-mail-account'; // Refer: https://github.com/hestiacp/hestiacp/blob/release/bin/v-add-mail-account & https://hestiacp.com/docs/reference/cli.html#v-add-mail-account

// Domain details
$username = 'demo';
$domain = 'demo.hestiacp.com';

// Prepare POST query
$postvars = array(
    'user' => $hst_username,
    'password' => $hst_password,
    'returncode' => $hst_returncode,
    'cmd' => $hst_command,
    'arg1' => $user,
    'arg2' => $domain
	'arg3' => $domain_idn
	'arg4' => $account
	'arg5' => $password
	'arg6' => $HIDE // 4
	'arg7' +> $quota // {5-unlimited}
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
    echo "Added new mail account.\n";
} else {
    echo "Query returned error code: " .$answer. "\n";
}
