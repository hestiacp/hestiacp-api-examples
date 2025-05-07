<?php

function WriteBindConfig($file, $content)
{
    // Write the contents FYI, may need sudo
    file_put_contents($file, implode("\x0A", $content));
    echo "Successfully updated $file" . PHP_EOL;
}

function ReadBindConfig($file)
{
    // Read bind9 config split on new line
    $cfg_file    = file_get_contents($file);
    $rows        = explode("\x0A", $cfg_file);
    return $rows;
}

function UpdateBindConfigFile($file)
{
    $filecontents = ReadBindConfig($file);
    $rowcount = 0;
    
    foreach($filecontents as $row)
    {
        if( str_starts_with(trim(strtolower($row)), 'zone') ) { break; }
        $rowcount++;
    }
    // keep the header
    array_splice($filecontents, $rowcount);
    
    // pull all the DNS domains from Hestia API
    $domains = GetHSDomains();
    $totalUpdated = 0;
    $total = count($domains);
    foreach($domains as $key => $domain)
    {
        if(NSValidator($domain)) {
            array_push($filecontents, SecondaryStringTemplate($key, $domain['IP']));
            $totalUpdated++;
        }
    }
    
    if( $totalUpdated > 0) {
        echo "$totalUpdated of $total defined as secondary authoritative domains." . PHP_EOL;
        WriteBindConfig($file, $filecontents);
    } else {
        // In case of network or API error, don't change the file
        echo "No zone records, $file not changed.";
    }
}

function SecondaryStringTemplate($domainname, $primary)
{
    // template for secondary domain on bind9, update this if syntax needs a change.
    $templateValue = "zone \"$domainname\" { type slave; file \"db.$domainname\"; masters { $primary; }; };";
    return $templateValue;
}

function NSValidator($domainvalues)
{
    global $config;
    // only values that have the primary server as the SOA
    if(isset($domainvalues['IP']) && isset($domainvalues['SOA']) && $config['primary'] == $domainvalues['SOA'])
    {
        return true;
    }
    return false;
}

function GetHSUserDomans($user)
{
    // Get all domains for a Hestia user
    $reqvars = array(
        'cmd' => 'v-list-dns-domains',
        'arg1' => $user,
        'arg2' => 'json' // use config
    );

    $domains = sanitizeBadJson(APIReq($reqvars));

    $data = "";
    if(!empty($domains)) {
        // Parse JSON output
        $data = json_decode($domains, true);
    }

    return $data;
}

function GetHSDomains()
{
    // For each Hestia user GetUserDomains and merge
    $users = GetHSUsers();

    $alldomains = array();
    foreach($users as $key => $user)
    {
        $domains = GetHSUserDomans($user);
        if( isset($domains) && !empty($domains) ) { // skip domainless users like possibly admin
            $alldomains = array_merge($alldomains, $domains); }
    }
    return $alldomains;
}

function GetHSUsers()
{
    // get all the users
    $reqvars = array(
        'cmd' => 'v-list-sys-users',
        'arg1' => 'plain' // use plain for simplicity
    );

    // request the user list
    $users = APIReq($reqvars);

    $data = "";
    if(!empty($users)) {
        // Parse output
        // New line delimited list, use array filter to purge empty values.
        $data = array_filter(explode("\x0A", $users));
    }

    return $data;
}

function sanitizeBadJson($json)
{
    $minified = preg_replace('/\s+/', '',$json);
    
    // The ending comma causes json decode to fail so fix that.
    // Hestia versions prior to commit 77e69939c ("Replace bin/v-list-sys-users by bin/v-list-users (#3930)", 2023-08-16)
    if(str_ends_with($minified,",]")) {
        return substr($minified, 0, -2)."]";
    }
    return $minified;
}

function APIReq($callreqvars)
{
    global $config;

    // Prepare POST query
    $std_reqvars = array(
        'user' => $config['server']['username'],
        'password' => $config['server']['pwd'],
        'returncode' => 'no'
    );
    $postreqvars = array_merge( $callreqvars, $std_reqvars );

    // Send POST query via cURL
    $postreqdata = http_build_query($postreqvars);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://' . $config['server']['host'] . ':' . $config['server']['port'] . '/api/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postreqdata);
    $response = curl_exec($ch);
    
    if( str_starts_with($response, "Error: "))
    {
        echo "WARNING Web Request ".$response.PHP_EOL;
        //print_r($callreqvars);
        $response = "";
    }
    
    return $response;
}

// Server credentials
$config = array(
    'server' => array(
        'host' => 'server.hestiacp.com',
        'port' => '8083',
        'username' => 'admin',
        'pwd' => 'p4ssw0rd'
    ),
    'primary' => 'ns1.hestiacp.com' // this is the primary server to use for this secondary server
);

// for a basic server setup with bind9 the secondary records will be in the below file
$cfg_file = '/etc/bind/named.conf.local';
// Update the config file, FYI, Bind may need to be restarted after
UpdateBindConfigFile($cfg_file);

// Tested on a 2 doller per month IONOS VM running Ubuntu with bind9, php-cli, php-curl installed
// akutra.tm@leapmaker.com for more information

