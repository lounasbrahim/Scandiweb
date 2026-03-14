<?php

namespace App;

use PDO;

class Database
{
    private static ?PDO $instance = null;

    public static function connect(): PDO
    {
        if (self::$instance !== null) {
            return self::$instance;
        }
        $host = getenv('MYSQLHOST') ?: getenv('MYSQL_HOST') ?: 'localhost';
        $db   = getenv('MYSQLDATABASE');
        $user = getenv('MYSQLUSER');
        $pass = getenv('MYSQLPASSWORD');
        $port = (int)(getenv('MYSQLPORT') ?: getenv('MYSQL_PORT') ?: 3306);
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        ];

        self::$instance = new PDO($dsn, $user, $pass, $options);
        return self::$instance;
    }
}
