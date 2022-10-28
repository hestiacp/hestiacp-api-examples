//NodeJS Script
//You must have the axios module installed
const axios = require('axios')
const querystring = require('querystring');

//Admin Credentials
const hst_hostname = 'server.hestiacp.com'
const hst_port = 8083
const hst_returncode = 'no'
const hst_username = 'admin'
const hst_password = 'p4ssw0rd'
const hst_command = 'v-list-web-domain'

//Domain details
const username = 'demo';
const domain = 'demo.hestiacp.com';
const format = 'json';

const data_json = {
'user': hst_username,
'password': hst_password,
'returncode': hst_returncode,
'cmd': hst_command,
'arg1': username,
'arg2': domain,
'arg3': format
}

const data = querystring.stringify(data_json)

axios.post('https://'+hst_hostname+':'+hst_port+'/api/', data)
.then(function (response) {
    console.log(JSON.stringify(response.data));

})
.catch(function (error) {
    console.log(error);
});