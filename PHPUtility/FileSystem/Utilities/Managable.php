<?php

namespace PHPUtility\FileSystem\Utilities;

interface Managable
{
    // mkdir and create file are part of fs module
    // mode musty be set in file object
    public  function open();
    public  function close(): bool;
    public  function setContext($context);
    public  function getContext();
    public  function delete(): bool;
    public  function copy(string $newPath): bool;
    public  function moveTo(string $newPath): bool;
    public  function rename(string $newPath): bool;
    public  function size();
}
