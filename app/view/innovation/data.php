<?php // Vue innovation data : indicateurs calcules depuis la base SQL. ?>
<section>
    <h2>Innovation data : tableau de bord</h2>
    <div class="metrics">
        <article><strong><?= e($dashboard['counts']['users_count']) ?></strong><span>utilisateurs</span></article>
        <article><strong><?= e($dashboard['counts']['drivers_count']) ?></strong><span>conducteurs</span></article>
        <article><strong><?= e($dashboard['counts']['passengers_count']) ?></strong><span>passagers</span></article>
        <article><strong><?= e($dashboard['counts']['vehicles_count']) ?></strong><span>véhicules</span></article>
        <article><strong><?= e($dashboard['counts']['cities_count']) ?></strong><span>villes</span></article>
        <article><strong><?= e($dashboard['counts']['active_trips_count']) ?></strong><span>trajets actifs</span></article>
        <article><strong><?= e($dashboard['counts']['passive_trips_count']) ?></strong><span>trajets passifs</span></article>
        <article><strong><?= e(money_format_fr($dashboard['counts']['average_price'])) ?></strong><span>prix moyen</span></article>
    </div>

    <div class="two-columns">
        <article>
            <h3>Trajets les plus réservés</h3>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>trajet</th><th class="number">réservations</th></tr></thead>
                    <tbody>
                    <?php foreach ($dashboard['popularTrips'] as $trip): ?>
                        <tr>
                            <td><?= e($trip['depart'] . ' --> ' . $trip['destination']) ?></td>
                            <td class="number"><?= e($trip['reservations']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </article>
        <article>
            <h3>Montant réservé par conducteur</h3>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>conducteur</th><th class="number">montant</th></tr></thead>
                    <tbody>
                    <?php foreach ($dashboard['driverIncome'] as $driver): ?>
                        <tr>
                            <td><?= e($driver['conducteur']) ?></td>
                            <td class="number"><?= e(money_format_fr($driver['montant_reserve'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </article>
    </div>

    <section>
        <h3>Villes les plus utilisées</h3>
        <div class="city-grid">
            <?php foreach ($dashboard['busyCities'] as $city): ?>
                <span><?= e($city['ville']) ?> : <?= e($city['total']) ?></span>
            <?php endforeach; ?>
        </div>
    </section>
</section>
