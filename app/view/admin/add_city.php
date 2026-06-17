<?php // Vue A7 : formulaire d'ajout d'une ville. ?>
<section>
    <h2>Ajout d’une ville</h2>
    <?php if ($errors): ?>
        <div class="flash error">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="form" method="post" action="<?= e(url_for('admin_add_city')) ?>">
        <label for="nom">Nom de la ville</label>
        <input id="nom" name="nom" value="<?= e($nom) ?>" required>

        <button class="button" type="submit">Ajouter</button>
    </form>
</section>
