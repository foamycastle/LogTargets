<?php

namespace FoamyCastle\Log;

class BadPathException extends \Exception
{
    public function __construct(string $filename)
    {
        parent::__construct("Unable to open $filename");
    }
}