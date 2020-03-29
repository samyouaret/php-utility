<?php

namespace PHPUtility\DataStructure\Utilities;

class Collection implements CollectionInterface
{
    private array $data = [];

    public function __construct($key = null, $value = null)
    {
        if ($key) {
            is_array($key) ? $this->pushArray($key) : $this->push($key, $value);
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
    /**
     * @param $key type mixed
     * @param $value mixed 
     */
    public function push($key, $value = null)
    {
        // insert key value pair 
        if ($value !== null) {
            $this->data[$key] = $value;
            return;
        }
        // if value is missed the value is key 
        array_push($this->data, $key);
        return $this;
    }

    public function pushArray(array $array)
    {
        $this->data = array_merge($this->data, $array);
        return $this;
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
        return $this;
    }

    public function prependArray(array $values)
    {
        $this->data = $values + $this->data;
        return $this;
    }

    public function pop()
    {
        return array_pop($this->data);
    }

    public function shift()
    {
        return array_shift($this->data);
    }

    public function remove($value): Collection
    {
        $array = array_diff($this->data, [$value]);
        return new Collection($array);
    }

    public function replace(array $array)
    {
        $array = array_replace($this->data, $array);
        return new Collection($array);
    }

    public function search($value, bool $strict = false): bool
    {
        return in_array($value, $this->data, $strict);
    }

    public function find(callable $callback)
    {
        foreach ($this->data as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }
        return null;
    }

    public function values(): array
    {
        return array_values($this->data);
    }

    public function keys(): array
    {
        return array_keys($this->data);
    }

    public function merge(Collection $collection): Collection
    {
        $array =  array_merge($this->data, $collection->all());
        return new Collection($array);
    }

    public function all(): array
    {
        return $this->data;
    }

    public function clear()
    {
        unset($this->data);
        $this->data = [];
        return $this;
    }

    public function map(callable $callback): Collection
    {
        // array_map preserce keys now 
        $array = array_map($callback, $this->data);
        return new Collection($array);
    }

    public function filter(callable $callback): Collection
    {
        // array_map preserce keys now 
        $array =  array_filter($this->data, $callback);
        return new Collection($array);
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

    public function reverse(): Collection
    {
        $array =  array_reverse($this->data, true);
        return new Collection($array);
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
