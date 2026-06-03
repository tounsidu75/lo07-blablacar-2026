<section>
    <h2>Liste des villes</h2>
    <?php if (!$cities): ?>
        <p class="muted">Aucune ville.</p>
    <?php else: ?>
        <div class="city-grid">
            <?php foreach ($cities as $city): ?>
                <span><?= e($city['nom']) ?></span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
