<?php

namespace FoamyCastle\Log;

use FoamyCastle\Log\LogTarget;

class TCPSocket extends LogTarget
{
    /**
     * @var string $address Address used for socket connection
     */
    private string $address;
    /**
     * @var int $port the port on the address used for the connection
     */
    private int $port;
    /**
     * @var int $connectionTimeout The number of second before a connection timeout is triggered
     */
    private int $connectionTimeout;
    /**
     * @var resource $stream the stream resource against which write operations will be performed
     */
    private $stream;
    /**
     * @var resource $resource;
     */
    private $resource;

    public function __construct(string $address, int $port, int $connectionTimeout=10)
    {
        //TODO: validate address and port
        $this->address=$address;
        $this->port=$port;
        $this->connectionTimeout=$connectionTimeout;
        $this->stream=$this->targetCreate([]);
        $this->isWriteable=$this->targetMakeReady([]);
    }

    /**
     * @inheritDoc
     */
    protected function targetCreate(array|string $options): mixed
    {
        $newResource=stream_socket_client(
            "tcp://".$this->address.":".$this->port,
            $errno,
            $error,
            $this->connectionTimeout,
            STREAM_CLIENT_CONNECT
        );

        if($errno==0&&$newResource!==false){
            return $newResource;
        }

        return false;

    }

    /**
     * @inheritDoc
     */
    protected function targetOpen(array|string $options): mixed
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    protected function targetClose(array|string $options): bool
    {
        return stream_socket_shutdown($this->stream,STREAM_SHUT_RDWR);
    }

    /**
     * @inheritDoc
     */
    protected function targetExists(array|string $options): bool
    {
        $didWrite=@fwrite($this->stream,"Test",0);
        return $didWrite!==false;
    }

    /**
     * @inheritDoc
     */
    protected function targetMakeReady(array|string $options): bool
    {
        return $this->targetExists([]);
    }

    /**
     * @inheritDoc
     */
    protected function targetUnset(array|string $options): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function targetClear(array|string $options): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    function writeMessage(string $message): bool
    {
        return @fwrite($this->stream,$message,strlen($message))!==false;
    }
}