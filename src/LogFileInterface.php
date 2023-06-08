<?php

namespace FoamyCastle\Log;

interface LogFileInterface
{
    function write($data);
    function closeFile():bool;
    function delete():bool;
    function clear():bool;
}