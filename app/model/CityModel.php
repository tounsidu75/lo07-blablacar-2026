<?php

declare(strict_types=1);

final class CityModel
{
    public function all(): array
    {
        return Database::connection()
            ->query('SELECT id, nom FROM ville ORDER BY nom')
            ->fetchAll();
    }

    public function add(string $nom): int
    {
        $id = Database::nextId('ville');
        $stmt = Database::connection()->prepare('INSERT INTO ville (id, nom) VALUES (:id, :nom)');
        $stmt->execute([
            'id' => $id,
            'nom' => $nom,
        ]);

        return $id;
    }
}
