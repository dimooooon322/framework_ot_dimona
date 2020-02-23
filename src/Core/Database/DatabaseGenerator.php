<?php

namespace Core\Database;


class DatabaseGenerator
{
    /**
     * Create and return database for migrations
     * @return Database
     */
    public static function getDatabase(): Database
    {
        $databaseConfig = config('database');
        return (new Database($databaseConfig['host'],
            $databaseConfig['databaseName'],
            $databaseConfig['user'],
            $databaseConfig['password']));
    }

    /**
     * @param string $tableName
     * @param callable $func
     * @return array|string
     */
    public static function registerModel(string $tableName, callable $func)
    {
        $model = call_user_func($func, new DatabaseModel($tableName));
        if (is_array($errors = self::getDatabase()->runCommand($model->getFullSQLQuery())))
            return $errors;
        return "$tableName successful added to database.";
    }

    /**
     * @param $tableName
     * @return mixed
     */
    protected static function getColumnsName($tableName)
    {
        $columns = [];
        $result = self::getDatabase()->runCommand("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$tableName' ORDER BY ORDINAL_POSITION");
        if (is_array($result))
            return $result;
        foreach ($result->fetchAll() as $value) {
            $columns[] = $value[0];
        }
        return $columns;
    }

}
