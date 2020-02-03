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
    protected static $tableName = "games";

    /**
     * @return \Core\Database\Database
     */
    protected static function getDatabase(): \Core\Database\Database
    {
        return $GLOBALS['app']->getDatabase()->setClassName(get_called_class());
    }

    protected static function getTableName(): string
    {
        $tableName = static::$tableName or array_slice(explode("\\", get_called_class()), -1, 1)[0];
        return $tableName;
    }

    public static function find($condition)
    {
        return static::getDatabase()->select(static::getTableName(), ['*'], $condition);
    }

    public function save()
    {
        if (isset($this->id))
            return static::getDatabase()->insert(static::getTableName(), $this->data);
        else
            return static::getDatabase()->update(static::getTableName(), $this->data, ['id', $this->id]);
    }

    public function delete()
    {
        static::getDatabase()->delete(static::getTableName(), ['id', $this->id]);
    }

    public function serialize()
    {
        return serialize($this->data);
    }

    public function unserialize($data)
    {
        $this->data = unserialize($data);
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        return $this->data[$name];
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __toString()
    {
        return json_encode($this->data);
    }

}