<?php // Vue E2 : resultat des 10 reservations aleatoires ajoutees. ?>
<section>
    <h2>10 nouvelles réservations aléatoires</h2>
    <ol class="result-list">
        <?php foreach ($messages as $message): ?>
            <li>
                Nouvelle réservation sur le trajet
                <?= e($message['depart']) ?> --&gt; <?= e($message['destination']) ?>
                par <?= e($message['passenger']) ?>
            </li>
        <?php endforeach; ?>
    </ol>
</section>
