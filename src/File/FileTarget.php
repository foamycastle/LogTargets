<?php

namespace FoamyCastle\Log\File;

use FoamyCastle\Log\Exception\PathNotWritable;
use FoamyCastle\Log\Exception\TargetFileClearOpFailed;
use FoamyCastle\Log\LogTarget;

class FileTarget extends LogTarget
{
    /**
     * @var resource $resource A php file pointer resource
     */
    private $resource;

    /**
     * @var string $filePath A local path to the log file
     */
    private $filePath;

    /**
     * @var bool $autoEOL A flag that indicates whether an EOL should be appended to each write operation
     */
    private $autoEOL;

    public function __construct(string $path, bool $autoEOL=true)
    {
        //assign properties
        $this->autoEOL=$autoEOL;
        $this->filePath = $path;

        //test if a resource exists
        if (!$this->targetExists($this->filePath)) {
            //if not, create it
            $this->resource = $this->targetCreate($this->filePath);
        } else {
            //if so, open it
            $this->resource = $this->targetOpen(($this->filePath));
        }
        //test if the create/open operation failed
        if(!$this->resource){
            throw new PathNotWritable($path);
        }

        //set the class in a ready-to-write state
        $this->targetMakeReady();
    }

    public function __destruct()
    {
        $this->targetClose();
    }

    /**
     * Toggle the auto End-Of-Line feature
     * @param bool $eol
     * @return $this
     */
    public function setEOL(bool $eol):self{
        $this->autoEOL=$eol;
        return $this;
    }

    /**
     * @param array|string $options The full path to the file on the system
     * @inheritDoc
     */
    protected function targetCreate(array|string $options = []): mixed
    {
        return @fopen($options, 'x');
    }

    /**
     * Open an existing target for continued use
     * @param string|array $options The full path to the file on the system
     * @return mixed
     */
    protected function targetOpen(array|string $options = []): mixed
    {
        return @fopen($options, 'a');
    }

    /**
     * Close an open target
     * @param string|array $options
     * @return bool
     */
    protected function targetClose(array|string $options = []): bool
    {
        return @fclose($this->resource);
    }

    /**
     * @param array|string $options The full path to the file on the system
     * @inheritDoc
     */
    protected function targetExists(array|string $options = []): bool
    {
        return file_exists($options);
    }

    /**
     * @inheritDoc
     */
    protected function targetMakeReady(array|string $options = []): bool
    {
        return $this->isWriteable=(@fwrite($this->resource,"File Ready",0)!==false);
    }

    /**
     * @param array $options
     * @inheritDoc
     */
    protected function targetUnset(array|string $options = []): bool
    {
        $this->targetClose();
        unset($this->resource);
        $this->isWriteable=false;
        return unlink($this->filePath);
    }

    /**
     * @param array $options
     * @inheritDoc
     */
    protected function targetClear(array|string $options = []): bool
    {
        if($this->isWriteable) {
            $this->targetUnset();
        }

        $this->resource=$this->targetCreate($this->filePath);
        if(!$this->resource){
            throw new TargetFileClearOpFailed();
        }

        return $this->targetMakeReady();

    }

    /**
     * @inheritDoc
     */
    function writeMessage(string $message): bool
    {
        if(!$this->isWriteable) return false;
        $message .= $this->autoEOL ? PHP_EOL : "";
        return (@fwrite($this->resource,$message,strlen($message))!==false);
    }
}