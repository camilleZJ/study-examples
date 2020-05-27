const http = require("http");
const fs = require("fs");

http
  .createServer(function(request, response) {
    // console.log("request come", request.url);
    console.log("request come", request.headers.host);

    if (request.url === "/") {
      response.writeHead(302, { //301-永久重定向  302-临时重定向
        "Location": '/new'  //不加域信息，默认同域下
      });
      response.end("");
    }
    if (request.url === "/new") {
      response.writeHead(200, {});
      response.end("<div>new page url</div>");
    }
  })
  .listen(8888);

console.log("server listening on 8888");
