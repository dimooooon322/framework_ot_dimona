<?php

namespace Core\Database;

use Core\Support\Collection;
use JsonSerializable;

class Model implements JsonSerializable
{
    /**
     * @var array
     */
    protected $data;
    /**
     * @var string
     */
    protected static $tableName;

    /**
     * @return Database
     */
    protected static function getDatabase(): Database
    {
        return $GLOBALS['app']->getDatabase()->setClassName(get_called_class());
    }

    /**
     * Get table name.
     * @return string
     */
    protected static function getTableName(): string
    {
        return isset(static::$tableName) ? static::$tableName : array_slice(explode("\\", get_called_class()), -1, 1)[0];
    }

    /**
     * Find model's objects
     * @param $condition
     * @return Collection
     */
    public static function find($condition)
    {
        return static::getDatabase()->select(static::getTableName(), ['*'], $condition);
    }

    /**
     * Save model's object
     * @return bool
     */
    public function save()
    {
        if (!isset($this->id))
            return static::getDatabase()->insert(static::getTableName(), $this->data);
        else
            return static::getDatabase()->update(static::getTableName(), $this->data, ['id', $this->id]);
    }

    /**
     * Delete model's object
     * @return bool
     */
    public function delete()
    {
        return static::getDatabase()->delete(static::getTableName(), ['id', $this->id]);
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->data[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->data;
    }

    /**
     * @return false|string
     */
    public function __toString()
    {
        return json_encode($this->jsonSerialize());
    }

}