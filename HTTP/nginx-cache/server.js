const http = require("http");
const fs = require("fs");

const wait = seconds => {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            resolve()
        }, seconds * 1000)
    })
}

http.createServer(function(request, response) {
    console.log("request come", request.url);

    if (request.url === '/') {
        const html = fs.readFileSync('text.html', 'utf-8');
        response.writeHead(200, {
          "Content-type": "text/html",     
        });
        response.end(html)
    }

    if (request.url === '/data') {
        response.writeHead(200, {
            "Cache-Control": "max-age=3000",
            'Vary': 'X-Test-Cache'     
        });
        wait(2).then( () => {
            response.end('success')
        })
    }
  }).listen(8888);

console.log("server listening on 8888");

