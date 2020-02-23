<?php

namespace Core\Database;

class DatabaseModel
{
    /**
     * @var string
     */
    protected $tableName = "";
    /**
     * @var array
     */
    protected $column = [];

    /**
     * DatabaseModel constructor.
     * @param $tableName
     */
    public function __construct($tableName)
    {
        $this->setTableName($tableName);

    }

    /**
     * @param string $tableName
     * @return $this
     */
    public function setTableName(string $tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * Add column to model
     * @param DatabaseModelColumn $column
     * @return DatabaseModelColumn
     */
    public function addColumn(DatabaseModelColumn $column = null)
    {
        if (is_null($column))
            $column = new DatabaseModelColumn();
        $this->column[] = $column;
        return $column;
    }

    /**
     * Add integer column to model
     * @param string $name
     * @param int $length
     * @return DatabaseModelColumn
     */
    public function integer(string $name, int $length = 11)
    {
        $column = new DatabaseModelColumn($name);
        $column->setType("int")->setLength($length);
        return $this->addColumn($column);
    }

    /**
     * Add increment column to model
     * @param string $name
     * @return DatabaseModelColumn
     */
    public function increment(string $name)
    {
        $column = new DatabaseModelColumn($name);
        $column->setType("int")->setLength(11)->nullable(false)->autoIncrement()->primaryKey();
        return $this->addColumn($column);
    }

    /**
     * Add text column to model
     * @param string $name
     * @return DatabaseModelColumn
     */
    public function text(string $name)
    {
        $column = new DatabaseModelColumn($name);
        $column->setType("text");
        return $this->addColumn($column);
    }

    /**
     * Add boolean column to model
     * @param string $name
     * @return DatabaseModelColumn
     */
    public function boolean(string $name)
    {
        $column = new DatabaseModelColumn($name);
        $column->setType("bool");
        return $this->addColumn($column);
    }

    /**
     * Add string column to model
     * @param string $name
     * @param int $length
     * @return DatabaseModelColumn
     */
    public function string(string $name, int $length = 255)
    {
        $column = new DatabaseModelColumn($name);
        $column->setType("varchar")->setLength($length);
        return $this->addColumn($column);
    }

    /**
     * Add double column to model
     * @param string $name
     * @param int $length
     * @param int $decimal
     * @return DatabaseModelColumn
     */
    public function double(string $name, int $length = 10, int $decimal = 2)
    {
        $column = new DatabaseModelColumn($name);
        $column->setType("double")->setLength($length)->setDecimal($decimal);
        return $this->addColumn($column);
    }

    /**
     * Add char column to model
     * @param string $name
     * @param int $length
     * @return DatabaseModelColumn
     */
    public function char(string $name, int $length = 1)
    {
        $column = new DatabaseModelColumn($name);
        $column->setType("char")->setLength($length);
        return $this->addColumn($column);
    }

    /**
     * Add blob column to model
     * @param string $name
     * @param int $length
     * @return DatabaseModelColumn
     */
    public function blob(string $name, int $length = 1000)
    {
        $column = new DatabaseModelColumn($name);
        $column->setType("blob")->setLength($length);
        return $this->addColumn($column);
    }

    /**
     * Add date column to model
     * @param string $name
     * @return DatabaseModelColumn
     */
    public function date(string $name)
    {
        $column = new DatabaseModelColumn($name);
        $column->setType("DATE");
        return $this->addColumn($column);
    }

    /**
     * Add date column to model
     * @param string $name
     * @return DatabaseModelColumn
     */
    public function time(string $name)
    {
        $column = new DatabaseModelColumn($name);
        $column->setType("time");
        return $this->addColumn($column);
    }

    /**
     * Add dateTime column to model
     * @param string $name
     * @return DatabaseModelColumn
     */
    public function dateTime(string $name)
    {
        $column = new DatabaseModelColumn($name);
        $column->setType("DATETIME");
        return $this->addColumn($column);
    }

    /**
     * Add timestamp column to model
     * @param string $name
     * @return DatabaseModelColumn
     */
    public function timestamp(string $name)
    {
        $column = new DatabaseModelColumn($name);
        $column->setType("TIMESTAMP");
        return $this->addColumn($column);
    }

    /**
     * Get sql query for create this model
     * @return string
     */
    public function getFullSQLQuery()
    {
        $sql = "CREATE TABLE IF NOT EXISTS $this->tableName (";
        foreach ($this->column as $column) {
            $sql .= $column->getSQLQuery() . ",";
        }
        $sql = substr($sql, 0, -1);
        $sql .= ")";
        return $sql;
    }

}
