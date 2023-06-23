<?php

namespace FoamyCastle\Log\Socket;

use FoamyCastle\Log\Socket\SocketTarget;

class SocketUDP extends SocketTarget
{
    public function __construct(string $address, int $port)
    {
        $this->address=$address;
        $this->port=$port;
        $this->addMsgLen=true;
        $this->addNewLine=true;
        $thisHost=gethostbyname(gethostname());
        $this->context=stream_context_create(
        [
            'udp'=>[
                'bindto'=>$thisHost.":0"
            ],

        ]
        );
        $this->socket=$this->targetCreate([]);
        if($this->socket) $this->isWriteable=$this->targetMakeReady([]);
    }

    protected function targetCreate(array|string $options): mixed
    {
        $stream = stream_socket_client(
            "udp://".$this->address.":".$this->port,
            $errno,
            $error,
            10,
            STREAM_CLIENT_CONNECT,
            $this->context
        );

        if(empty($error)) return $stream;
        echo $error;
        return null;
    }

    protected function targetOpen(array|string $options): mixed
    {
        return null;
    }

    protected function targetClose(array|string $options): bool
    {
        return stream_socket_shutdown($this->socket,STREAM_SHUT_RDWR);
    }

    protected function targetExists(array|string $options): bool
    {
        return (false!==@stream_socket_get_name($this->socket,true));
    }

    protected function targetMakeReady(array|string $options): bool
    {
        if($this->targetExists([])) return true;
        return false;
    }

    protected function targetUnset(array|string $options): bool
    {
        return true;
    }

    protected function targetClear(array|string $options): bool
    {
        return true;
    }
}