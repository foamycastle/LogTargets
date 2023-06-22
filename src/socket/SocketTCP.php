<?php

namespace FoamyCastle\Log\Socket;

use FoamyCastle\Log\Socket\SocketTarget;

class SocketTCP extends SocketTarget
{
    public function __construct(string $address, int $port)
    {
        $this->address=$address;
        $this->port=$port;
        $thisHost=gethostbyname(gethostname());
        $this->context=stream_context_create(
        [
            'tcp'=>[
                'bind'=>$thisHost
            ],

        ],
        [
            'notification'=>[
                $this,'socketNotify'
            ]
        ]
        );
        $this->socket=$this->targetCreate([]);
        if($this->socket) $this->isWriteable=true;
    }

    protected function targetCreate(array|string $options): mixed
    {
        $stream = stream_socket_client(
            "tcp://".$this->address.":".$this->port,
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
        // TODO: Implement targetOpen() method.
    }

    protected function targetClose(array|string $options): bool
    {
        // TODO: Implement targetClose() method.
    }

    protected function targetExists(array|string $options): bool
    {
        // TODO: Implement targetExists() method.
    }

    protected function targetMakeReady(array|string $options): bool
    {
        // TODO: Implement targetMakeReady() method.
    }

    protected function targetUnset(array|string $options): bool
    {
        // TODO: Implement targetUnset() method.
    }

    protected function targetClear(array|string $options): bool
    {
        // TODO: Implement targetClear() method.
    }
}