<?php // Fragment commun : entete, menu dynamique et messages flash. ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?> - <?= e(APP_TITLE) ?></title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
<header class="topbar">
    <div class="topbar-inner">
        <a class="students" href="<?= e(url_for('home')) ?>"><?= e(APP_STUDENTS) ?></a>
        <?php if ($currentUser): ?>
            <span class="separator">|</span>
            <span class="identity"><?= e($currentUser['nom'] . ' ' . $currentUser['prenom']) ?></span>
            <span class="separator">|</span>
            <span class="balance"><?= e(money_format_fr($currentUser['solde'])) ?></span>
            <span class="separator">|</span>
        <?php endif; ?>

        <nav class="menu" aria-label="Navigation principale">
            <?php // Un seul menu metier est affiche selon le role connecte. ?>
            <?php if ($currentUser && $currentUser['role'] === 'administrateur'): ?>
                <div class="dropdown">
                    <button type="button">Administrateur</button>
                    <div class="dropdown-menu">
                        <a href="<?= e(url_for('admin_users')) ?>">Liste des utilisateurs</a>
                        <a href="<?= e(url_for('admin_add_driver')) ?>">Ajout d’un conducteur</a>
                        <a href="<?= e(url_for('admin_add_passenger')) ?>">Ajout d’un passager</a>
                        <span class="menu-rule"></span>
                        <a href="<?= e(url_for('admin_vehicles')) ?>">Liste des véhicules</a>
                        <a href="<?= e(url_for('admin_add_vehicle')) ?>">Ajout d’un véhicule</a>
                        <span class="menu-rule"></span>
                        <a href="<?= e(url_for('admin_cities')) ?>">Liste des villes</a>
                        <a href="<?= e(url_for('admin_add_city')) ?>">Ajout d’une ville</a>
                    </div>
                </div>
            <?php elseif ($currentUser && $currentUser['role'] === 'conducteur'): ?>
                <div class="dropdown">
                    <button type="button">Conducteur</button>
                    <div class="dropdown-menu wide">
                        <a href="<?= e(url_for('driver_vehicles')) ?>">Liste de mes véhicules</a>
                        <span class="menu-rule"></span>
                        <a href="<?= e(url_for('driver_trips')) ?>">Liste de tous mes trajets (actifs et passifs)</a>
                        <a href="<?= e(url_for('driver_add_trip')) ?>">Ajout d’un trajet</a>
                        <span class="menu-rule"></span>
                        <a href="<?= e(url_for('driver_trip_passengers')) ?>">Liste des passagers de l’un de mes trajets actifs</a>
                        <a href="<?= e(url_for('driver_close_trip')) ?>">Clôturer l’un de mes trajets actifs</a>
                    </div>
                </div>
            <?php elseif ($currentUser && $currentUser['role'] === 'passager'): ?>
                <div class="dropdown">
                    <button type="button">Passager</button>
                    <div class="dropdown-menu">
                        <a href="<?= e(url_for('passenger_reservations')) ?>">Liste de mes réservations</a>
                        <a href="<?= e(url_for('passenger_reserve')) ?>">Réservation d’un trajet actif</a>
                    </div>
                </div>
            <?php endif; ?>

            <?php // Menus publics toujours visibles : innovations, examinateur et connexion. ?>
            <div class="dropdown">
                <button type="button">Innovations</button>
                <div class="dropdown-menu">
                    <a href="<?= e(url_for('innovation_data')) ?>">Proposer une fonctionnalité originale</a>
                    <a href="<?= e(url_for('innovation_mvc')) ?>">Proposer une amélioration du code MVC</a>
                </div>
            </div>

            <div class="dropdown">
                <button type="button">Examinateur</button>
                <div class="dropdown-menu">
                    <a href="<?= e(url_for('exam_superglobals')) ?>">SuperGlobales (Cookies et Session)</a>
                    <a href="<?= e(url_for('exam_random_reservations')) ?>">Ajout de 10 réservations aléatoires</a>
                </div>
            </div>

            <div class="dropdown">
                <button type="button">Se connecter</button>
                <div class="dropdown-menu right">
                    <a href="<?= e(url_for('login')) ?>">Login</a>
                    <?php if ($currentUser): ?>
                        <a href="<?= e(url_for('logout')) ?>">Déconnexion</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </div>
</header>

<main class="page">
    <section class="hero">
        <h1><?= e(APP_TITLE) ?></h1>
        <p><?= e(APP_SUBTITLE) ?></p>
    </section>

    <?php foreach ($flash as $flashMessage): ?>
        <?php // Message de confirmation ou d'erreur apres une action. ?>
        <div class="flash <?= e($flashMessage['type']) ?>"><?= e($flashMessage['message']) ?></div>
    <?php endforeach; ?>
