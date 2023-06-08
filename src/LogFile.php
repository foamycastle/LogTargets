<?php

namespace FoamyCastle\Log;
use FoamyCastle\Utils\Writer;

class LogFile extends Writer implements LogFileInterface
{
    /**
     * Contains the local path to the log file
     */
    private string $logFilePath;

    public function __construct(string $fileName)
    {
        $this->logFilePath=$fileName;
        $this->resource=$this->openFile($this->logFilePath);
        if(false===$this->resource){
            $this->canWrite=false;
            return;
        }
        $this->canWrite=true;

    }
    public function __destruct()
    {
        $this->closeResource();
    }
    function closeResource()
    {
        unset($this->resource);
    }

    /**
     * Creates a resource stream for writing
     * @return resource the created resource
     */
    protected function createResource($options = [], $params = [])
    {
        $stream=stream_context_create();
        //stream_set_blocking($stream,true);
        return $stream;
    }

    /**
     * Open a local file for writing and return the resource;
     * @param $fileName
     * @return false|resource
     */
    private function openFile($fileName){
        if (file_exists($fileName))
            $resource = fopen($fileName,'a',false,$this->createResource());
        else{
            $resource = fopen($fileName, 'c',false,$this->createResource());
        }
        if(!$resource){
            return false;
            //throw new BadPathException($fileName);
        }

        return $resource;
    }

    /**
     * @inheritDoc
     */
    function write($data)
    {
        if (!$this->canWrite) return 0;
        return fwrite($this->resource,$data);
    }
    public function closeFile(): bool
    {
        $this->canWrite=false;
        return fclose($this->resource);
    }
    public function delete(): bool
    {
        $this->canWrite=false;
        return unlink($this->logFilePath);
    }
    public function clear(): bool
    {
        if(!$this->closeResource()){
            return false;
        }
        unlink($this->logFilePath);
        $this->resource=$this->openFile($this->logFilePath);
        if(!$this->resource){
            return false;
        }
        return $this->canWrite=true;
    }

}