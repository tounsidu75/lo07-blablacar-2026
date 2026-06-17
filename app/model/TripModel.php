<?php

declare(strict_types=1);

final class TripModel
{
    public function byDriver(int $driverId): array
    {
        // C2 : tous les trajets du conducteur, actifs et passifs.
        $stmt = Database::connection()->prepare(
            "SELECT t.id, dep.nom AS depart, arr.nom AS destination, t.prix, t.date_depart,
                    t.heure_depart, t.statut, CONCAT(v.marque, ' ', v.modele) AS vehicule,
                    v.immatriculation
             FROM trajet t
             JOIN ville dep ON dep.id = t.ville_depart
             JOIN ville arr ON arr.id = t.ville_arrivee
             JOIN vehicule v ON v.id = t.vehicule_id
             WHERE t.conducteur_id = :driver_id
             ORDER BY t.date_depart DESC, t.heure_depart DESC, t.id DESC"
        );
        $stmt->execute(['driver_id' => $driverId]);
        return $stmt->fetchAll();
    }

    public function activeByDriver(int $driverId): array
    {
        // Liste courte des trajets actifs pour les formulaires C4 et C5.
        $stmt = Database::connection()->prepare(
            "SELECT t.id, dep.nom AS depart, arr.nom AS destination, t.date_depart,
                    t.heure_depart, t.prix
             FROM trajet t
             JOIN ville dep ON dep.id = t.ville_depart
             JOIN ville arr ON arr.id = t.ville_arrivee
             WHERE t.conducteur_id = :driver_id AND t.statut = 'actif'
             ORDER BY t.date_depart, t.heure_depart, t.id"
        );
        $stmt->execute(['driver_id' => $driverId]);
        return $stmt->fetchAll();
    }

    public function allActiveWithDetails(): array
    {
        // P2 : trajets actifs visibles par un passager pour reserver.
        return Database::connection()->query(
            "SELECT t.id, dep.nom AS depart, arr.nom AS destination, t.date_depart,
                    t.heure_depart, t.prix, CONCAT(c.prenom, ' ', c.nom) AS conducteur,
                    CONCAT(v.marque, ' ', v.modele) AS vehicule, v.immatriculation
             FROM trajet t
             JOIN ville dep ON dep.id = t.ville_depart
             JOIN ville arr ON arr.id = t.ville_arrivee
             JOIN utilisateur c ON c.id = t.conducteur_id
             JOIN vehicule v ON v.id = t.vehicule_id
             WHERE t.statut = 'actif'
             ORDER BY t.date_depart, t.heure_depart, t.id"
        )->fetchAll();
    }

    public function add(
        int $villeDepart,
        int $villeArrivee,
        int $driverId,
        int $vehicleId,
        float $prix,
        string $dateDepart,
        string $heureDepart
    ): int {
        // C3 : tout nouveau trajet cree par un conducteur commence actif.
        $id = Database::nextId('trajet');
        $stmt = Database::connection()->prepare(
            "INSERT INTO trajet
                (id, ville_depart, ville_arrivee, conducteur_id, vehicule_id, prix, date_depart, heure_depart, statut)
             VALUES
                (:id, :ville_depart, :ville_arrivee, :conducteur_id, :vehicule_id, :prix, :date_depart, :heure_depart, 'actif')"
        );
        $stmt->execute([
            'id' => $id,
            'ville_depart' => $villeDepart,
            'ville_arrivee' => $villeArrivee,
            'conducteur_id' => $driverId,
            'vehicule_id' => $vehicleId,
            'prix' => $prix,
            'date_depart' => $dateDepart,
            'heure_depart' => $heureDepart,
        ]);

        return $id;
    }

    public function passengersForActiveDriverTrip(int $tripId, int $driverId): array
    {
        // C4 : compte le nombre de places reservees par passager.
        $stmt = Database::connection()->prepare(
            "SELECT u.nom, u.prenom, u.login, COUNT(*) AS places
             FROM trajet t
             JOIN reservation r ON r.trajet_id = t.id
             JOIN utilisateur u ON u.id = r.passager_id
             WHERE t.id = :trip_id AND t.conducteur_id = :driver_id AND t.statut = 'actif'
             GROUP BY u.id, u.nom, u.prenom, u.login
             ORDER BY u.nom, u.prenom"
        );
        $stmt->execute([
            'trip_id' => $tripId,
            'driver_id' => $driverId,
        ]);
        return $stmt->fetchAll();
    }

    public function activeDriverTripLabel(int $tripId, int $driverId): ?array
    {
        // Verifie qu'un trajet actif appartient bien au conducteur connecte.
        $stmt = Database::connection()->prepare(
            "SELECT t.id, dep.nom AS depart, arr.nom AS destination, t.date_depart, t.heure_depart, t.prix
             FROM trajet t
             JOIN ville dep ON dep.id = t.ville_depart
             JOIN ville arr ON arr.id = t.ville_arrivee
             WHERE t.id = :trip_id AND t.conducteur_id = :driver_id AND t.statut = 'actif'"
        );
        $stmt->execute([
            'trip_id' => $tripId,
            'driver_id' => $driverId,
        ]);
        $trip = $stmt->fetch();
        return $trip ?: null;
    }

    public function closeActiveTrip(int $tripId, int $driverId): array
    {
        $pdo = Database::connection();
        // La cloture touche le trajet et les soldes : tout doit reussir ensemble.
        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare(
                "SELECT id, prix
                 FROM trajet
                 WHERE id = :trip_id AND conducteur_id = :driver_id AND statut = 'actif'
                 FOR UPDATE"
            );
            $stmt->execute([
                'trip_id' => $tripId,
                'driver_id' => $driverId,
            ]);
            $trip = $stmt->fetch();

            if (!$trip) {
                throw new RuntimeException('Trajet actif introuvable pour ce conducteur.');
            }

            $reservationsStmt = $pdo->prepare(
                'SELECT passager_id FROM reservation WHERE trajet_id = :trip_id FOR UPDATE'
            );
            $reservationsStmt->execute(['trip_id' => $tripId]);
            $reservations = $reservationsStmt->fetchAll();

            $price = (float) $trip['prix'];
            $reservationCount = count($reservations);
            $total = $price * $reservationCount;

            // Une reservation = un paiement, meme si le meme passager reserve deux fois.
            $debitStmt = $pdo->prepare(
                'UPDATE utilisateur SET solde = solde - :prix WHERE id = :passager_id'
            );
            foreach ($reservations as $reservation) {
                $debitStmt->execute([
                    'prix' => $price,
                    'passager_id' => (int) $reservation['passager_id'],
                ]);
            }

            $creditStmt = $pdo->prepare(
                'UPDATE utilisateur SET solde = solde + :total WHERE id = :driver_id'
            );
            $creditStmt->execute([
                'total' => $total,
                'driver_id' => $driverId,
            ]);

            $closeStmt = $pdo->prepare("UPDATE trajet SET statut = 'passif' WHERE id = :trip_id");
            $closeStmt->execute(['trip_id' => $tripId]);

            $pdo->commit();

            return [
                'reservations' => $reservationCount,
                'total' => $total,
                'price' => $price,
            ];
        } catch (Throwable $exception) {
            $pdo->rollBack();
            throw $exception;
        }
    }
}
