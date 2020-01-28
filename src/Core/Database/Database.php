<?php

namespace Core\Database;

class Database
{
    protected $pdo;
    protected $className;

    public function __construct(string $host, string $databaseName, string $user, string $password)
    {
        $this->pdo = new \PDO("mysql:host=$host;dbname=$databaseName;",
            $user,
            $password
        );
    }

    protected function runCommand(string $command)
    {
        return $this->pdo->query($this->pdo->quote($command));
    }

    public function setClassName($className)
    {
        $this->className = $className;
        return $this;
    }

    public function getClassName()
    {
        return $this->className ?? 'std_class';
    }

    /**
     * @param string $tableName
     * @param array $cols
     * @return array
     */
    public function select(string $tableName, array $cols = ["*"])
    {
        $query = $this->pdo->prepare("SELECT :cols FROM :tableName");
        $query->execute(["cols" => implode(',', $cols), "tableName" => $tableName]);
        return $query->fetchAll(PDO::FETCH_CLASS, $this->getClassName());
    }

    /**
     * @param string $tableName
     * @param array $cols
     * @param array $values
     * @return bool
     */
    public function insert(string $tableName, array $cols, array $values)
    {
        $query = $this->pdo->prepare("INSERT INTO :tableName  VALUES (:values) ");
        return $query->execute(["cols" => implode(',', $cols), "values" => implode(',', $values), "tableName" => $tableName]);

    }

    public function update()
    {

    }

    public function delete()
    {

    }

}