<?php

namespace FoamyCastle\Log;

interface LogTarget
{
    function write($data);
    function closeFile():bool;
    function delete():bool;
    function clear():bool;
}