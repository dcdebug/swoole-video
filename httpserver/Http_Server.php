<?php

class Http_Server
{

    static $host = '0.0.0.0';
    static $port = '81';

    public static function start()
    {

        $http = new Swoole\Http\Server(self::$host, self::$port);
        $http->set([
            'document_root' => __DIR__ . DIRECTORY_SEPARATOR . "../html",
            'enable_static_handler' => true,
            //'daemonize'=>1  // 设置后台启动
        ]);
        $http->on("request", function ($request, $response) {

            //list($controller, $action) = explode('/', trim($request->server['request_uri'], '/'));
            //根据 $controller, $action 映射到不同的控制器类和方法
            //(new $controller)->$action($request, $response);
            $response->header("Content-Type", "text/html; charset=utf-8");
            var_dump($request);
            $response->end("<h1>Hello Swoole. #" . "</h1>" . "<h3>Request id fd :" . $request->fd . "</h3>");

        });
        $http->start();
    }
}

//开启Http服务
Http_Server::start();
