<?php

namespace phpmosaic\core\storage;

abstract class FileResource
{
  protected $handle;
  protected $path;
  protected $mode;

  public function __construct($path)
  {
    $this->handle = null;
    $this->path = $path;
  }

  public function reset()
  {
    rewind($this->handle);
  }

  public function resource_pointer()
  {
    if (!is_null($handle))
      return  ftell($this->handle);
    return null;
  }

  public function is_eof()
  {
    return  feof($this->handle);
  }

  public function close()
  {
    fclose($this->handle);
  }
}
