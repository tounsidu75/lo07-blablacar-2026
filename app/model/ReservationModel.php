<?php

declare(strict_types=1);

final class ReservationModel
{
    public function forPassenger(int $passengerId): array
    {
        $stmt = Database::connection()->prepare(
            "SELECT t.date_depart, t.heure_depart, dep.nom AS depart, arr.nom AS destination,
                    CONCAT(c.prenom, ' ', c.nom) AS conducteur,
                    CONCAT(v.marque, ' ', v.modele) AS vehicule, v.immatriculation,
                    t.prix, t.statut
             FROM reservation r
             JOIN trajet t ON t.id = r.trajet_id
             JOIN ville dep ON dep.id = t.ville_depart
             JOIN ville arr ON arr.id = t.ville_arrivee
             JOIN utilisateur c ON c.id = t.conducteur_id
             JOIN vehicule v ON v.id = t.vehicule_id
             WHERE r.passager_id = :passenger_id
             ORDER BY t.date_depart DESC, t.heure_depart DESC, r.id DESC"
        );
        $stmt->execute(['passenger_id' => $passengerId]);
        return $stmt->fetchAll();
    }

    public function add(int $tripId, int $passengerId): int
    {
        $check = Database::connection()->prepare(
            "SELECT id FROM trajet WHERE id = :trip_id AND statut = 'actif'"
        );
        $check->execute(['trip_id' => $tripId]);
        if (!$check->fetch()) {
            throw new RuntimeException('Le trajet choisi n’est plus actif.');
        }

        $id = Database::nextId('reservation');
        $stmt = Database::connection()->prepare(
            'INSERT INTO reservation (id, trajet_id, passager_id)
             VALUES (:id, :trip_id, :passenger_id)'
        );
        $stmt->execute([
            'id' => $id,
            'trip_id' => $tripId,
            'passenger_id' => $passengerId,
        ]);

        return $id;
    }

    public function addTenRandomActiveReservations(): array
    {
        $pdo = Database::connection();
        $activeTrips = $pdo->query(
            "SELECT t.id, dep.nom AS depart, arr.nom AS destination
             FROM trajet t
             JOIN ville dep ON dep.id = t.ville_depart
             JOIN ville arr ON arr.id = t.ville_arrivee
             WHERE t.statut = 'actif'
             ORDER BY t.id"
        )->fetchAll();

        $passengers = $pdo->query(
            "SELECT id, nom, prenom
             FROM utilisateur
             WHERE role = 'passager'
             ORDER BY id"
        )->fetchAll();

        if (!$activeTrips || !$passengers) {
            throw new RuntimeException('Il faut au moins un trajet actif et un passager.');
        }

        $pdo->beginTransaction();
        try {
            $nextId = Database::nextId('reservation');
            $insert = $pdo->prepare(
                'INSERT INTO reservation (id, trajet_id, passager_id)
                 VALUES (:id, :trip_id, :passenger_id)'
            );
            $messages = [];

            for ($i = 0; $i < 10; $i++) {
                $trip = $activeTrips[random_int(0, count($activeTrips) - 1)];
                $passenger = $passengers[random_int(0, count($passengers) - 1)];

                $insert->execute([
                    'id' => $nextId + $i,
                    'trip_id' => (int) $trip['id'],
                    'passenger_id' => (int) $passenger['id'],
                ]);

                $messages[] = [
                    'depart' => $trip['depart'],
                    'destination' => $trip['destination'],
                    'passenger' => trim($passenger['prenom'] . ' ' . $passenger['nom']),
                ];
            }

            $pdo->commit();
            return $messages;
        } catch (Throwable $exception) {
            $pdo->rollBack();
            throw $exception;
        }
    }
}
