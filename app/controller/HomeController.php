<?php

declare(strict_types=1);

final class HomeController extends BaseController
{
    public function home(): void
    {
        // Page d'accueil commune, visible connecte ou non connecte.
        $this->render('home.php', [
            'title' => 'Accueil',
        ]);
    }

    public function notFound(): void
    {
        // Page d'erreur pour une action inconnue dans router2.
        http_response_code(404);
        $this->render('error.php', [
            'title' => 'Page introuvable',
            'heading' => 'Page introuvable',
            'message' => 'Cette action n’existe pas dans le routeur.',
        ]);
    }
}
