<?php

namespace FoamyCastle\Log;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

abstract class LogTarget implements LoggerInterface
{
    /**
     * Create a target which will accept messages for commit. If the target is a database, the $name
     * will be the database name and $path will be the table name.  If the target is a file, the $name
     * will be the file name, and $path will be a discrete system path.
     * @param string $name a target identifier
     * @return bool
     */
    abstract function targetCreate(string $name,string $path=''):bool;

    /**
     * Verifies the existences of a target. If the target is a database, the $name
     * will be the database name and $path will be the table name.  If the target is a file, the $name
     * will be the file name, and $path will be a discrete system path.
     * @param string $name
     * @return bool
     */
    abstract function targetExists(string $name,string $path=''):bool;

    /**
     * Prepare a target to receive log messages.
     * @param string $name
     * @param string $path
     * @param array $options
     * @return bool
     */
    abstract function targetMakeReady(string $name, string $path='', array $options=[]):bool;

    /**
     * Remove the target resource from the system.  For databases, a supplied $path argument will attempt to remove
     * only the table to which messages were committed.  An omitted $path argument will attempt to remove the
     * entire database to which log messages were committed.  For files, $name is the filename of the log target and
     * $path is the system path in which the target is located. $path may be omitted if php will not require a discrete
     * path to locate the file.
     * @param string $name
     * @param string $path
     * @return bool
     */
    abstract function targetUnset(string $name,string $path=''):bool;

    /**
     * Erase all committed messages on a target without destroying the target
     * @param string $name
     * @return bool true if successful
     */
    abstract function targetClear(string $name):bool;

    /**
     * Set a static array of context options to be used in each message commit. key=>value pairs.
     * @param array<string,string|int|object|array|float|bool> $options
     * @return static
     */
    abstract function setContextOptions(...$options):static;

    /**
     * Returns the entire array of context options currently set. If no options are set, an empty array
     * is returned.
     * @return array
     */
    abstract function getContextOptions():array;
    /**
     * Remove a single or many context option(s) given its(their) key(s)
     * @param string|array $key
     * @return bool
     */
    abstract function removeContextOptions(string|array $key):bool;

    /**
     * An invokable commit method
     * @param LogLevel $level log level 0-7
     * @param string $message log message
     * @param array $context context options given here override options set with setContextOptions()
     * @return bool returns true if the message commit was successful
     */
    abstract function __invoke(LogLevel $level,string $message,array $context=[]):bool;

    /**
     * Sets the format in which log messages will be committed
     * @param string $format
     * @return static
     */
    abstract function setMessageFormat(string $format):static;

    /**
     * Returns the currently used log message format
     * @return string
     */
    abstract function getMessageFormat():string;

    /**
     * Set a static log level to be used in message commits
     * @param LogLevel $level
     * @return static
     */
    abstract function setLogLevel(LogLevel $level):static;

}