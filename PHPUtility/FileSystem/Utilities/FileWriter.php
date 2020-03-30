<?php

namespace phpmosaic\core\storage;

use phpmosaic\core\storage\Manageable;

class FileWriter extends FileResource
{

  public function __construct($path, $append = false)
  {
    $this->mode = 'w';

    if ($append) {
      $this->mode = 'a';
    }

    parent::__construct($path);
  }

  public function write($string)
  {

    if (!file_exists($this->path)) {
      throw new \Exception("Error file does not exists to write");
      return false;
    }

    $this->handle = fopen($this->path, $this->mode);
    fwrite($this->handle, $string);
  }
}
// $fw = new FileWriter("users.json");
// $fw->write('{
//   "fname":"ouaret",
//   "lname":"samy",
//   "age":23
// }
// ');
// $fw->write('{
//   "fname":"ouaret",
//   "lname":"samy",
//   "age":23
// }
// ');
