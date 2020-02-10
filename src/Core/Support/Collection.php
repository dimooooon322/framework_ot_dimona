<?php

namespace Core\Support;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use ArrayIterator;
use Traversable;
use JsonSerializable;

class Collection implements ArrayAccess, Countable, JsonSerializable, IteratorAggregate
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * Collection constructor.
     * @param array $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * Get all of the items in the collection.
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * @param int $options
     * @return false|string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->all(), $options);
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->all());
    }

    /**
     * @return Traversable|void
     */
    public function getIterator()
    {
        return new ArrayIterator($this->all());
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->all();
    }
}