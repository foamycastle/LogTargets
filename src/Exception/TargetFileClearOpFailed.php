<?php

namespace FoamyCastle\Log\Exception;

class TargetFileClearOpFailed extends \Exception
{
    public function __construct()
    {
        parent::__construct("The log file target 'clear' operation failed");
    }
}