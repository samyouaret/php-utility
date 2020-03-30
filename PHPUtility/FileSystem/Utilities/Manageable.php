<?php

namespace PHPUtility\FileSystem\Utilities;

interface Managable
{
    public  function create();
    public  function delete();
    public  function copy($newPath);
    public  function size();
}
