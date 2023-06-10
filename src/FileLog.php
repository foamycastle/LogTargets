<?php

namespace FoamyCastle\Log;

use FoamyCastle\Log\Exception\PathNotWritable;
use FoamyCastle\Utils\MessageFormatter\MessageFormatter;

final class FileLog extends LogTarget
{
    /**
     * If no filename is provided at instantiation, this will be used as a substitute
     */
    private const DEFAULT_FILENAME='logfile.log';
    private const DEFAULT_DIRNAME='tmplogs';
    /**
     * the log file name
     * @var string $fileName
     */
    private string $fileName;

    /**
     * The file system path to the log file
     * @var string $pathName
     */
    private string $pathName;
    /**
     * A full assembly of $fileName and $pathName
     * @var string $logFilePath
     */
    private string $logFilePath;
    /**
     * The php resource against which write operations will be run
     * @var resource $fileResource
     */
    private $fileResource;

    public function __construct(string $fileName="", $path="")
    {
        if($fileName=="") $fileName=self::DEFAULT_FILENAME;
        if($this->writePermissionsCheck($fileName,$path)){
            $this->fileName=$fileName;
            $this->pathName=(str_ends_with($path,DIRECTORY_SEPARATOR)?$path:$path.DIRECTORY_SEPARATOR);
            $this->logFilePath=$this->pathName.$this->fileName;
        }else{
            throw new PathNotWritable($path);
        }
        if(!$this->targetMakeReady()){
            throw new PathNotWritable($path);
        }
        $this->setDefaultLogLevel(7);
        $this->setCurrentLogLevel(7);
        $this->setMessageFormat(self::DEFAULT_MESSAGE_FORMAT);


    }
    private function writePermissionsCheck($filename,$path):bool{
        $path=($path==""?__DIR__.DIRECTORY_SEPARATOR.self::DEFAULT_DIRNAME:$path);
        if(is_writable($path)){
            $path=(str_ends_with($path,DIRECTORY_SEPARATOR)?$path:$path.DIRECTORY_SEPARATOR);
            if(file_exists($path.$filename)){
                return is_writable($path.$filename);
            }else{
                return true;
            }
        }
        return false;
    }

    /**
     * Returns the full path string of the current target
     * @return string
     */
    public function getTargetPath():string{
        return $this->logFilePath??"";
    }
    /**
     * @inheritDoc
     */
    function writeMessage(string $message): bool
    {
        $formatter = new MessageFormatter($message,$this->getContextOptions());
        $message = $this->prepareLogTemplate($formatter->getMessage()).PHP_EOL;
        unset($formatter);
        if($this->isWriteable){
            return (false!==fwrite($this->fileResource,$message,strlen($message)));
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    protected function targetCreate(array $options=[]):mixed
    {
        return @fopen($this->logFilePath,'w');
    }

    /**
     * @inheritDoc
     */
    protected function targetExists(): bool
    {
        if(!isset($this->logFilePath)) return false;
        return file_exists($this->logFilePath);
    }

    /**
     * @inheritDoc
     */
    protected function targetMakeReady(array $options = []): bool
    {
        if(!isset($this->logFilePath)) return false;
        //file does not exist, create it
        if(!$this->targetExists()){
            $this->fileResource=$this->targetCreate();
            $this->isWriteable=true;
            return true;
        }

        //file exists, open it
        $this->fileResource=@fopen($this->logFilePath,'a');
        if(is_resource($this->fileResource)){
            $this->isWriteable=true;
            return true;
        }
        return false;
    }

    /**
     * Replaces all formatting symbols with data
     * @param string $message string to be formatted. passed by reference
     * @return void
     */
    function prepareLogTemplate(string $message): string
    {
        $format = $this->messageFormat == "" ? LogTarget::DEFAULT_MESSAGE_FORMAT : $this->messageFormat;
        $symbols=[
            (LogTarget::FORMAT_MESSAGE)     => $message,
            (LogTarget::FORMAT_LEVEL)       => $this->getCurrentLogLevelString(),
            (LogTarget::FORMAT_TIMESTAMP)   => function(){
                $date=new \DateTime('now');
                return $date->format(DATE_RFC2822);
            },
            (LogTarget::FORMAT_HOSTNAME)    =>$_SERVER['SERVER_NAME'],
            (LogTarget::FORMAT_PORT)        =>$_SERVER['SERVER_PORT']
        ];
        return (new MessageFormatter($format,$symbols))->getMessage();
    }

    /**
     * @inheritDoc
     */
    protected function targetUnset(): bool
    {
        $this->isWriteable=false;
        return unlink($this->logFilePath);
    }

    /**
     * @inheritDoc
     */
    protected function targetClear(): bool
    {
        if(!isset($this->logFilePath)) return false;
        if($this->targetExists()){
            $this->fileResource=$this->targetCreate();
            $this->isWriteable=false;
            return fclose($this->fileResource);
        }
        return false;
    }

    public function isWritable(): bool
    {
        return $this->isWriteable;
    }

}