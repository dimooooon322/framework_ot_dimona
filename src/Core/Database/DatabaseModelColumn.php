<?php

namespace Core\Database;

class DatabaseModelColumn
{
    /**
     * @var string
     */
    protected $name = "";
    /**
     * @var string
     */
    protected $type = "varchar";
    /**
     * @var int
     */
    protected $length = 255;
    /**
     * @var int
     */
    protected $decimal = null;
    /**
     * @var mixed
     */
    protected $default = null;
    /**
     * @var bool
     */
    protected $unique = false;
    /**
     * @var bool
     */
    protected $autoIncrement = false;
    /**
     * @var bool
     */
    protected $unsigned = false;
    /**
     * @var bool
     */
    protected $null = true;
    /**
     * @var bool
     */
    protected $primaryKey = false;
    /**
     * @var string
     */
    protected $referenceTable = null;
    /**
     * @var string
     */
    protected $referenceColumn = null;
    /**
     * @var string
     */
    protected $deleteType = null;

    /**
     * DatabaseModelColumn constructor.
     * @param string $name
     */
    public function __construct(string $name = "")
    {
        $this->setName($name);
    }

    /**
     * @return $this
     */
    public function autoIncrement()
    {
        $this->autoIncrement = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function unsigned()
    {
        $this->unsigned = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function unique()
    {
        $this->unique = true;
        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function nullable(bool $value = true)
    {
        $this->null = $value;
        return $this;
    }

    /**
     * @return $this
     */
    public function primaryKey()
    {
        $this->primaryKey = true;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param int $length
     * @return $this
     */
    public function setLength(int $length)
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @param int $decimal
     * @return $this
     */
    public function setDecimal(int $decimal)
    {
        $this->decimal = $decimal;
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setDefault($value)
    {
        $this->default = $value;
        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $table
     * @param string $column
     * @return $this
     */
    public function setReference(string $table, string $column = "id")
    {
        $this->referenceColumn = $column;
        $this->referenceTable = $table;
        return $this;
    }

    /**
     * @param $type
     * @return $this
     */
    public function setDeleteType($type)
    {
        $this->deleteType = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getSQLQuery()
    {
        return "$this->name $this->type($this->length" .
            (!is_null($this->decimal) ? $this->decimal : "")
            . ")" .
            ($this->unsigned ? " UNSIGNED" : "") .
            ($this->null ? " NULL" : " NOT NULL") .
            ($this->autoIncrement ? " AUTO_INCREMENT" : "") .
            ($this->unique ? " UNIQUE" : "") .
            (!is_null($this->default) ? " DEFAULT $this->default" : "") .
            ($this->primaryKey ? " , PRIMARY KEY ($this->name)" : "") .
            (!is_null($this->referenceColumn) && !is_null($this->referenceTable) ? " , FOREIGN KEY ($this->name) REFERENCES $this->referenceTable($this->referenceColumn)" .
                (!is_null($this->deleteType) ? " ON DELETE $this->deleteType" : "") : "");
    }
}