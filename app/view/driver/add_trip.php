<section>
    <h2>Création d’un nouveau trajet</h2>
    <?php if ($errors): ?>
        <div class="flash error">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!$vehicles): ?>
        <p class="muted">Vous devez posséder au moins un véhicule pour créer un trajet.</p>
    <?php else: ?>
        <form class="form" method="post" action="<?= e(url_for('driver_add_trip')) ?>">
            <label for="ville_depart">Ville de départ</label>
            <select id="ville_depart" name="ville_depart" required>
                <option value="">-- choisir --</option>
                <?php foreach ($cities as $city): ?>
                    <option value="<?= e($city['id']) ?>" <?= (string) $city['id'] === (string) $form['ville_depart'] ? 'selected' : '' ?>>
                        <?= e($city['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="ville_arrivee">Ville d’arrivée</label>
            <select id="ville_arrivee" name="ville_arrivee" required>
                <option value="">-- choisir --</option>
                <?php foreach ($cities as $city): ?>
                    <option value="<?= e($city['id']) ?>" <?= (string) $city['id'] === (string) $form['ville_arrivee'] ? 'selected' : '' ?>>
                        <?= e($city['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="vehicule_id">Véhicule</label>
            <select id="vehicule_id" name="vehicule_id" required>
                <option value="">-- choisir --</option>
                <?php foreach ($vehicles as $vehicle): ?>
                    <option value="<?= e($vehicle['id']) ?>" <?= (string) $vehicle['id'] === (string) $form['vehicule_id'] ? 'selected' : '' ?>>
                        <?= e($vehicle['marque'] . ' ' . $vehicle['modele'] . ' - ' . $vehicle['immatriculation']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="prix">Prix</label>
            <input id="prix" name="prix" type="number" min="0.01" step="0.01" value="<?= e($form['prix']) ?>" required>

            <label for="date_depart">Date de départ</label>
            <input id="date_depart" name="date_depart" type="date" value="<?= e($form['date_depart']) ?>" required>

            <label for="heure_depart">Heure de départ</label>
            <input id="heure_depart" name="heure_depart" type="time" value="<?= e($form['heure_depart']) ?>" required>

            <div class="actions">
                <button class="button success-button" type="submit">Submit</button>
                <button class="button reset-button" type="reset">Reset</button>
            </div>
        </form>
    <?php endif; ?>
</section>
