<?php

namespace PHPUtility\FileSystem\Utilities;

use PHPUtility\FileSystem\Exceptions\DirectoryNotExistException;
use PHPUtility\FileSystem\Exceptions\DirectoryException;
use PHPUtility\FileSystem\Exceptions\DirectoryExistsException;
use PHPUtility\FileSystem\Utilities\Managable;
use PHPUtility\FileSystem\Utilities\Readable;

class Directory implements Managable, Readable
{
  protected string $path;
  protected $context;
  protected $handle;

  public function __construct(string $path, $context = NULL)
  {
    $this->path = $path;
    $this->context = $context ?? stream_context_get_default();
  }

  public function open()
  {
    $this->ensureIsdir();
    $this->handle = @opendir($this->path, $this->context);
    if (!$this->handle) {
      throw new DirectoryException("Cannot open directory $this->path due to permission restrictions,
       or due to filesystem errors.  ", 1);
    }
    return true;
  }

  public  function close(): bool
  {
    // check to ignore bugs, If the directory handle is not specified, 
    // will close the last link opened by opendir. 
    if ($this->handle) {
      closedir($this->handle);
      $this->handle = null;
      return true;
    }
    return false;
  }

  public  function read()
  {
    $this->ensureDirIsOpended();
    return readdir($this->handle);
  }

  public function rewind()
  {
    $this->ensureDirIsOpended();
    return rewinddir($this->handle);
  }

  public  function create(int $mode = 0777, bool $recursive = FALSE): bool
  {
    if (is_dir($this->path)) {
      throw new DirectoryException("Cannot create directory $this->path ,
      directory exists.", 1);
    }
    if (!@mkdir($this->path, $mode, $recursive, $this->context)) {
      throw new DirectoryException("Cannot create directory $this->path ,
      due to permission restrictions.", 1);
    }
    return true;
  }

  public  function moveTo(string $newPath): bool
  {
    return rename($this->path, $newPath, $this->context);
  }

  public  function rename(string $newPath): bool
  {
    return rename($this->path, $newPath, $this->context);
  }

  public  function delete(): bool
  {
    $this->ensureIsdir();
    return @rmdir($this->path, $this->context);
  }

  public function forceDelete(): bool
  {
    $this->ensureIsdir();
    return $this->remove_dir($this->path);
  }

  private function remove_dir($path)
  {
    $handle = opendir($path);
    while (($file = readdir($handle)) !== false) {
      if ($file == "." || $file == "..") {
        continue;
      }
      $pathToDelete  = Path::join($path, $file);
      if (is_dir($pathToDelete)) {
        $this->remove_dir($pathToDelete);
      } else {
        unlink($pathToDelete);
      }
    }
    closedir($handle);
    return rmdir($path);
  }

  public  function copy(string $newPath): bool
  {
    if (!file_exists($this->path))
      return false;
    return $this->copy_dir($this->path, $newPath);
  }

  private function copy_dir($src, $des)
  {
    $handle = opendir($src);
    @mkdir($des);
    while (($file = readdir($handle)) !== false) {
      if ($file == "." || $file == "..") {
        continue;
      }
      $src = Path::join($src, $file);
      $des = Path::join($des, $file);
      if (is_dir($src)) {
        return $this->copy_dir($src, $des);
      } else {
        return copy($src, $des);
      }
    }
    closedir($handle);
  }

  function size()
  {

    if (file_exists($this->path))
      return $this->rec_size($this->path);
    return 0;
  }

  function rec_size($path, $size = 0)
  {

    $handle = opendir($path);

    while (($file = readdir($handle)) !== false) {

      if ($file != "." && $file != "..") {

        if (is_dir($path . DIRECTORY_SEPARATOR . $file)) {
          $this->rec_size($path . DIRECTORY_SEPARATOR . $file, $size);
        } else {
          $size += filesize($path . DIRECTORY_SEPARATOR . $file);
        }
      }
    }
    closedir($handle);
    return $size;
  }

  public function to_json()
  {
    return json_encode($this->to_array($this->path), true);
  }

  function to_array($path)
  {
    $dir = opendir($path);
    $result = [];
    static $limit = 0;

    if ($limit == 300) {
      return $result;
    }

    while ($file = readdir($dir)) {

      if ($file == '.' || $file == '..') {
        continue;
      }

      if (is_dir($path . '/' . $file)) {
        $result[$path . '/' . $file]  = $this->to_array($path . '/' . $file);
      } else {
        $result["parent"][] = $file;
      }
      $limit++;
    }

    closedir($dir);
    return $result;
  }

  public function ensureIsdir()
  {
    if (!is_dir($this->path)) {
      throw new DirectoryNotExistException("Invalid path for given directory", 1);
    }
  }

  public  function ensureDirNotExist()
  {
    if (!is_dir($this->path)) {
      throw new DirectoryExistsException("Cannot Perform operation On existing Directory $this->path", 1);
    }
  }

  public  function ensureDirIsOpended()
  {
    if (!$this->isOpen()) {
      throw new DirectoryException('Direcoty is not open', 1);
    }
  }

  public  function isOpen()
  {
    return $this->handle !== null;
  }

  /**
   * Get the value of context
   */
  public function getContext()
  {
    return $this->context;
  }

  /**
   * Set the value of context
   *
   * @return  self
   */
  public function setContext($context)
  {
    $this->context = $context;

    return $this;
  }
}
