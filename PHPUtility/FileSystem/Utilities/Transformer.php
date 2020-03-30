<?php

namespace phpmosaic\core\storage;

use phpmosaic\core\storage\Manageable;

/* works only with paths*/

class Transformer
{

    public static function rename($path, $newPath)
    {
        // $path = realpath($this->path);
        if (!file_exists($newPath))
            return rename($path, $newPath);
        return false;
    }

    /* move a file or a entire dir using rename func */
    public static function move($path, $newPath)
    {
        return $this->exists() && rename($path, $newPath . DIRECTORY_SEPARATOR . $path);
    }
}
