<?php

namespace App\Config;

use PDO;
use PDOException;
use App\Utils\Response;

class Database
{
    public static function connection(): PDO
    {
        // Dotenv -> $_ENV, Hosting env -> getenv()
        $host = $_ENV['DB_HOST'] ?? $_SERVER['DB_HOST'] ?? getenv('DB_HOST') ?? null;
        $db   = $_ENV['DB_NAME'] ?? $_SERVER['DB_NAME'] ?? getenv('DB_NAME') ?? null;
        $user = $_ENV['DB_USER'] ?? $_SERVER['DB_USER'] ?? getenv('DB_USER') ?? null;
        $pass = $_ENV['DB_PASS'] ?? $_SERVER['DB_PASS'] ?? getenv('DB_PASS') ?? '';

        if (!$host || !$db || !$user) {
            Response::error(500, 'Veritabanı ortam değişkenleri eksik.', [
                'missing_keys' => [
                    'DB_HOST' => (bool)$host,
                    'DB_NAME' => (bool)$db,
                    'DB_USER' => (bool)$user,
                ],
            ]);
        }

        try {
            $dsn = "mysql:host={$host};dbname={$db};charset=utf8mb4";
            return new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            Response::error(500, 'Veritabanı bağlantısı kurulamadı.', ['message' => $e->getMessage()]);
        }
    }
}
