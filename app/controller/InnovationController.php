<?php

declare(strict_types=1);

final class InnovationController extends BaseController
{
    private StatsModel $stats;

    public function __construct()
    {
        parent::__construct();
        $this->stats = new StatsModel();
    }

    public function data(): void
    {
        $this->render('innovation/data.php', [
            'title' => 'Innovation data',
            'dashboard' => $this->stats->dashboard(),
        ]);
    }

    public function mvc(): void
    {
        $this->render('innovation/mvc.php', [
            'title' => 'Innovation MVC',
        ]);
    }
}
