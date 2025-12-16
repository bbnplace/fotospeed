import fs from 'fs';
import https from 'https';
import http from 'http';

const options = {
    key: fs.readFileSync('certs/server.key'),
    cert: fs.readFileSync('certs/server.crt'),
};

const TARGET_HOST = '192.168.1.106'; // Target running PHP server
const TARGET_PORT = 8200;
const LISTEN_PORT = 8201;

const server = https.createServer(options, (req, res) => {
    const proxyReq = http.request({
        host: TARGET_HOST,
        port: TARGET_PORT,
        path: req.url,
        method: req.method,
        headers: {
            ...req.headers,
            'X-Forwarded-Proto': 'https',
            'X-Forwarded-Port': LISTEN_PORT,
        },
    }, (proxyRes) => {
        res.writeHead(proxyRes.statusCode, proxyRes.headers);
        proxyRes.pipe(res, { end: true });
    });

    req.pipe(proxyReq, { end: true });

    proxyReq.on('error', (e) => {
        console.error(e);
        res.writeHead(500);
        res.end('Proxy Error');
    });
});

server.listen(LISTEN_PORT, () => {
    console.log(`Secure Proxy running at https://${TARGET_HOST}:${LISTEN_PORT}`);
    console.log(`Forwarding to http://${TARGET_HOST}:${TARGET_PORT}`);
});
