<?php

namespace phpmosaic\core\storage;

use phpmosaic\core\storage\Manageable;

class File  implements Manageable
{

  private $path;

  public function __construct($path)
  {
    $this->path = $path;
  }

  public  function create()
  {
    if (file_exists($this->path)) {
      throw new \Exception("can't create <b>" . $this->path . "</b> File already exists");
      return false;
    }
    var_dump($this->path);
    $this->handle = \fopen($this->path, 'x');
  }

  public  function delete()
  {
    return unlink($this->path);
  }

  public  function copy($newPath)
  {
    if (file_exists($newPath . DIRECTORY_SEPARATOR . $this->path)) {
      throw new \Exception("target path <b>" . $newPath . "</b> File already exists");
      return false;
    }
    return @copy($this->path, $newPath . DIRECTORY_SEPARATOR . $this->path);
  }
  public  function size()
  {
    if (file_exists($this->path))
      return filesize($this->path);
    return false;
  }
}
