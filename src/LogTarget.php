<?php

namespace FoamyCastle\Log;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use FoamyCastle\Utils\ContextProcessor;

abstract class LogTarget implements LoggerInterface
{
    protected const DEFAULT_MESSAGE_FORMAT = "{log-level}: {log-timestamp} {log-message}";
    protected const FORMAT_MESSAGE='{log-message}';
    protected const FORMAT_TIMESTAMP='{log-timestamp}';
    protected const FORMAT_LEVEL='{log-level}';
    protected const LOG_LEVEL=[
        0=>LogLevel::EMERGENCY,
        1=>LogLevel::ALERT,
        2=>LogLevel::CRITICAL,
        3=>LogLevel::ERROR,
        4=>LogLevel::WARNING,
        5=>LogLevel::NOTICE,
        6=>LogLevel::INFO,
        7=>LogLevel::DEBUG
    ];
    /**
     * Indicates whether the process is able to and/or has permissions to write log messages.
     * @var bool true if log messages may br written to the filesystem
     */
    protected bool $isWriteable=false;
    /**
     * Contains key->value pairs that will be substituted in the log message
     * @var array
     */
    protected array $options;

    /**
     * An integer representing the log level
     * @var int
     */
    protected int $defaultLogLevel;
    /**
     * The log level of the current message;
     * @var int $currentLogLevel
     */
    protected int $currentLogLevel=7;

    /**
     * A string that contains plain text and symbols that serves as the blueprint for each log message
     * @var string $messageFormat
     */
    protected string $messageFormat;

    /**
     * A key->value hash that contains pieces of data that will replace {curly brace} elements in the log message
     * @var array $contextOptions
     */
    protected array $contextOptions=[];

    /**
     * Write the string to the resource or database
     * @param string $message
     * @return string
     */
    abstract protected function writeMessage(string $message):bool;

    /**
     * Create a target which will accept messages for commit. If the target is a database, the $name
     * will be the database name and $path will be the table name.  If the target is a file, the $name
     * will be the file name, and $path will be a discrete system path.
     * @param string $name a target identifier
     * @param string $path
     * @return bool
     */
    abstract protected function targetCreate(string $name,string $path=''):bool;

    /**
     * Verifies the existences of a target. If the target is a database, the $name
     * will be the database name and $path will be the table name.  If the target is a file, the $name
     * will be the file name, and $path will be a discrete system path.
     * @param string $name
     * @param string $path
     * @return bool
     */
    abstract protected function targetExists(string $name,string $path=''):bool;

    /**
     * Prepare a target to receive log messages.
     * @param string $name
     * @param string $path
     * @param array $options
     * @return bool
     */
    abstract protected function targetMakeReady(string $name, string $path='', array $options=[]):bool;

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
    abstract protected function targetUnset(string $name,string $path=''):bool;

    /**
     * Erase all committed messages on a target without destroying the target
     * @param string $name
     * @return bool true if successful
     */
    abstract protected function targetClear(string $name):bool;

    /**
     * Set a static array of context options to be used in each message commit. key=>value pairs.
     * @param array<string,string|int|object|array|float|bool> $options
     * @return static
     */
    abstract protected function setContextOptions(...$options):static;

    /**
     * Returns the entire array of context options currently set. If no options are set, an empty array
     * is returned.
     * @return array
     */
    abstract protected function getContextOptions():array;
    /**
     * Remove a single or many context option(s) given its(their) key(s)
     * @param string|array $key
     * @return bool
     */
    abstract protected function removeContextOptions(string|array $key):bool;

    /**
     * An invokable commit method
     * @param LogLevel $level log level 0-7
     * @param string $message log message
     * @param array $context context options given here override options set with setContextOptions()
     * @return bool returns true if the message commit was successful
     */
    abstract protected function __invoke(LogLevel $level,string $message,array $context=[]):bool;

    /**
     * Sets the format in which log messages will be committed
     * @param string $format
     * @return static
     */
    abstract protected function setMessageFormat(string $format):static;

    /**
     * Returns the currently used log message format
     * @return string
     */
    abstract protected function getMessageFormat():string;

    /**
     * Set a static log level to be used in message commits
     * @param LogLevel $level
     * @return static
     */
    abstract protected function setLogLevel(LogLevel $level):static;

}
