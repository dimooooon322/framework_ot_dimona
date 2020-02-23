<?php

namespace Core\Database;

use Core\Support\Collection;
use PDO;
use PDOStatement;

class Database
{
    /**
     * @var PDO
     */
    protected $pdo;
    /**
     * @var string
     */
    protected $className;

    /**
     * Database constructor.
     * @param string $host
     * @param string $databaseName
     * @param string $user
     * @param string $password
     */
    public function __construct(string $host, string $databaseName, string $user, string $password)
    {
        $this->pdo = new PDO("mysql:host=$host;dbname=$databaseName;",
            $user,
            $password
        );
    }

    /**
     * @param string $command
     * @return PDOStatement|array
     */
    public function runCommand(string $command)
    {
        if(!$result = $this->pdo->query($command))
            return $this->pdo->errorInfo();
        return $result;
    }

    /**
     * @param $className
     * @return $this
     */
    public function setClassName($className)
    {
        $this->className = $className;
        return $this;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className ?? 'stdClass';
    }

    /**
     * Select data from table
     * @param string $tableName
     * @param array $cols
     * @param array $condition [optional]
     * @return Collection
     */
    public function select(string $tableName, array $cols = ["*"], array $condition = [])
    {
        $cols = implode(",", array_values($cols));
        if (!empty($condition)) {
            $conditionQuery = $this->getWhereQuery($condition);
            $query = $this->pdo->prepare("SELECT $cols FROM $tableName $conditionQuery");
            $query->execute(array(is_array($condition[1]) ? array_values($condition[1]) : $condition[1]));
        } else {
            $query = $this->pdo->prepare("SELECT $cols FROM $tableName");
            $query->execute();
        }
        return (new Collection($query->fetchAll(PDO::FETCH_CLASS, $this->getClassName())));
    }

    /**
     * Insert data into table.
     * Array $data should look like
     *  [
     *      "colName1" => "foo",
     *      ...,
     *      "colNameN" => "bar"
     *  ]
     * @param string $tableName
     * @param array $data
     * @return bool
     */
    public function insert(string $tableName, array $data)
    {
        array_walk($data, "escapeValues");
        $cols = implode(",", array_keys($data));
        $values = implode(",", array_fill(0, count($data), "?"));
        $query = $this->pdo->prepare("INSERT INTO $tableName ($cols)  VALUES ($values) ");
        return $query->execute(array_values($data));
    }

    /**
     * Update data in table.
     * Array $data should look like
     *  [
     *      "colName1" => "foo",
     *      ...,
     *      "colNameN" => "bar"
     *  ]
     * Array $condition should look like
     *  [
     *      "columnWhereFind",
     *      "valueWhatFind",
     *      "operatorFind"
     *  ]
     * @param string $tableName
     * @param array $data
     * @param array $condition
     * @return bool
     */
    public function update(string $tableName, array $data, array $condition)
    {
        array_walk($data, "escapeValues");
        $updateQuery = "";
        foreach ($data as $col => $value) {
            $updateQuery .= $col . "=?,";
        }
        $updateQuery = substr($updateQuery, 0, -1);
        $conditionQuery = $this->getWhereQuery($condition);
        $query = $this->pdo->prepare("UPDATE $tableName SET $updateQuery $conditionQuery");
        return $query->execute(array_merge(array_values($data), array_values($condition[1])));
    }

    /**
     * Delete data from table.
     * Array $condition should look like
     *  [
     *      "columnWhereFind",
     *      "valueWhatFind",
     *      "operatorFind"
     *  ]
     * @param string $tableName
     * @param array $condition [optional]
     * @return bool
     */
    public function delete(string $tableName, array $condition)
    {
        $conditionQuery = $this->getWhereQuery($condition);
        $query = $this->pdo->prepare("DELETE FROM $tableName $conditionQuery");
        return $query->execute(array_values($condition[1]));
    }

    /**
     * Generating 'where query' from input condition
     * @param array $condition
     * @return string
     */
    protected function getWhereQuery(array $condition)
    {
        $conditionCol = $condition[0];
        switch (strtoupper($condition[2])) {
            case "BETWEEN":
                $conditionVal = "? and ?";
                $conditionOperator = "BETWEEN";
                break;
            case "IN":
                $conditionVal = "(" . implode(",", array_fill(0, count($condition[1]), "?")) . ")";
                $conditionOperator = "IN";
                break;
            default:
                $conditionVal = "?";
                $conditionOperator = $condition[2] ?? "=";
                break;
        }
        return "WHERE $conditionCol $conditionOperator $conditionVal";
    }

}