<?php

declare(strict_types=1);

// Constantes generales affichees dans le menu, le titre et le README.
define('APP_TITLE', 'Projet BlaBlaCar 2026');
define('APP_SUBTITLE', 'Mettez-vous bien avec le covoiturage au quotidien');
define('APP_STUDENTS', getenv('BLABLACAR_STUDENTS') ?: 'KHARRAZ Sami');
define('APP_DEPLOY_URL', getenv('BLABLACAR_DEPLOY_URL') ?: 'http://dev-isi.utt.fr/~kharrazs/lo07_tp/projet/');
define('APP_DEBUG', getenv('BLABLACAR_DEBUG') === '1');

// Parametres MySQL, remplacables par variables d'environnement sur dev-isi.
define('DB_HOST', getenv('BLABLACAR_DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('BLABLACAR_DB_PORT') ?: '3306');
define('DB_NAME', getenv('BLABLACAR_DB_NAME') ?: 'blablacar2026');
define('DB_USER', getenv('BLABLACAR_DB_USER') ?: 'root');
define('DB_PASSWORD', getenv('BLABLACAR_DB_PASSWORD') ?: '');

function e(?string $value): string
{
    // Echappe les textes avant affichage HTML pour eviter les injections.
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function money_format_fr($value): string
{
    // Format lisible des soldes et des prix dans l'interface.
    return number_format((float) $value, 2, ',', ' ') . ' €';
}

function url_for(string $action, array $params = []): string
{
    // Genere les liens internes sous la forme router2.php?action=...
    $query = array_merge(['action' => $action], $params);
    return 'router2.php?' . http_build_query($query);
}

function redirect_to(string $action, array $params = []): void
{
    // Redirection courte utilisee apres connexion ou insertion reussie.
    header('Location: ' . url_for($action, $params));
    exit;
}

function set_flash(string $type, string $message): void
{
    // Message temporaire affiche une seule fois sur la page suivante.
    $_SESSION['flash'][] = [
        'type' => $type,
        'message' => $message,
    ];
}

function consume_flash(): array
{
    // Recupere les messages puis les supprime de la session.
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}
