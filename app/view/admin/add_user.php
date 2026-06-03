<section>
    <h2><?= e($heading) ?></h2>
    <?php if ($errors): ?>
        <div class="flash error">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="form" method="post" action="<?= e(url_for($role === 'conducteur' ? 'admin_add_driver' : 'admin_add_passenger')) ?>">
        <label for="nom">Nom</label>
        <input id="nom" name="nom" value="<?= e($form['nom']) ?>" maxlength="40" required>

        <label for="prenom">Prénom</label>
        <input id="prenom" name="prenom" value="<?= e($form['prenom']) ?>" maxlength="40" required>

        <label for="login">Login</label>
        <input id="login" name="login" value="<?= e($form['login']) ?>" maxlength="20" required>

        <label for="password">Mot de passe</label>
        <input id="password" name="password" value="<?= e($form['password']) ?>" maxlength="20" required>

        <label for="solde">Solde initial</label>
        <input id="solde" name="solde" type="number" min="0" step="0.01" value="<?= e($form['solde']) ?>" required>

        <button class="button" type="submit">Ajouter</button>
    </form>
</section>
