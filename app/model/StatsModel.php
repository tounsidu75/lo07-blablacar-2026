<?php

declare(strict_types=1);

final class StatsModel
{
    public function dashboard(): array
    {
        // Requetes regroupees pour alimenter la page Innovation data.
        $pdo = Database::connection();

        // Indicateurs principaux : volumes, statuts des trajets et prix moyen.
        $counts = $pdo->query(
            "SELECT
                (SELECT COUNT(*) FROM utilisateur) AS users_count,
                (SELECT COUNT(*) FROM utilisateur WHERE role = 'conducteur') AS drivers_count,
                (SELECT COUNT(*) FROM utilisateur WHERE role = 'passager') AS passengers_count,
                (SELECT COUNT(*) FROM vehicule) AS vehicles_count,
                (SELECT COUNT(*) FROM ville) AS cities_count,
                (SELECT COUNT(*) FROM trajet WHERE statut = 'actif') AS active_trips_count,
                (SELECT COUNT(*) FROM trajet WHERE statut = 'passif') AS passive_trips_count,
                (SELECT COUNT(*) FROM reservation) AS reservations_count,
                (SELECT COALESCE(AVG(prix), 0) FROM trajet) AS average_price"
        )->fetch();

        // Trajets les plus reserves, en comptant chaque ligne reservation.
        $popularTrips = $pdo->query(
            "SELECT dep.nom AS depart, arr.nom AS destination, COUNT(r.id) AS reservations
             FROM reservation r
             JOIN trajet t ON t.id = r.trajet_id
             JOIN ville dep ON dep.id = t.ville_depart
             JOIN ville arr ON arr.id = t.ville_arrivee
             GROUP BY t.id, dep.nom, arr.nom
             ORDER BY reservations DESC, dep.nom, arr.nom
             LIMIT 5"
        )->fetchAll();

        // Montant potentiel par conducteur selon les reservations existantes.
        $driverIncome = $pdo->query(
            "SELECT CONCAT(u.prenom, ' ', u.nom) AS conducteur,
                    COALESCE(SUM(CASE WHEN r.id IS NULL THEN 0 ELSE t.prix END), 0) AS montant_reserve
             FROM utilisateur u
             LEFT JOIN trajet t ON t.conducteur_id = u.id
             LEFT JOIN reservation r ON r.trajet_id = t.id
             WHERE u.role = 'conducteur'
             GROUP BY u.id, u.prenom, u.nom
             ORDER BY montant_reserve DESC, conducteur
             LIMIT 5"
        )->fetchAll();

        // Villes les plus presentes en depart ou en arrivee.
        $busyCities = $pdo->query(
            "SELECT ville, SUM(total) AS total
             FROM (
                SELECT v.nom AS ville, COUNT(*) AS total
                FROM trajet t
                JOIN ville v ON v.id = t.ville_depart
                GROUP BY v.id, v.nom
                UNION ALL
                SELECT v.nom AS ville, COUNT(*) AS total
                FROM trajet t
                JOIN ville v ON v.id = t.ville_arrivee
                GROUP BY v.id, v.nom
             ) city_counts
             GROUP BY ville
             ORDER BY total DESC, ville
             LIMIT 8"
        )->fetchAll();

        return [
            'counts' => $counts,
            'popularTrips' => $popularTrips,
            'driverIncome' => $driverIncome,
            'busyCities' => $busyCities,
        ];
    }
}
