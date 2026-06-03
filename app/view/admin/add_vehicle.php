<section>
    <h2>Formulaire de création d’un nouveau véhicule</h2>
    <?php if ($errors): ?>
        <div class="flash error">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="form" method="post" action="<?= e(url_for('admin_add_vehicle')) ?>">
        <label for="marque">Marque</label>
        <input id="marque" name="marque" value="<?= e($form['marque']) ?>" required>

        <label for="modele">Modèle</label>
        <input id="modele" name="modele" value="<?= e($form['modele']) ?>" required>

        <label for="annee">Année</label>
        <input id="annee" name="annee" type="number" min="1900" max="2100" value="<?= e($form['annee']) ?>" required>

        <label for="immatriculation">Immatriculation</label>
        <input id="immatriculation" name="immatriculation" value="<?= e($form['immatriculation']) ?>" required>

        <label for="proprietaire_id">Sélectionner un propriétaire</label>
        <select id="proprietaire_id" name="proprietaire_id" required>
            <option value="">-- choisir --</option>
            <?php foreach ($drivers as $driver): ?>
                <option value="<?= e($driver['id']) ?>" <?= (string) $driver['id'] === (string) $form['proprietaire_id'] ? 'selected' : '' ?>>
                    <?= e($driver['prenom'] . ' ' . $driver['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button class="button" type="submit">Ajouter</button>
    </form>
</section>
