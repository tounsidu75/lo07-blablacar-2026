<section>
    <h2>Clôturer l’un de mes trajets actifs</h2>
    <?php if ($errors): ?>
        <div class="flash error">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!$activeTrips): ?>
        <p class="muted">Aucun trajet actif à clôturer.</p>
    <?php else: ?>
        <form class="form compact" method="post" action="<?= e(url_for('driver_close_trip')) ?>">
            <label for="trajet_id">Sélectionnez l’un de mes trajets actifs</label>
            <select id="trajet_id" name="trajet_id" required>
                <option value="">-- choisir --</option>
                <?php foreach ($activeTrips as $trip): ?>
                    <option value="<?= e($trip['id']) ?>">
                        <?= e($trip['depart'] . ' --> ' . $trip['destination'] . ' le ' . $trip['date_depart'] . ' à ' . $trip['heure_depart']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button class="button danger-button" type="submit">Clôturer</button>
        </form>
    <?php endif; ?>

    <?php if ($result && $closedTrip): ?>
        <section class="panel success">
            <h3>Trajet clôturé</h3>
            <p>
                <?= e($closedTrip['depart'] . ' --> ' . $closedTrip['destination']) ?> :
                <?= e($result['reservations']) ?> réservation(s), total transféré
                <strong><?= e(money_format_fr($result['total'])) ?></strong>.
            </p>
        </section>
    <?php endif; ?>
</section>
