<?php
// Script de depart demande par le sujet : il remet la session a "non connecte".
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

// -1 signifie qu'aucun utilisateur n'est connecte au lancement.
$_SESSION['login_id'] = -1;

// Toute la navigation passe ensuite par router2.php avec une action.
header('Location: app/router/router2.php?action=home');
exit;
