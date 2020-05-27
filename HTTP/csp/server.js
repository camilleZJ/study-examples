const http = require("http");
const fs = require("fs");

http.createServer(function(request, response) {
    console.log("request come", request.url);

    const html = fs.readFileSync('text.html', 'utf-8');
    if (request.url === '/') {
        response.writeHead(200, {
          "Content-type": "text/html",  
          //"Content-Security-Policy": "default-src https: http: 'unsafe-inline'",
         // "Content-Security-Policy-Report-Only": "default-src 'self';report-uri /report"
        });
        response.end(html)
    } else if(request.url === '/report'){
      response.end(request);
    } else {
        response.writeHead(200, {
            "Content-Type": "application/javascript",
            'Connection': 'keep-alive'     
        });   
        response.end('console.log("loaded script")')
    }
  }).listen(8888);

console.log("server listening on 8888");

