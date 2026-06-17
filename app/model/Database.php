<?php

declare(strict_types=1);

final class Database
{
    private static ?PDO $pdo = null;

    public static function connection(): PDO
    {
        // Une seule connexion PDO est partagee pendant toute la requete.
        if (self::$pdo === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                DB_HOST,
                DB_PORT,
                DB_NAME
            );

            self::$pdo = new PDO($dsn, DB_USER, DB_PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }

        return self::$pdo;
    }

    public static function nextId(string $table): int
    {
        // Le SQL fourni n'utilise pas AUTO_INCREMENT : on calcule donc l'id suivant.
        if (!preg_match('/^[a-z_]+$/', $table)) {
            throw new InvalidArgumentException('Nom de table invalide.');
        }

        $stmt = self::connection()->query("SELECT COALESCE(MAX(id), 0) + 1 AS next_id FROM {$table}");
        return (int) $stmt->fetch()['next_id'];
    }
}
