<?php
declare(strict_types=1);

$services = [
    ['number' => '01', 'title' => 'Développement web', 'description' => 'Des sites modernes, rapides et adaptés à tous les écrans.'],
    ['number' => '02', 'title' => 'Solutions numériques', 'description' => 'Des outils simples pour faire grandir vos projets.'],
    ['number' => '03', 'title' => 'Accompagnement', 'description' => 'Un accompagnement clair, de la première idée à la mise en ligne.'],
];

$year = date('Y');
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Une présence numérique claire, moderne et efficace.">
    <title>Nova — Vos idées prennent vie</title>
    <style>
        :root { color-scheme: light; --ink:#17211d; --muted:#68736d; --green:#176b4b; --lime:#c9f27b; --paper:#f7f8f4; }
        * { box-sizing:border-box; }
        html { scroll-behavior:smooth; }
        body { margin:0; font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif; color:var(--ink); background:var(--paper); line-height:1.6; }
        a { color:inherit; text-decoration:none; }
        .wrap { width:min(1120px,calc(100% - 40px)); margin:auto; }
        header { display:flex; justify-content:space-between; align-items:center; padding:24px 0; }
        .logo { font-size:1.35rem; font-weight:850; letter-spacing:-.06em; }
        .logo span { color:var(--green); }
        nav { display:flex; gap:28px; align-items:center; font-size:.94rem; }
        nav a:hover { color:var(--green); }
        .button { display:inline-flex; align-items:center; justify-content:center; background:var(--green); color:white; padding:13px 20px; border-radius:999px; font-weight:700; transition:transform .2s,background .2s; }
        .button:hover { transform:translateY(-2px); background:#10543a; }
        .hero { padding:84px 0 100px; display:grid; grid-template-columns:1.2fr .8fr; gap:56px; align-items:center; }
        .eyebrow { color:var(--green); text-transform:uppercase; font-size:.76rem; font-weight:800; letter-spacing:.15em; }
        h1 { font-size:clamp(3rem,7vw,5.8rem); line-height:.98; letter-spacing:-.075em; margin:18px 0 24px; max-width:720px; }
        h1 em { color:var(--green); font-style:normal; }
        .lead { max-width:540px; color:var(--muted); font-size:1.12rem; margin-bottom:30px; }
        .visual { min-height:370px; border-radius:32px; background:var(--green); position:relative; overflow:hidden; display:grid; place-items:center; }
        .visual:before,.visual:after { content:""; position:absolute; border:1px solid rgba(255,255,255,.25); border-radius:50%; width:310px; height:310px; }
        .visual:after { width:430px; height:430px; }
        .orb { width:190px; height:190px; border-radius:42% 58% 62% 38%; background:var(--lime); transform:rotate(-25deg); box-shadow:0 25px 80px #092f2180; z-index:1; }
        .visual-label { position:absolute; z-index:2; bottom:26px; right:28px; color:white; font-weight:700; }
        section { padding:76px 0; }
        .section-head { display:flex; justify-content:space-between; gap:30px; align-items:end; margin-bottom:32px; }
        h2 { font-size:clamp(2rem,4vw,3.2rem); letter-spacing:-.06em; line-height:1.05; margin:10px 0 0; }
        .section-head p { color:var(--muted); max-width:390px; margin:0; }
        .cards { display:grid; grid-template-columns:repeat(3,1fr); gap:18px; }
        .card { background:white; border:1px solid #e8ece5; border-radius:22px; padding:28px; min-height:220px; }
        .card small { color:var(--green); font-weight:800; }
        .card h3 { margin:28px 0 8px; font-size:1.25rem; }
        .card p { color:var(--muted); margin:0; }
        .contact { background:#eaf1e8; border-radius:30px; padding:52px; display:flex; justify-content:space-between; align-items:center; gap:30px; }
        .contact p { color:var(--muted); margin-bottom:0; }
        footer { padding:28px 0; color:var(--muted); font-size:.9rem; display:flex; justify-content:space-between; gap:20px; }
        @media (max-width:760px) { nav { gap:14px; font-size:.85rem; } nav a:not(.button) { display:none; } .hero { grid-template-columns:1fr; padding:54px 0 70px; gap:34px; } .visual { min-height:270px; } .cards { grid-template-columns:1fr; } .section-head,.contact { align-items:flex-start; flex-direction:column; } .contact { padding:32px; } section { padding:55px 0; } }
    </style>
</head>
<body>
<header class="wrap">
    <a class="logo" href="#" aria-label="Nova, accueil">nova<span>.</span></a>
    <nav aria-label="Navigation principale">
        <a href="#services">Services</a>
        <a href="#apropos">À propos</a>
        <a class="button" href="#contact">Nous contacter</a>
    </nav>
</header>

<main>
    <div class="wrap hero" id="apropos">
        <div>
            <div class="eyebrow">Créativité · Technologie · Impact</div>
            <h1>Vos idées méritent de <em>grandir.</em></h1>
            <p class="lead">Nous transformons vos idées en expériences numériques simples, utiles et mémorables. Bienvenue dans la suite de votre projet.</p>
            <a class="button" href="#services">Découvrir nos services <span aria-hidden="true">&nbsp;↗</span></a>
        </div>
        <div class="visual" aria-label="Illustration abstraite verte">
            <div class="orb"></div>
            <div class="visual-label">Pensé pour demain ↗</div>
        </div>
    </div>

    <section id="services">
        <div class="wrap">
            <div class="section-head">
                <div><div class="eyebrow">Ce que nous faisons</div><h2>Du concret.<br>Du numérique.</h2></div>
                <p>Une approche directe et humaine pour donner à votre activité les bons outils et une présence en ligne solide.</p>
            </div>
            <div class="cards">
                <?php foreach ($services as $service): ?>
                    <article class="card">
                        <small><?= htmlspecialchars($service['number'], ENT_QUOTES, 'UTF-8') ?></small>
                        <h3><?= htmlspecialchars($service['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p><?= htmlspecialchars($service['description'], ENT_QUOTES, 'UTF-8') ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="wrap" id="contact">
        <div class="contact">
            <div><div class="eyebrow">On commence ?</div><h2>Parlons de votre idée.</h2><p>Chaque grand projet commence par une conversation.</p></div>
            <a class="button" href="mailto:contact@example.com">Écrivez-nous ↗</a>
        </div>
    </section>
</main>

<footer class="wrap">
    <span>© <?= $year ?> Nova. Tous droits réservés.</span>
    <span>Créé avec PHP &amp; passion.</span>
</footer>
</body>
</html>
