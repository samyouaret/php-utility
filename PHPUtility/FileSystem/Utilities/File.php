<?php

namespace PHPUtility\FileSystem\Utilities;

use PHPUtility\FileSystem\Exceptions\DirectoryDoesNotExistException;
use PHPUtility\FileSystem\Exceptions\FileDoesNotExistException;
use PHPUtility\FileSystem\Exceptions\InvalidModeException;
use PHPUtility\FileSystem\Exceptions\PerformOperationOnClosedFileException;
use PHPUtility\FileSystem\Utilities\Managable;
use PHPUtility\FileSystem\Utilities\Readable;
use PHPUtility\FileSystem\Utilities\Writable;
use PHPUtility\FileSystem\Utilities\Path;

class File  implements Managable, Readable, Writable
{
  //Open for reading only; place the file pointer at the beginning of the file. 
  public const READ_ONLY = 'r';
  // Open for reading and writing; place the file pointer at the beginning of the file. 
  public const READ_AND_WRITE = 'r+';
  // If the file does not exist, attempt to create it. 
  public const WRITE_ONLY = 'w';
  //  If the file does not exist, attempt to create it. 
  public const WRITE_AND_READ = 'w+';
  // If the file does not exist, attempt to create it
  // fseek() has no effect,
  public const APPEND_WRITE = 'a';
  //  fseek() only affects the reading position,
  public const APPEND_WRITE_AND_READ = 'a+';
  // Create and open for writing only, error if file exists
  // If the file does not exist, attempt to create it
  public const CREATE_AND_WRITE = 'x';
  // 	Create and open for reading and writing; 
  //otherwise it has the same behavior as 'x'. 
  public const CREATE_READ_AND_WRITE = 'x+';

  protected string $path;
  protected $handle;
  protected string $mode;
  protected array $supporedModes = ['r', 'r+', 'w', 'w+', 'a', 'a+', 'x', 'x+'];
  protected $context = NULL;
  protected bool $useIncludePath = FALSE;

  public function __construct(string $path, string $mode = self::READ_ONLY)
  {
    // call it statically fix this 
    $this->ensureValidMode($mode);
    $this->ensureValidPathWithMode($path, $mode);
    $this->path = $path;
    $this->mode = $mode;
  }

  public  function open()
  {
    if (($this->handle = @fopen(
      $this->path,
      $this->mode,
      $this->useIncludePath,
      $this->context
    ))) {
      return true;
    }
    // here only write modes are able to reach
    throw new DirectoryDoesNotExistException("trying to open Non existing Directoy for write", 1);
  }

  public  function close()
  {
    $bool = fclose($this->handle);
    $this->handle = null;
    return $bool;
  }

  public  function read(int $length = 0)
  {
    $this->ensureCanRead();
    $length = $length > 0 ? $length : filesize($this->path);
    // file_get_contents is more efficient than fread
    return fread($this->handle, $length);
  }

  public  function eof()
  {
    return feof($this->handle);
  }

  public  function rewind(): bool
  {
    return rewind($this->handle);
  }

  public  function readAsync(int $length = 1024)
  {
    $this->ensureCanRead();
    /// until here called only once when get called firt time
    while (!feof($this->handle) && $chunk = fread($this->handle, $length)) {
      $key = ftell($this->handle);
      // you can get arg from  ex  $gen->send('stop') us the return value  of yield
      yield $key => $chunk;
    }
    /// here called only once after it finish
  }

  public  function content()
  {
    $this->ensureCanRead();
    return file_get_contents($this->path, $this->useIncludePath, $this->context);
  }

  public  function readLine(int $length = 0)
  {
    $this->ensureCanRead();
    $length = $length > 0 ? $length : filesize($this->path);
    return fgets($this->handle, $length);
  }

  public  function readLineAsync(int $length = 1024)
  {
    $this->ensureCanRead();
    while (!feof($this->handle) && $chunk = fgets($this->handle, $length)) {
      $key = ftell($this->handle);
      yield $key => $chunk;
    }
  }

  public  function readChar()
  {
    $this->ensureCanRead();
    return fgetc($this->handle);
  }

  public  function readCharAsync()
  {
    $this->ensureCanRead();
    while (!feof($this->handle) && $char = fgetc($this->handle)) {
      $key = ftell($this->handle);
      yield $key => $char;
    }
  }

  public  function write(string $string): int
  {
    return 1;
  }

  public  function move(string $newPath)
  {
  }

  public  function rename(string $newPath)
  {
  }

  public  function delete()
  {
    return unlink($this->path);
  }

  public  function copy(string $newPath)
  {
    $newPath = Path::join($newPath, $this->path);
    if (file_exists($newPath)) {
      throw new \Exception("target path $newPath File already exists");
      return false;
    }
    return @copy($this->path, $newPath);
  }

  public  function size()
  {
    $this->ensureFileIsOpended();
    return filesize($this->path);
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

  /**
   * Get the value of useIncludePath
   */
  public function getUseIncludePath()
  {
    return $this->useIncludePath;
  }

  /**
   * Set the value of useIncludePath
   *
   * @return  self
   */
  public function setUseIncludePath($useIncludePath)
  {
    $this->useIncludePath = $useIncludePath;

    return $this;
  }

  /**
   * Get the value of mode
   */
  public function getMode()
  {
    return $this->mode;
  }

  public function ensureCanRead()
  {
    $this->ensureFileIsOpended();
    $this->ensureReadMode();
  }

  public function ensureValidMode(string $mode)
  {
    if (!$this->isValidMode($mode)) {
      throw new \Exception("invalid Mode '$this->mode' provided to open file", 1);
    }
  }

  public  function ensureFileIsOpended()
  {
    if (!$this->isOpen()) {
      throw new PerformOperationOnClosedFileException('try to read/write on closed file', 1);
    }
  }

  public  function isOpen()
  {
    return $this->handle !== null;
  }

  public function isValidMode(string $mode)
  {
    return in_array($mode, $this->supporedModes);
  }

  public function ensureReadMode()
  {
    $nonReadModes = ['w', 'x'];
    if (in_array($this->mode, $nonReadModes)) {
      throw new InvalidModeException("invalid Mode '$this->mode' provided to read file", 1);
    }
  }

  public function ensureValidPathWithMode(string $path, string $mode)
  {
    if (
      ($mode == self::READ_ONLY || $mode == self::READ_AND_WRITE)
      && !Path::exists($path)
    ) {
      throw new FileDoesNotExistException("trying to open non Existing File For Read", 1);
    }
  }
}
