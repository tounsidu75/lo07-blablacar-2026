<?php

declare(strict_types=1);

final class PassengerController extends BaseController
{
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
        $passenger = $this->requireRole(['passager']);
        $this->render('passenger/my_reservations.php', [
            'title' => 'Liste de mes réservations',
            'reservations' => $this->reservations->forPassenger((int) $passenger['id']),
        ]);
    }

    public function reserve(): void
    {
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
