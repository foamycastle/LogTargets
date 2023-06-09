<?php

namespace FoamyCastle\Log;

use FoamyCastle\Utils\MessageFormatter\MessageFormatter;
use Psr\Log\LogLevel;

abstract class LogTarget
{

    /**
     * Indicates whether the process is able to and/or has permissions to write log messages.
     * @var bool true if log messages may br written to the filesystem
     */
    protected bool $isWriteable = false;

    /**
     * Create a target which will accept messages for commit.
     * @param array $options
     * @return bool
     */
    abstract protected function targetCreate(string|array $options): mixed;

    /**
     * Open an existing target for continued use
     * @param string|array $options
     * @return mixed
     */
    abstract protected function targetOpen(string|array $options): mixed;

    /**
     * Close an open target
     * @param string|array $options
     * @return bool
     */
    abstract protected function targetClose(string|array $options): bool;

    /**
     * Verifies the existences of a target.
     * @return bool
     */
    abstract protected function targetExists(string|array $options): bool;

    /**
     * Prepare a target to receive log messages.
     * @param array $options
     * @return bool
     */
    abstract protected function targetMakeReady(string|array $options): bool;

    /**
     * Remove the target resource from the system.
     * @return bool
     */
    abstract protected function targetUnset(string|array $options): bool;

    /**
     * Erase all committed messages on a target without destroying the target
     * @return bool true if successful
     */
    abstract protected function targetClear(string|array $options): bool;

    /**
     * Write the string to the resource or database
     * @param string $message
     * @return bool
     */
    abstract function writeMessage(string $message): bool;

    /**
     * Indicates that the log target is able to accept write operations
     * @return bool TRUE if the target is write-ready
     */
    function isWritable(): bool
    {
        return $this->isWriteable;
    }

}
