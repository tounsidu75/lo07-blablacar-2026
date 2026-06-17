<?php

declare(strict_types=1);

final class PassengerController extends BaseController
{
    // Controleur des fonctions P1 et P2 reservees aux passagers.
    private ReservationModel $reservations;
    private TripModel $trips;

    public function __construct()
    {
        parent::__construct();
        $this->reservations = new ReservationModel();
        $this->trips = new TripModel();
    }

    public function reservations(): void
    {
        // P1 : affiche toutes les reservations du passager connecte.
        $passenger = $this->requireRole(['passager']);
        $this->render('passenger/my_reservations.php', [
            'title' => 'Liste de mes réservations',
            'reservations' => $this->reservations->forPassenger((int) $passenger['id']),
        ]);
    }

    public function reserve(): void
    {
        // P2 : permet de reserver un trajet actif, doublons autorises.
        $passenger = $this->requireRole(['passager']);
        $activeTrips = $this->trips->allActiveWithDetails();
        $errors = [];

        if ($this->isPost()) {
            $tripId = $this->postInt('trajet_id');
            $activeIds = array_map('intval', array_column($activeTrips, 'id'));
            if (!in_array($tripId, $activeIds, true)) {
                $errors[] = 'Veuillez choisir un trajet actif.';
            }

            if (!$errors) {
                $this->reservations->add($tripId, (int) $passenger['id']);
                set_flash('success', 'Votre réservation a été créée.');
                redirect_to('passenger_reservations');
            }
        }

        $this->render('passenger/reserve_trip.php', [
            'title' => 'Réservation d’un trajet actif',
            'activeTrips' => $activeTrips,
            'errors' => $errors,
        ]);
    }
}
