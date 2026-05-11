#平滑重启 HTTP/WebSocket/TCP 请求处理
kill -USR1 $(cat runtime/hyperf.pid)

#平滑重启 HTTP/WebSocket/TCP 请求处理
kill -USR2 $(cat runtime/hyperf.pid)