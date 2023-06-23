<?php

namespace FoamyCastle\Log\Socket;
use FoamyCastle\Log\LogTarget;
use Socket;

abstract class SocketTarget extends LogTarget
{
    /**
     * @var string $address
     */
    protected string $address;
    protected int $port;
    /**
     * @var resource|Socket $socket
     */
    protected $socket;
    /**
     * @var resource $context
     */
    protected $context;

    /**
     * @inheritDoc
     */
    function writeMessage(string $message): bool
    {
        if(!$this->socket) return false;
        return fputs($this->socket,$message);
    }
}