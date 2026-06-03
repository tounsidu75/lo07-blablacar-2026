<section>
    <h2>Liste de mes réservations</h2>
    <?php if (!$reservations): ?>
        <p class="muted">Aucune réservation.</p>
    <?php else: ?>
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
                    <th>statut</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?= e($reservation['date_depart']) ?></td>
                        <td><?= e($reservation['heure_depart']) ?></td>
                        <td><?= e($reservation['depart']) ?></td>
                        <td><?= e($reservation['destination']) ?></td>
                        <td><?= e($reservation['conducteur']) ?></td>
                        <td><?= e($reservation['vehicule']) ?></td>
                        <td><?= e($reservation['immatriculation']) ?></td>
                        <td class="number"><?= e(money_format_fr($reservation['prix'])) ?></td>
                        <td><span class="status <?= e($reservation['statut']) ?>"><?= e($reservation['statut']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
