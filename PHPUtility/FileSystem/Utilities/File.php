<?php
// FIX: 
// check the read/write mode at open

namespace PHPUtility\FileSystem\Utilities;

use PHPUtility\FileSystem\Exceptions\DirectoryDoesNotExistException;
use PHPUtility\FileSystem\Exceptions\FileDoesNotExistException;
use PHPUtility\FileSystem\Exceptions\FileException;
use PHPUtility\FileSystem\Exceptions\InvalidModeException;
use PHPUtility\FileSystem\Exceptions\PerformOperationOnClosedFileException;
use PHPUtility\FileSystem\Utilities\Managable;
use PHPUtility\FileSystem\Utilities\Readable;
use PHPUtility\FileSystem\Utilities\Writable;
use PHPUtility\FileSystem\Utilities\Path;

class File  extends \SplFileInfo implements Managable, Readable, Writable
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
    parent::__construct($path);
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
  public function getFilePath()
  {
    return $this->path;
  }
  public  function close(): bool
  {
    $bool = fclose($this->handle);
    $this->handle = null;
    return $bool;
  }

  public  function read($length = NULL)
  {
    $this->ensureCanRead();
    $this->ensureReadLengthNotZero($length);
    $length = $length === NULL ? filesize($this->path)  : $length;
    // file_get_contents is more efficient than fread
    return fread($this->handle, $length);
  }

  public  function eof()
  {
    $this->ensureFileIsOpended();
    return feof($this->handle);
  }

  public  function tell()
  {
    $this->ensureFileIsOpended();
    return ftell($this->handle);
  }

  public  function seek(int $flags, $whence = SEEK_SET): int
  {
    $this->ensureFileIsOpended();
    return fseek($this->handle, $flags, $whence);
  }

  public  function stat(): array
  {
    $this->ensureFileIsOpended();
    return fstat($this->handle);
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

  public function content()
  {
    return file_get_contents($this->path, $this->useIncludePath, $this->context);
  }

  public  function readLine($length = NULL)
  {
    $this->ensureCanRead();
    $this->ensureReadLengthNotZero($length);
    $length = $length === NULL ? filesize($this->path)  : $length;
    return fgets($this->handle, $length);
  }

  public  function readLineAsync(int $length = 0)
  {
    $this->ensureCanRead();
    $this->ensureReadLengthNotZero($length);
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

  /**
   * @param $flags 
   * FILE_USE_INCLUDE_PATH: Search for the file in the include_path.
   * FILE_IGNORE_NEW_LINES : Omit newline at the end of each array element 
   * FILE_SKIP_EMPTY_LINES : Skip empty lines 
   */
  public function asArray(int $flags = 0)
  {
    $flags = $this->useIncludePath ? FILE_USE_INCLUDE_PATH : $flags;
    return file($this->path, $flags, $this->context);
  }

  public  function write(string $string, int $length = PHP_INT_MAX): int
  {
    $this->ensureCanWrite();
    return \fwrite($this->handle, $string, $length);
  }
  /**
   * FILE_USE_INCLUDE_PATH 	Search for filename in the include directory. 
   * FILE_APPEND 	If file filename already exists, append the data 
   * to the file instead of overwriting it.
   * LOCK_EX 	Acquire an exclusive lock on the file while proceeding to the writing.
   * @param $data Can be either a string, an array or a stream resource.
   */
  public function setContent($data, int $flags = 0)
  {
    $flags = $this->useIncludePath ? FILE_USE_INCLUDE_PATH : $flags;
    return file_put_contents($this->path, $data, $flags, $this->context);
  }

  public  function flush(): bool
  {
    $this->ensureCanWrite();
    return fflush($this->handle);
  }

  /* move a file or a entire dir using rename func */
  public function moveTo(string $newPath): bool
  {
    return rename($this->path, $newPath, $this->context);
  }

  public function rename(string $newPath): bool
  {
    return rename($this->path, $newPath, $this->context);
  }

  public  function copy(string $newPath): bool
  {
    // $newPath = Path::join($newPath, $this->path);
    if (file_exists($newPath)) {
      throw new \Exception("target path $newPath File already exists");
      return false;
    }
    return @copy($this->path, $newPath);
  }

  public  function delete(): bool
  {
    return unlink($this->path);
  }

  public  function size()
  {
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

  public function ensureCanWrite()
  {
    $this->ensureFileIsOpended();
    $this->ensureWriteMode();
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

  public function ensureReadLengthNotZero($length)
  {
    if ($length === 0) {
      throw new FileException("length to read file cannot be 0", 1);
    }
  }

  public function ensureWriteMode()
  {
    if ($this->mode == 'r') {
      throw new InvalidModeException("invalid Mode '$this->mode' provided to write file", 1);
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
