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
    function socketNotify(
        int $notification_code,
        int $severity,
        string $message,
        int $message_code,
        int $bytes_transferred,
        int $bytes_max
    ):void
    {
        echo "socket notification: \ncode: $notification_code\nseverity: $severity\nmessage: $message\nmessage code: $message_code\nbytes transferred: $bytes_transferred\nbytes max: $bytes_max";
    }
}