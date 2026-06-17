<?php

declare(strict_types=1);

final class CityModel
{
    public function all(): array
    {
        // Liste des villes pour l'administration et les formulaires trajet.
        return Database::connection()
            ->query('SELECT id, nom FROM ville ORDER BY nom')
            ->fetchAll();
    }

    public function add(string $nom): int
    {
        // Ajout d'une ville avec un id calcule comme dans les autres tables.
        $id = Database::nextId('ville');
        $stmt = Database::connection()->prepare('INSERT INTO ville (id, nom) VALUES (:id, :nom)');
        $stmt->execute([
            'id' => $id,
            'nom' => $nom,
        ]);

        return $id;
    }
}
