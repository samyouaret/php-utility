<?php

namespace PHPUtility\DataStructure\Utilities;

interface CollectionInterface extends \Countable, \Iterator, \ArrayAccess
{
    public function empty(): bool;
    public function push($key, $value);
    public function first();
    public function last();
    public function pop();
    public function remove($item);
    public function search($item);
    public function clear();
}
