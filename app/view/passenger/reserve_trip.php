<section>
    <h2>Réservation d’un trajet actif</h2>
    <?php if ($errors): ?>
        <div class="flash error">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!$activeTrips): ?>
        <p class="muted">Aucun trajet actif disponible.</p>
    <?php else: ?>
        <form class="form compact" method="post" action="<?= e(url_for('passenger_reserve')) ?>">
            <label for="trajet_id">Sélectionnez un trajet actif</label>
            <select id="trajet_id" name="trajet_id" required>
                <option value="">-- choisir --</option>
                <?php foreach ($activeTrips as $trip): ?>
                    <option value="<?= e($trip['id']) ?>">
                        <?= e($trip['depart'] . ' --> ' . $trip['destination'] . ' le ' . $trip['date_depart'] . ' à ' . $trip['heure_depart'] . ' - ' . money_format_fr($trip['prix'])) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button class="button" type="submit">Submit form</button>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>date_depart</th>
                    <th>heure_depart</th>
                    <th>depart</th>
                    <th>destination</th>
                    <th>conducteur</th>
                    <th>vehicule</th>
                    <th>immatriculation</th>
                    <th class="number">prix</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($activeTrips as $trip): ?>
                    <tr>
                        <td><?= e($trip['date_depart']) ?></td>
                        <td><?= e($trip['heure_depart']) ?></td>
                        <td><?= e($trip['depart']) ?></td>
                        <td><?= e($trip['destination']) ?></td>
                        <td><?= e($trip['conducteur']) ?></td>
                        <td><?= e($trip['vehicule']) ?></td>
                        <td><?= e($trip['immatriculation']) ?></td>
                        <td class="number"><?= e(money_format_fr($trip['prix'])) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
