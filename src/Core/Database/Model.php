<?php

namespace Core\Database;

use Serializable;

class Model implements Serializable
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
     * @return \Core\Database\Database
     */
    protected static function getDatabase(): \Core\Database\Database
    {
        return $GLOBALS['app']->getDatabase()->setClassName(get_called_class());
    }

    /**
     * Get table name.
     * @return string
     */
    protected static function getTableName(): string
    {
        $tableName = static::$tableName or array_slice(explode("\\", get_called_class()), -1, 1)[0];
        return $tableName;
    }

    /**
     * Find model's objects
     * @param $condition
     * @return array
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
        if (isset($this->id))
            return static::getDatabase()->insert(static::getTableName(), $this->data);
        else
            return static::getDatabase()->update(static::getTableName(), $this->data, ['id', $this->id]);
    }

    /**
     * Delete model's object
     */
    public function delete()
    {
        return static::getDatabase()->delete(static::getTableName(), ['id', $this->id]);
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize($this->data);
    }

    /**
     * @param string $data
     */
    public function unserialize($data)
    {
        $this->data = unserialize($data);
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
     * @return false|string
     */
    public function __toString()
    {
        return json_encode($this->data);
    }

}