<?php

namespace PHPUtility\FileSystem\Utilities;

use PHPUtility\FileSystem\Utilities\Managable;

class Directory implements Managable
{
  private $path;

  public function __construct($path)
  {
    $this->path = $path;
  }

  public  function create()
  {
    @mkdir($this->path);
  }
  
  public  function delete()
  {
    if (file_exists($this->path)) {
      $this->remove_dir($this->path);
    }
  }

  private function remove_dir($path)
  {

    $handle = opendir($path);
    while (($file = readdir($handle)) !== false) {

      if ($file != "." && $file != "..") {

        if (is_dir($path . DIRECTORY_SEPARATOR . $file)) {

          $this->remove_dir($path . DIRECTORY_SEPARATOR . $file);
        } else {

          unlink($path . DIRECTORY_SEPARATOR . $file);
        }
      }
    }
    closedir($handle);
    return rmdir($path);
  }

  public  function copy($newPath)
  {
    if (file_exists($this->path))
      $this->copy_dir($this->path, $newPath);
  }

  private function copy_dir($src, $des)
  {

    $handle = opendir($src);
    @mkdir($des);

    while (($file = readdir($handle)) !== false) {

      if ($file != "." && $file != "..") {

        if (is_dir($src . DIRECTORY_SEPARATOR . $file)) {
          $this->copy_dir($src . DIRECTORY_SEPARATOR . $file, $des . DIRECTORY_SEPARATOR . $file);
        } else {
          copy($src . DIRECTORY_SEPARATOR . $file, $des . DIRECTORY_SEPARATOR . $file);
        }
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
  // }

}
