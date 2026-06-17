<?php // Page autonome pour afficher une erreur serveur non prevue. ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Erreur - <?= e(APP_TITLE) ?></title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
<main class="page">
    <section class="hero">
        <h1><?= e(APP_TITLE) ?></h1>
        <p><?= e(APP_SUBTITLE) ?></p>
    </section>
    <section class="panel danger">
        <h2>Erreur applicative</h2>
        <p><?= e(APP_DEBUG ? $message : 'Une erreur technique est survenue. Verifiez la configuration MySQL ou reessayez.') ?></p>
        <p class="muted">Vérifiez la configuration MySQL dans <code>app/config/config.php</code> et l’import du fichier SQL.</p>
    </section>
</main>
</body>
</html>
