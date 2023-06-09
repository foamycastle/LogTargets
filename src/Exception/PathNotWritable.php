<?php

namespace FoamyCastle\Log\Exception;

class PathNotWritable extends \Exception
{
    public function __construct($path)
    {
        parent::__construct("The given path '$path' is not writable");
    }
}