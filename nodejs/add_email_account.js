//NodeJS Script
//You must have the axios module installed
const axios = require('axios')
const querystring = require('querystring');

//Admin Credentials
const hst_hostname = 'server.hestiacp.com'
const hst_port = 8083
const hst_username = 'admin'
const hst_password = 'p4ssw0rd'
const hst_returncode = 'yes'
const hst_command = 'v-add-mail-account' // Refer: https://github.com/hestiacp/hestiacp/blob/release/bin/v-add-mail-account & https://hestiacp.com/docs/reference/cli.html#v-add-mail-account

//Domain details
const username = 'demo'; //username to associate the domain
const domain = 'domain.tld'; //domain

const data_json = {
'user': hst_username,
'password': hst_password,
'returncode': hst_returncode,
'cmd': hst_command,
'arg1': user,
'arg2': domain,
'arg3': account,
'arg4': password,
'arg5': $quota // {5-unlimited}
}

const data = querystring.stringify(data_json)

axios.post('https://'+hst_hostname+':'+hst_port+'/api/', data)
.then(function (response) {
    console.log(response.data);
    console.log("0 means successful")
})
.catch(function (error) {
    console.log(error);
});
