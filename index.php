<?php
require_once __DIR__ . '/includes/bootstrap.php';
$products = [];
if ($pdo) {
    $products = $pdo->query('SELECT id, name, description, price, image, stock FROM products WHERE active = 1 ORDER BY featured DESC, id DESC')->fetchAll();
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Nova Boutique — découvrez notre sélection de produits.">
  <title><?= e(SITE_NAME) ?> — Accueil</title>
  <link rel="stylesheet" href="assets/css/style.css"><script src="assets/js/app.js" defer></script>
</head>
<body>
<header class="header"><a class="logo" href="index.php">NOVA<span>BOUTIQUE</span></a><nav><a href="index.php">Accueil</a><a href="#catalogue">Produits</a><a class="cart-link" href="panier.php">Panier (<?= cart_count() ?>)</a></nav></header>
<main>
<section class="hero"><div><p class="eyebrow">LA SÉLECTION NOVA</p><h1>Les bons produits.<br><em>Le bon choix.</em></h1><p>Découvrez une sélection pensée pour simplifier votre quotidien. Commandez simplement, nous vous contactons pour confirmer votre commande.</p><a class="button" href="#catalogue">Découvrir les produits</a></div><div class="hero-art"><span>✦</span><b>NOVA<br>CHOICE</b><small>QUALITÉ · SIMPLICITÉ · CONFIANCE</small></div></section>
<?php if ($db_error): ?><div class="notice warning"><?= e($db_error) ?></div><?php endif; ?>
<section class="section" id="catalogue"><div class="section-title"><div><p class="eyebrow">NOTRE CATALOGUE</p><h2>À découvrir</h2></div><p>Une première sélection de démonstration, modifiable dans la base de données.</p></div>
<?php if (!$db_error && !$products): ?><div class="notice">Aucun produit disponible pour le moment.</div><?php endif; ?>
<div class="product-grid">
<?php foreach ($products as $product): ?>
<article class="product-card"><a class="product-image" href="produit.php?id=<?= (int)$product['id'] ?>"><img src="<?= e($product['image'] ?: 'https://placehold.co/600x450?text=Nova+Boutique') ?>" alt="<?= e($product['name']) ?>" loading="lazy"></a><div class="product-info"><p class="product-tag">SÉLECTION NOVA</p><h3><a href="produit.php?id=<?= (int)$product['id'] ?>"><?= e($product['name']) ?></a></h3><p class="muted"><?= e($product['description']) ?></p><div class="product-bottom"><strong><?= money($product['price']) ?></strong><a class="small-button" href="produit.php?id=<?= (int)$product['id'] ?>">Voir le produit</a></div></div></article>
<?php endforeach; ?>
</div></section>
<section class="promise"><div><b>01</b><span>Commande simple</span></div><div><b>02</b><span>Confirmation par téléphone</span></div><div><b>03</b><span>Paiement à la livraison</span></div></section>
</main><footer class="footer"><a class="logo" href="index.php">NOVA<span>BOUTIQUE</span></a><p>Une boutique locale, simple et pratique.</p><small>© <?= date('Y') ?> Nova Boutique — Version de démonstration locale.</small></footer>
</body></html>
