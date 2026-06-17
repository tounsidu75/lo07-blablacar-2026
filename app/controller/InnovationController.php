<?php

declare(strict_types=1);

final class InnovationController extends BaseController
{
    // Controleur des deux pages d'innovation demandees dans le sujet.
    private StatsModel $stats;

    public function __construct()
    {
        parent::__construct();
        $this->stats = new StatsModel();
    }

    public function data(): void
    {
        // Innovation data : transforme les donnees SQL en indicateurs.
        $this->render('innovation/data.php', [
            'title' => 'Innovation data',
            'dashboard' => $this->stats->dashboard(),
        ]);
    }

    public function mvc(): void
    {
        // Innovation MVC : explique les choix de structure du code.
        $this->render('innovation/mvc.php', [
            'title' => 'Innovation MVC',
        ]);
    }
}
