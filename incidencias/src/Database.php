<?php
namespace App;

use PDO;
use PDOException;

class Database {
    private static ?PDO $pdo = null;

    public static function pdo(): PDO {
        if (self::$pdo) return self::$pdo;

        $host = Bootstrap::env('DB_HOST', '127.0.0.1');
        $port = Bootstrap::env('DB_PORT', '3306');
        $db   = Bootstrap::env('DB_NAME', 'incidencias_db');
        $user = Bootstrap::env('DB_USER', 'root');
        $pass = Bootstrap::env('DB_PASS', '');

        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try { self::$pdo = new PDO($dsn, $user, $pass, $opt); }
        catch(PDOException $e){ http_response_code(500); exit('Error DB: '.$e->getMessage()); }
        return self::$pdo;
    }
}

