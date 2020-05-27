const http = require("http");
const fs = require("fs");

http.createServer(function(request, response) {
    console.log("request come", request.url);

    const html = fs.readFileSync("text.html", "utf8"); //同步读取
    response.writeHead(200, {
      "Content-type": "text/html" //不设置按照字符串读取，就会显示出来而不是解析html
    });
    response.end(html);
  })
  .listen(8888);

console.log("server listening on 8888");