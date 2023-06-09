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
     * Create a target which will accept messages for commit.
     * @param array $options
     * @return bool
     */
    abstract protected function targetCreate(array $options=[]):mixed;

    /**
     * Verifies the existences of a target.
     * @return bool
     */
    abstract protected function targetExists():bool;

    /**
     * Prepare a target to receive log messages.
     * @param array $options
     * @return bool
     */
    abstract protected function targetMakeReady(array $options=[]):bool;

    /**
     * Remove the target resource from the system.
     * @return bool
     */
    abstract protected function targetUnset():bool;

    /**
     * Erase all committed messages on a target without destroying the target
     * @param string $name
     * @return bool true if successful
     */
    abstract protected function targetClear():bool;

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
     * Replaces all formatting symbols with data
     * @param string $message string to be formatted. passed by reference
     * @return void
     */
    function formatMessage(string &$message): void
    {
        $format = $this->messageFormat == "" ? self::DEFAULT_MESSAGE_FORMAT : $this->messageFormat;
        $message = str_replace(self::FORMAT_MESSAGE, $message, $format);
        $message = str_replace(self::FORMAT_LEVEL, self::LOG_LEVEL[$this->currentLogLevel], $message);
        $message = str_replace(self::FORMAT_TIMESTAMP, (new DateTime())->format(DATE_RFC3339), $message);
    }

    abstract protected function formatMessage(string &$message):void;

    /**
     * Set a default log level to be used with the __invoke() method
     * @param LogLevel $level
     * @return static
     */
    abstract function setDefaultLogLevel(LogLevel $level):static;

}
