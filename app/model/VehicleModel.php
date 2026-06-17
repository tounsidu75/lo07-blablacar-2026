<?php

declare(strict_types=1);

final class VehicleModel
{
    public function allWithOwner(): array
    {
        return Database::connection()->query(
            "SELECT v.marque, v.modele, v.annee, v.immatriculation,
                    CONCAT(u.prenom, ' ', u.nom) AS proprietaire
             FROM vehicule v
             JOIN utilisateur u ON u.id = v.proprietaire_id
             ORDER BY v.id"
        )->fetchAll();
    }

    public function byOwner(int $ownerId): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT id, marque, modele, annee, immatriculation
             FROM vehicule
             WHERE proprietaire_id = :owner_id
             ORDER BY id'
        );
        $stmt->execute(['owner_id' => $ownerId]);
        return $stmt->fetchAll();
    }

    public function add(string $marque, string $modele, int $annee, string $immatriculation, int $ownerId): int
    {
        $id = Database::nextId('vehicule');
        $stmt = Database::connection()->prepare(
            'INSERT INTO vehicule (id, marque, modele, annee, immatriculation, proprietaire_id)
             VALUES (:id, :marque, :modele, :annee, :immatriculation, :owner_id)'
        );
        $stmt->execute([
            'id' => $id,
            'marque' => $marque,
            'modele' => $modele,
            'annee' => $annee,
            'immatriculation' => $immatriculation,
            'owner_id' => $ownerId,
        ]);

        return $id;
    }
}
