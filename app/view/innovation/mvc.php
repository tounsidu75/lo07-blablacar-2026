<section>
    <h2>Innovation MVC : code plus maintenable</h2>
    <div class="panel">
        <p>
            L’amélioration proposée consiste à centraliser les responsabilités répétées dans des composants MVC
            réutilisables : contrôleur de base, fonctions de rendu, messages flash, garde de rôle et transactions
            SQL dans les modèles.
        </p>
        <p>
            Avantages : les vues restent dédiées à l’affichage, les contrôleurs orchestrent les cas d’usage,
            et les modèles protègent les opérations critiques comme la clôture d’un trajet avec paiement.
        </p>
        <p>
            Cette organisation limite la duplication du menu, rend les droits plus explicites et permet de tester
            les règles métier sans modifier les pages HTML.
        </p>
    </div>
</section>
