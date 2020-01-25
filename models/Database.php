<?php

class Database {

    public static $pdo = null;
    private static $dbuser = 'root';
    private static $dbpass = '';
    private static $dbname = 'test';

    /**
     * execute pdo query with bind values
     * @param string $sqlQuery
     * @param array $placeholders
     * @return PDO object
     */
    public static function test($sqlQuery, $placeholders = []) {
        if (is_null(self::$pdo)) {
            self::connect();
        }

        $result = self::$pdo->prepare($sqlQuery);

        foreach ($placeholders as $key => $value) {
            if (is_bool($value)) {
                $type = PDO::PARAM_BOOL;
            } elseif (is_null($value)) {
                $type = PDO::PARAM_NULL;
            } elseif (is_int($value)) {
                $type = PDO::PARAM_INT;
            } else {
                $type = PDO::PARAM_STR;
            }

            $result->bindValue($key + 1, $value, $type);
        }

        return $result;
    }

    /**
     * try to connect to database
     * @return nothing
     */
    private static function connect() {
        if (is_null(self::$pdo)) {
            self::$pdo = new PDO('mysql:dbname=' . self::$dbname, self::$dbuser, self::$dbpass);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

}
