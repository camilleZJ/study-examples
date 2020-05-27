const http = require('http')
const fs   = require('fs')
 
http.createServer(function(request, response){
  console.log('request come',request.url)
  
  const host = request.headers.host
  if(request.url === '/'){
    const html  = fs.readFileSync('text.html','utf8')
    if(host === 'test.com') {
      response.writeHead(200,{
        'Content-type':'text/html',
        'Set-Cookie':['id=123;max-age=20', 'abc=345;domain=test.com'] 
      })
    }
    response.end(html)
  }
}).listen(8888)
 
console.log('server listening on 8888')