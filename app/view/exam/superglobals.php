<?php // Vue E1 : affichage lisible des superglobales pour l'examinateur. ?>
<section>
    <h2>SuperGlobales (Cookies et Session)</h2>
    <div class="two-columns">
        <article class="panel">
            <h3>$_COOKIE</h3>
            <pre><?= e(print_r($cookies, true)) ?></pre>
        </article>
        <article class="panel">
            <h3>$_SESSION</h3>
            <pre><?= e(print_r($session, true)) ?></pre>
        </article>
    </div>
</section>
