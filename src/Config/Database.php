<?php
namespace App\Config;

use PDO;

class Database {
    private static $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $dsn = "pgsql:host=" . $_ENV['DB_HOST'] . ";" .
                   "dbname=" . $_ENV['DB_NAME'] . ";" .
                   "user=" . $_ENV['DB_USER'] . ";" .
                   "password=" . $_ENV['DB_PASSWORD'] . ";" .
                   "port=" . $_ENV['DB_PORT'];

            self::$instance = new PDO($dsn);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$instance;
    }
}