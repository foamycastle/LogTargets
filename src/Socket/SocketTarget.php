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
    protected bool $addNewLine;
    protected bool $addMsgLen;

    /**
     * @inheritDoc
     */
    function writeMessage(string $message): bool
    {
        if(!$this->socket) return false;
        if($this->addMsgLen){
            $message = strlen($message)." ".$message;
        }
        if($this->addNewLine){
            $message.=PHP_EOL;
        }
        return fputs($this->socket,$message);
    }

    /**
     * A setting that adds a CRLF to the end of every transmission.  Some log receivers require this.
     * @param bool $newLine when TRUE, a CRLF will be added to the end of every transmission
     * @return $this
     */
    public function setAddNewLine(bool $newLine):static
    {
        $this->addNewLine=$newLine;
        return $this;
    }

    /**
     * Prepends each transmission with the message length
     * @param bool $msgLen When TRUE, each transmission will be prepended with its byte length
     * @return $this
     */
    public function setAddMsgLen(bool $msgLen):static
    {
        $this->addMsgLen=$msgLen;
        return $this;
    }

    public function getAddNewLine():bool
    {
        return $this->addNewLine;
    }
    public function getAddMsgLen():bool
    {
        return $this->addMsgLen;
    }

}