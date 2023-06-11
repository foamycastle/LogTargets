<?php

namespace FoamyCastle\Log;
use FoamyCastle\Utils\MessageFormatter\MessageFormatter;
use Psr\Log\LogLevel;

abstract class LogTarget
{
    /**
     * RFC4122 Log levels
     */
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
     * @var string $messageTemplate
     */
    protected string $messageTemplate;

    /**
     * MessageFormatter object handles the search-and-replace operations of replacing symbols in the log
     * message with their corresponding values
     * @var MessageFormatter $logEntryFormatter
     */
    protected MessageFormatter $logEntryFormatter;

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
     * @return bool true if successful
     */
    abstract protected function targetClear(): bool;

    /**
     * Return the entirely-formatted log message with all log template symbols replaced
     * @param string $message The already-processed user message portion.
     * @return string a ready-to-write log entry
     */
    abstract protected function prepareLogTemplate(string $message): string;
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
    function getMessageTemplate(): string
    {
        return $this->messageTemplate;
    }

    /**
     * Set the message template from which each log message will be built
     * @param string $format
     * @return $this
     */
    function setMessageTemplate(string $format): static
    {
        $this->messageTemplate = $format;
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
     * Set a default log level to be used with the __invoke() method
     * @param int|string $level
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

    /**
     * Set the log level for the pending message write
     * @param int|string $level can either be the integer level or the string representation of the integer value
     * @return $this
     */
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
     * Indicates that the log target is able to accept write operations
     * @return bool TRUE if the target is write-ready
     */
    function isWritable(): bool
    {
        return $this->isWriteable;
    }

}
