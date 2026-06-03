<?php

declare(strict_types=1);

final class ExamController extends BaseController
{
    private ReservationModel $reservations;

    public function __construct()
    {
        parent::__construct();
        $this->reservations = new ReservationModel();
    }

    public function superglobals(): void
    {
        $this->render('exam/superglobals.php', [
            'title' => 'SuperGlobales',
            'cookies' => $_COOKIE,
            'session' => $_SESSION,
        ]);
    }

    public function randomReservations(): void
    {
        $messages = $this->reservations->addTenRandomActiveReservations();
        $this->render('exam/random_reservations.php', [
            'title' => '10 nouvelles réservations aléatoires',
            'messages' => $messages,
        ]);
    }
}
