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
const hst_command = 'v-add-user'

//New account details
const username = 'demo';
const password = 'd3m0p4ssw0rd';
const email = 'demo@gmail.com';
const package = 'default';
const first_name = 'Rust';
const last_name = 'Cohle';

const data_json = {
'user': hst_username,
'password': hst_password,
'returncode': hst_returncode,
'cmd': hst_command,
'arg1': username,
'arg2': password,
'arg3': email,
'arg4': package,
'arg5': first_name,
'arg6': last_name
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