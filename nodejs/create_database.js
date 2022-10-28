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
const hst_command = 'v-add-databse'

//Domain details
const username = 'demo'
const db_name = 'wordpress'
const db_user = 'wordpress'
const db_pass = 'wpbl0gp4s'

const data_json = {
'user': hst_username,
'password': hst_password,
'returncode': hst_returncode,
'cmd': hst_command,
'arg1': username,
'arg2': db_name,
'arg3': db_user,
'arg4': db_pass
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