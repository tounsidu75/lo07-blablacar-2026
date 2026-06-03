<?php

declare(strict_types=1);

define('APP_TITLE', 'Projet BlaBlaCar 2026');
define('APP_SUBTITLE', 'Mettez-vous bien avec le covoiturage au quotidien');
define('APP_STUDENTS', getenv('BLABLACAR_STUDENTS') ?: 'KHARRAZ Sami');
define('APP_DEPLOY_URL', getenv('BLABLACAR_DEPLOY_URL') ?: 'http://dev-isi.utt.fr/~kharrazs/lo07_tp/projet/');
define('APP_DEBUG', getenv('BLABLACAR_DEBUG') === '1');

define('DB_HOST', getenv('BLABLACAR_DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('BLABLACAR_DB_PORT') ?: '3306');
define('DB_NAME', getenv('BLABLACAR_DB_NAME') ?: 'blablacar2026');
define('DB_USER', getenv('BLABLACAR_DB_USER') ?: 'root');
define('DB_PASSWORD', getenv('BLABLACAR_DB_PASSWORD') ?: '');

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function money_format_fr($value): string
{
    return number_format((float) $value, 2, ',', ' ') . ' €';
}

function url_for(string $action, array $params = []): string
{
    $query = array_merge(['action' => $action], $params);
    return 'router2.php?' . http_build_query($query);
}

function redirect_to(string $action, array $params = []): void
{
    header('Location: ' . url_for($action, $params));
    exit;
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'][] = [
        'type' => $type,
        'message' => $message,
    ];
}

function consume_flash(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}
