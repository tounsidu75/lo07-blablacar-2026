<section>
    <h2>Liste des utilisateurs</h2>
    <?php if (!$users): ?>
        <p class="muted">Aucun utilisateur.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>nom</th>
                    <th>prenom</th>
                    <th>role</th>
                    <th>login</th>
                    <th>password</th>
                    <th class="number">solde</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= e($user['nom']) ?></td>
                        <td><?= e($user['prenom']) ?></td>
                        <td><?= e($user['role']) ?></td>
                        <td><?= e($user['login']) ?></td>
                        <td><?= e($user['password']) ?></td>
                        <td class="number"><?= e(money_format_fr($user['solde'])) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
