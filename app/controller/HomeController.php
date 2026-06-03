<?php

declare(strict_types=1);

final class HomeController extends BaseController
{
    public function home(): void
    {
        $this->render('home.php', [
            'title' => 'Accueil',
        ]);
    }

    public function notFound(): void
    {
        http_response_code(404);
        $this->render('error.php', [
            'title' => 'Page introuvable',
            'heading' => 'Page introuvable',
            'message' => 'Cette action n’existe pas dans le routeur.',
        ]);
    }
}
