<?php // Vue C4 : passagers ayant reserve un trajet actif du conducteur. ?>
<section>
    <h2>Liste des passagers de l’un de mes trajets actifs</h2>
    <?php if ($errors): ?>
        <div class="flash error">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!$activeTrips): ?>
        <p class="muted">Aucun trajet actif à sélectionner.</p>
    <?php else: ?>
        <form class="form compact" method="post" action="<?= e(url_for('driver_trip_passengers')) ?>">
            <label for="trajet_id">Sélectionnez l’un de mes trajets actifs</label>
            <select id="trajet_id" name="trajet_id" required>
                <option value="">-- choisir --</option>
                <?php foreach ($activeTrips as $trip): ?>
                    <option value="<?= e($trip['id']) ?>">
                        <?= e($trip['depart'] . ' --> ' . $trip['destination'] . ' le ' . $trip['date_depart'] . ' à ' . $trip['heure_depart']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button class="button" type="submit">Submit form</button>
        </form>
    <?php endif; ?>

    <?php if ($selectedTrip): ?>
        <h3>Passagers du trajet <?= e($selectedTrip['depart'] . ' --> ' . $selectedTrip['destination']) ?></h3>
        <?php if (!$passengers): ?>
            <p class="muted">Aucune réservation pour ce trajet actif.</p>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>nom</th>
                        <th>prenom</th>
                        <th>login</th>
                        <th class="number">places réservées</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($passengers as $passenger): ?>
                        <tr>
                            <td><?= e($passenger['nom']) ?></td>
                            <td><?= e($passenger['prenom']) ?></td>
                            <td><?= e($passenger['login']) ?></td>
                            <td class="number"><?= e($passenger['places']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</section>
