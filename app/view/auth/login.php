<?php // Vue F1 : formulaire de connexion. ?>
<section>
    <h2>Login</h2>
    <?php if ($errors): ?>
        <div class="flash error">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="form" method="post" action="<?= e(url_for('login')) ?>">
        <label for="login">Login</label>
        <input id="login" name="login" value="<?= e($login) ?>" required>

        <label for="password">Mot de passe</label>
        <input id="password" name="password" type="password" required>

        <button class="button" type="submit">Se connecter</button>
    </form>
</section>
