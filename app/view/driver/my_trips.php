<?php // Vue C2 : tous les trajets du conducteur connecte. ?>
<section>
    <h2>Liste de tous les trajets du conducteur <?= e($currentUser['nom'] . ' ' . $currentUser['prenom']) ?></h2>
    <?php if (!$trips): ?>
        <p class="muted">Aucun trajet enregistré pour ce conducteur.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>date_depart</th>
                    <th>heure_depart</th>
                    <th>depart</th>
                    <th>destination</th>
                    <th>vehicule</th>
                    <th>immatriculation</th>
                    <th class="number">prix</th>
                    <th>statut</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($trips as $trip): ?>
                    <tr>
                        <td><?= e($trip['date_depart']) ?></td>
                        <td><?= e($trip['heure_depart']) ?></td>
                        <td><?= e($trip['depart']) ?></td>
                        <td><?= e($trip['destination']) ?></td>
                        <td><?= e($trip['vehicule']) ?></td>
                        <td><?= e($trip['immatriculation']) ?></td>
                        <td class="number"><?= e(money_format_fr($trip['prix'])) ?></td>
                        <td><span class="status <?= e($trip['statut']) ?>"><?= e($trip['statut']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
