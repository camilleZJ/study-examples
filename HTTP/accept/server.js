const http = require("http");
const fs = require("fs");
const zlib = require('zlib')

http.createServer(function(request, response) {
    console.log("request come", request.url);

    const html = fs.readFileSync('text.html');
    response.writeHead(200, {
      "Content-type": "text/html",
      'Content-Encoding': 'gzip' //声明以gzip的方式传输
    });
    response.end(zlib.gzipSync(html))
  })
  .listen(8888);

console.log("server listening on 8888");

