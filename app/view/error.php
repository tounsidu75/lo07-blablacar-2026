<?php // Vue commune pour afficher une erreur controlee, par exemple un acces refuse. ?>
<section class="panel danger">
    <h2><?= e($heading ?? 'Erreur') ?></h2>
    <p><?= e($message ?? 'Une erreur est survenue.') ?></p>
</section>
