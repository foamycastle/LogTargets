<?php

namespace FoamyCastle\Log;

use Socket;

abstract class SocketTarget extends LogTarget
{
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
    protected function targetCreate(array|string $options): mixed
    {
        return stream_context_create($options);
    }

    /**
     * @inheritDoc
     */
    protected function targetOpen(array|string $options): mixed
    {
        // TODO: Implement targetOpen() method.
    }

    /**
     * @inheritDoc
     */
    protected function targetClose(array|string $options): bool
    {
        // TODO: Implement targetClose() method.
    }

    /**
     * @inheritDoc
     */
    protected function targetExists(array|string $options): bool
    {
        // TODO: Implement targetExists() method.
    }

    /**
     * @inheritDoc
     */
    protected function targetMakeReady(array|string $options): bool
    {
        // TODO: Implement targetMakeReady() method.
    }

    /**
     * @inheritDoc
     */
    protected function targetUnset(array|string $options): bool
    {
        // TODO: Implement targetUnset() method.
    }

    /**
     * @inheritDoc
     */
    protected function targetClear(array|string $options): bool
    {
        // TODO: Implement targetClear() method.
    }

    /**
     * @inheritDoc
     */
    function writeMessage(string $message): bool
    {
        // TODO: Implement writeMessage() method.
    }
}