<?php

declare(strict_types=1);

final class ExamController extends BaseController
{
    // Fonctions publiques prevues pour faciliter la correction.
    private ReservationModel $reservations;

    public function __construct()
    {
        parent::__construct();
        $this->reservations = new ReservationModel();
    }

    public function superglobals(): void
    {
        // E1 : affiche les cookies et la session en cours.
        $this->render('exam/superglobals.php', [
            'title' => 'SuperGlobales',
            'cookies' => $_COOKIE,
            'session' => $_SESSION,
        ]);
    }

    public function randomReservations(): void
    {
        // E2 : ajoute 10 reservations aleatoires sur des trajets actifs.
        $messages = $this->reservations->addTenRandomActiveReservations();
        $this->render('exam/random_reservations.php', [
            'title' => '10 nouvelles réservations aléatoires',
            'messages' => $messages,
        ]);
    }
}
