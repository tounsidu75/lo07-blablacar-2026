<?php // Page d'accueil : elle adapte le texte selon l'utilisateur connecte. ?>
<section class="panel">
    <h2>Accueil</h2>
    <?php if ($currentUser): ?>
        <p>
            Vous êtes connecté en tant que
            <strong><?= e($currentUser['nom'] . ' ' . $currentUser['prenom']) ?></strong>
            avec le rôle <strong><?= e($currentUser['role']) ?></strong>.
        </p>
        <p>Sélectionnez une fonctionnalité dans le menu correspondant à votre rôle.</p>
    <?php else: ?>
        <p>Aucun utilisateur n’est connecté. Les menus Innovations, Examinateur et Se connecter restent disponibles.</p>
        <p><a class="button" href="<?= e(url_for('login')) ?>">Login</a></p>
    <?php endif; ?>
</section>
