<?php

namespace phpmosaic\core\storage;

use phpmosaic\core\storage\Manageable;

class FileReader extends FileResource
{

  public function __construct($path)
  {
    $this->mode = 'r';
    parent::__construct($path);
  }

  public function read()
  {

    if (!file_exists($this->path)) {
      throw new \Exception("Error file does not exists to read");
      return false;
    }
    $this->handle = fopen($this->path, $this->mode);
    return fread($this->handle, filesize($this->path));
  }

  public function to_array()
  {

    if (!file_exists($this->path)) {
      throw new \Exception("Error file does not exists to read");
      return false;
    }
    return file($this->path);
  }
}
// $fr = new FileReader("users.json");
// echo $fr->read();
// pre($fr->to_array());
// $fr->close();
// function pre($var){
//   echo "<pre>";
//     print_r($var);
//   echo "</pre>";
// }
