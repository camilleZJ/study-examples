const http = require("http");
const fs = require("fs");

http.createServer(function(request, response) {
    console.log("request come", request.url);

    const html = fs.readFileSync('text.html', 'utf-8');
    const img = fs.readFileSync('test.jpg')
    if (request.url === '/') {
        response.writeHead(200, {
          "Content-type": "text/html",  
            "Connection": "close",
            'Link': '</test.jpg>;as=image;rel=preload'  //as=image说明类型，rel=preload代表服务端使用server-push
        });
        response.end(html)
    }else {
        response.writeHead(200, {
            "Content-Type": "image/jpg",
            'Connection': 'keep-alive'     
        });   
        response.end(img)
    }
  }).listen(8888);

console.log("server listening on 8888");

