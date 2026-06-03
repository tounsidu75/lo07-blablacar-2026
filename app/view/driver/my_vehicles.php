<section>
    <h2>Liste des véhicules du conducteur <?= e($currentUser['nom'] . ' ' . $currentUser['prenom']) ?></h2>
    <?php if (!$vehicles): ?>
        <p class="muted">Aucun véhicule enregistré pour ce conducteur.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>marque</th>
                    <th>modele</th>
                    <th class="number">annee</th>
                    <th>immatriculation</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($vehicles as $vehicle): ?>
                    <tr>
                        <td><?= e($vehicle['marque']) ?></td>
                        <td><?= e($vehicle['modele']) ?></td>
                        <td class="number"><?= e($vehicle['annee']) ?></td>
                        <td><?= e($vehicle['immatriculation']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
