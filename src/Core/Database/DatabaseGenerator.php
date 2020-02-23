<?php

namespace Core\Database;


class DatabaseGenerator
{
    /**
     * @var Database
     */
    protected static $database = null;

    /**
     * Create and return database for migrations
     * @return Database
     */
    protected static function getDatabase(): Database
    {
        if (is_null(self::$database)) {
            $file = file_get_contents(__DIR__ . '/../../../../../../config.json');
            $config = collect(json_decode($file, true));
            self::$database = new Database($config["dbHost"],
                $config["dbName"],
                $config["dbUser"],
                $config["dbPassword"]);
        }

        return self::$database;
    }

    /**
     * @param string $tableName
     * @param callable $func
     * @return array|string
     */
    public static function registerModel(string $tableName, callable $func)
    {
        $model = call_user_func($func, new DatabaseModel($tableName));
        if (is_array($errors = self::getDatabase()->runCommand($model->getFullSQLQuery()))) {
            print_r($errors);
            die();
        }
        echo "$tableName successful added to database.";
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
