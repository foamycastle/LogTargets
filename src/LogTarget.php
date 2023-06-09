<?php

namespace FoamyCastle\Log;

use Psr\Log\LogLevel;

abstract class LogTarget
{
    protected const DEFAULT_MESSAGE_FORMAT = "{log-level}: {log-timestamp} {log-message}";
    protected const FORMAT_MESSAGE = '{log-message}';
    protected const FORMAT_TIMESTAMP = '{log-timestamp}';
    protected const FORMAT_LEVEL = '{log-level}';
    protected const LOG_LEVEL = [
        0 => LogLevel::EMERGENCY,
        1 => LogLevel::ALERT,
        2 => LogLevel::CRITICAL,
        3 => LogLevel::ERROR,
        4 => LogLevel::WARNING,
        5 => LogLevel::NOTICE,
        6 => LogLevel::INFO,
        7 => LogLevel::DEBUG
    ];
    public const UPPERCASE = 1;
    public const LOWERCASE = 2;
    /**
     * Indicates whether the process is able to and/or has permissions to write log messages.
     * @var bool true if log messages may br written to the filesystem
     */
    protected bool $isWriteable = false;
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
    protected int $currentLogLevel = 7;

    /**
     * A string that contains plain text and symbols that serves as the blueprint for each log message
     * @var string $messageFormat
     */
    protected string $messageFormat;

    /**
     * A key->value hash that contains pieces of data that will replace {curly brace} elements in the log message
     * @var array $contextOptions
     */
    protected array $contextOptions = [];

    /**
     * Write the string to the resource or database
     * @param string $message
     * @return bool
     */
    abstract function writeMessage(string $message): bool;

    /**
     * Returns the message format string
     * @return string
     */
    function getMessageFormat(): string
    {
        return $this->messageFormat;
    }

    /**
     * @inheritDoc
     */
    function setMessageFormat(string $format): static
    {
        $this->messageFormat = $format;
        return $this;
    }

    /**
     * Return a string representation of the default log level
     * @param int $case return either upper or lower case string
     * @return string log level
     */
    function getDefaultLogLevelString(int $case = self::LOWERCASE): string
    {
        return $case = self::LOWERCASE ?
            self::LOG_LEVEL[$this->defaultLogLevel] :
            strtoupper(self::LOG_LEVEL[$this->defaultLogLevel]);
    }

    /**
     * Return an integer representation of the default log level
     * @return int log level
     */
    function getDefaultLogLevelInt(): int
    {
        return $this->defaultLogLevel;
    }

    /**
     * Return a string representation of the current log level
     * @param int $case flag return either upper or lower case string
     * @return string log level
     */
    public function getCurrentLogLevelString(int $case = self::LOWERCASE): string
    {
        return $case = self::LOWERCASE ?
            self::LOG_LEVEL[$this->currentLogLevel] :
            strtoupper(self::LOG_LEVEL[$this->currentLogLevel]);
    }

    /**
     * Return an integer representation of the current log level
     * @return int log level
     */
    public function getCurrentLogLevelInt(): int
    {
        return $this->currentLogLevel;
    }

    /**
     * Returns the entire array of context options currently set. If no options are set, an empty array
     * is returned.
     * @return array
     */
    function getContextOptions(): array
    {
        return $this->contextOptions;
    }

    /**
     * Set a static array of context options to be used in each message commit. key=>value pairs.
     * @param array<string,string|int|object|array|float|bool> $options
     * @return static
     */
    function setContextOptions(array $options): static
    {
        $this->contextOptions = $options;
        return $this;
    }

    /**
     * Set a default log level to be used with the __invoke() method
     * @param int $level
     * @return static
     */
    function setDefaultLogLevel(int|string $level): static
    {
        if (is_string($level)) {
            $level = array_search(strtolower($level), self::LOG_LEVEL);
            if (false === $level) $level = 7;
        }
        if ($level > 7 || $level < 0) $level = 7;
        $this->defaultLogLevel = $level;
        return $this;
    }

    public function setCurrentLogLevel(int|string $level): static
    {
        if (is_string($level)) {
            $level = array_search(strtolower($level), self::LOG_LEVEL);
            if (false === $level) $level = 7;
        }
        if ($level > 7 || $level < 0) $level = 7;
        $this->currentLogLevel = $level;
        return $this;
    }

    /**
     * Remove a single or many context option(s) given its(their) key(s)
     * @param string|array $key
     * @return bool
     */
    function removeContextOptions(array|string $key): bool
    {
        $hasRemoveSomething = false;
        if (is_array($key)) {
            foreach ($key as $item) {
                if (key_exists($item, $this->contextOptions)) {
                    unset($this->contextOptions[$item]);
                    $hasRemoveSomething = true;
                }
            }
            return $hasRemoveSomething;
        } else {
            if (key_exists($key, $this->contextOptions)) {
                unset($this->contextOptions[$key]);
                return true;
            }
            return false;
        }
    }

    function isWritable(): bool
    {
        return $this->isWriteable;
    }

    /**
     * Create a target which will accept messages for commit.
     * @param array $options
     * @return bool
     */
    abstract protected function targetCreate(array $options = []): mixed;

    /**
     * Verifies the existences of a target.
     * @return bool
     */
    abstract protected function targetExists(): bool;

    /**
     * Prepare a target to receive log messages.
     * @param array $options
     * @return bool
     */
    abstract protected function targetMakeReady(array $options = []): bool;

    /**
     * Remove the target resource from the system.
     * @return bool
     */
    abstract protected function targetUnset(): bool;

    /**
     * Erase all committed messages on a target without destroying the target
     * @param string $name
     * @return bool true if successful
     */
    abstract protected function targetClear(): bool;

    abstract protected function formatMessage(string &$message): void;

}
