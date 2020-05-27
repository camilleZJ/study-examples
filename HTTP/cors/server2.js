const http = require('http')
 
http.createServer(function(request, response){
  console.log('request come',request.url)
 
 //解决跨域
  response.writeHead(200,{
    'Access-Control-Allow-Origin':'http://localhost:8888', //或者'Access-Control-Allow-Origin':'http://127.0.0.1:8888' 注意页面访问用的是localhost就要用http://localhost:8888，浏览器不回去匹配IP地址
    'Access-Control-Allow-Methods': 'PUT, POST', //请求方法不是get、post、head
    'Access-Control-Allow-Headers': 'Content-Type, X-Test-Core', //自定义头或者Content-Type不是text/plain、multipart/form-data、appication/x-www-form-urlencoded
    'Access-Control-Allow-Credentials': true, //携带cookie跨域，
    'Access-Control-Max-Age': '1000'
})
  response.end('123')
}).listen(8887)
 
console.log('server listening on 8887')