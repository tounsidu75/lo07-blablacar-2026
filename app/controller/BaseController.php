<?php

declare(strict_types=1);

abstract class BaseController
{
    protected UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    protected function currentUser(): ?array
    {
        // On repart de l'id en session, puis on recharge l'utilisateur en base.
        $loginId = (int) ($_SESSION['login_id'] ?? -1);
        if ($loginId < 0) {
            return null;
        }

        $user = $this->users->findById($loginId);
        if (!$user) {
            $_SESSION['login_id'] = -1;
            return null;
        }

        return $user;
    }

    protected function requireLogin(): array
    {
        $user = $this->currentUser();
        if (!$user) {
            set_flash('warning', 'Vous devez vous connecter pour accéder à cette page.');
            redirect_to('login');
        }

        return $user;
    }

    protected function requireRole(array $roles): array
    {
        // Les droits sont controles ici, pas seulement caches dans le menu.
        $user = $this->requireLogin();
        if (!in_array($user['role'], $roles, true)) {
            http_response_code(403);
            $this->render('error.php', [
                'title' => 'Accès refusé',
                'heading' => 'Accès refusé',
                'message' => 'Votre rôle ne permet pas d’accéder à cette fonctionnalité.',
            ]);
            exit;
        }

        return $user;
    }

    protected function render(string $view, array $data = []): void
    {
        // Toutes les pages gardent le meme header, le meme footer et les flash messages.
        $viewFile = dirname(__DIR__) . '/view/' . $view;
        if (!is_file($viewFile)) {
            throw new RuntimeException('Vue introuvable : ' . $view);
        }

        $currentUser = $this->currentUser();
        $flash = consume_flash();
        $title = $data['title'] ?? APP_TITLE;
        extract($data, EXTR_SKIP);

        require dirname(__DIR__) . '/view/fragments/header.php';
        require $viewFile;
        require dirname(__DIR__) . '/view/fragments/footer.php';
    }

    protected function isPost(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
    }

    protected function postString(string $name): string
    {
        return trim((string) ($_POST[$name] ?? ''));
    }

    protected function postInt(string $name): int
    {
        return (int) ($_POST[$name] ?? 0);
    }

    protected function postFloat(string $name): float
    {
        return (float) str_replace(',', '.', (string) ($_POST[$name] ?? '0'));
    }
}
