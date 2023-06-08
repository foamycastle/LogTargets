<?php

namespace FoamyCastle\Log\Test;

use FoamyCastle\Log\LogFile;
use PHPUnit\Framework\TestCase;

class LogFileTest extends TestCase
{
    public LogFile $logFile;
    public const LOGFILE_NAME='dummylog.log';
    protected function setUp(): void
    {
        parent::setUp();
        $this->logFile=new LogFile(self::LOGFILE_NAME);
    }

    public function testIsWritable()
    {
        self::assertTrue($this->logFile->isWritable());
    }

    public function testCloseFile()
    {
        self::assertTrue($this->logFile->closeFile());
        self::assertFalse($this->logFile->isWritable());
    }

    public function testClear()
    {
        $this->logFile->write('this is a fucka fucka ');
        $this->logFile->write('this is a ducka ducka ');
        $this->logFile->clear();
        $readFrom=file_get_contents(self::LOGFILE_NAME,false);
        self::assertEmpty($readFrom);
    }

    public function testGetResource()
    {
        self::assertIsResource($this->logFile->getResource());
    }

    public function testWrite()
    {
        $writeMessage='this is a dummy write message';
        $this->logFile->clear();
        $this->logFile->write($writeMessage);
        $readFrom=file_get_contents(self::LOGFILE_NAME,false);
        self::assertEquals($writeMessage,$readFrom);
    }

    public function testDelete()
    {
        self::assertFileExists(self::LOGFILE_NAME);
        $this->logFile->delete();
        self::assertFileDoesNotExist(self::LOGFILE_NAME);
    }
}
