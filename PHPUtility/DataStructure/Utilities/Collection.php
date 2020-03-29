<?php

namespace PHPUtility\DataStructure\Utilities;

class Collection implements CollectionInterface
{
    private array $data = [];
    private int $position = 0;
    public function __construct($key = null, $value = null)
    {
        if ($key) {
            $this->push($key, $value);
        }
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function empty(): bool
    {
        return count($this->data) == 0;
    }

    public function push($key, $value = null)
    {
        // insert key value pair 
        if ($value !== null) {
            $this->data[$key] = $value;
            return;
        }
        // key is the value insert it in the data
        array_push($this->data, $key);
    }

    public function pushArray(array $array)
    {
        $this->data = array_merge($this->data, $array);
    }

    public function first()
    {
        return $this->data[array_key_first($this->data)] ?? null;
    }

    public function last()
    {
        return $this->data[array_key_last($this->data)] ?? null;
    }

    public function get($key)
    {
        return $this->data[$key] ?? null;
    }

    public function has($key, $value): bool
    {
        return $this->hasKey($key) && $this->data[$key] == $value;
    }

    public function hasKey($key): bool
    {
        return array_key_exists($key, $this->data);
    }

    public function prepend($key, $value = null)
    {
        if ($value !== null) {
            $this->data = array($key => $value) + $this->data;
            return;
        }
        array_unshift($this->data, $key);
    }

    public function prependArray(array $values)
    {
        $this->data = $values + $this->data;
    }

    public function pop()
    {
        return array_pop($this->data);
    }

    public function shift()
    {
        return array_shift($this->data);
    }

    public function remove($value)
    {
        return array_diff($this->data, [$value]);
    }

    public function search($value, bool $strict = false): bool
    {
        return array_search($value, $this->data, $strict);
    }

    public function all(): array
    {
        return $this->data;
    }

    public function clear()
    {
        unset($this->data);
        $this->data = [];
    }

    public function map(callable $callback): array
    {
        // array_map preserce keys now 
        return array_map($callback, $this->data);
    }

    public function filter(callable $callback): array
    {
        // array_map preserce keys now 
        return array_filter($this->data, $callback);
    }

    public function reduce(callable $callback, $intialValue = NULL)
    {
        return array_reduce($this->data, $callback, $intialValue);
    }

    public function every(callable $callback)
    {
        foreach ($this->data as $key => $value) {
            if (!$callback($key, $value)) {
                return false;
            }
        }
        return true;
    }

    public function forEach(callable $callback)
    {
        foreach ($this->data as $key => $value) {
            $callback($key, $value);
        }
    }

    /* Methods of ArrayAccess */
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        if (isset($this->data[$offset])) {
            return $this->data[$offset];
        }
    }

    public function offsetSet($offset,  $value): void
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    /**iterator methods */
    public function rewind()
    {
        reset($this->data);
    }

    public function current()
    {
        return current($this->data);
    }

    public function key()
    {
        return  key($this->data);
    }

    public function next()
    {
        next($this->data);
    }

    public function valid()
    {
        return  key($this->data) !== null;
    }
}
