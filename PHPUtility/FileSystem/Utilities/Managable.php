<?php

namespace PHPUtility\FileSystem\Utilities;

interface Managable
{
    // mkdir and create file are part of fs module
    // mode musty be set in file object
    public  function open();
    public  function close();
    public  function setContext($context);
    public  function getContext();
    public  function delete();
    public  function copy(string $newPath);
    public  function move(string $newPath);
    public  function rename(string $newPath);
    public  function size();
}
