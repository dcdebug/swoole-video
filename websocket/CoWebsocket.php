<?php

namespace Websocket;

//Cortoutine WebSocket Server

class CoWebsocket
{

    static  $host = '127.0.0.1';
    static $port = '9502';

    /**
     * @params $request
     * @param $ws
     * Cortouine Websocket的回调函数
     */
    public static function Callback_function($request, $ws)
    {
        var_dump($request);
        var_dump($request->server);
        $server_params = $request->server;
        if(strtoupper($server_params['server_protocol']) !== "HTTP/1.1"){
            //禁止http/https请求
            $ws->upgrade(); //向客户端发送握手信息
            while (true) {
                $frame = $ws->recv();
                var_dump($frame);
                if ($frame === false) {
                    echo "error :" . swoole_last_error() . PHP_EOL;
                } else if(empty($frame)) {
                    echo "empty frame".PHP_EOL;
                }else{
                    if($frame->data == "close"){
                        $ws->close();
                        return ;
                    }
                    $ws->push("Hello ".$frame->data." !");
                    $ws->push("How are you ,".$frame->data." ?----");
                }
            }
        }else{
            //当成http请求处理的时候，禁止访问
            $ws->end("test");
        }

    }

    /**
     * 开启start
     */
    public static function bootstrap(){
        \Co\run(function() {
                $server = new \Co\Http\Server(self::$host,self::$port,false);
                // $server->on('start',[self::class,'start']);
                $server->handle("/",[self::class,'Callback_function']);
                $server->start();
        });
    }
}

CoWebsocket::bootstrap();
