<?php
require_once __DIR__ . '/includes/bootstrap.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$product = null;
if ($pdo && $id) {
    $statement = $pdo->prepare('SELECT * FROM products WHERE id = ? AND active = 1');
    $statement->execute([$id]);
    $product = $statement->fetch() ?: null;
}
if (!$product) { http_response_code(404); }
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $product) {
    verify_csrf();
    $quantity = max(1, min((int)($_POST['quantity'] ?? 1), (int)$product['stock']));
    if ($quantity > 0 && (int)$product['stock'] > 0) {
        $_SESSION['cart'] ??= [];
        $_SESSION['cart'][$product['id']] = min((int)($_SESSION['cart'][$product['id']] ?? 0) + $quantity, (int)$product['stock']);
        header('Location: panier.php?ajoute=1'); exit;
    }
}
?>
<!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Produit — <?= e(SITE_NAME) ?></title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<header class="header"><a class="logo" href="index.php">NOVA<span>BOUTIQUE</span></a><nav><a href="index.php">Accueil</a><a href="panier.php">Panier (<?= cart_count() ?>)</a></nav></header><main class="section">
<?php if ($db_error): ?><div class="notice warning"><?= e($db_error) ?></div><?php elseif (!$product): ?><div class="notice">Produit introuvable. <a href="index.php">Retour au catalogue</a>.</div><?php else: ?>
<a class="back-link" href="index.php">← Retour au catalogue</a><div class="detail-grid"><div class="detail-image"><img src="<?= e($product['image'] ?: 'https://placehold.co/800x650?text=Nova+Boutique') ?>" alt="<?= e($product['name']) ?>"></div><div><p class="eyebrow">SÉLECTION NOVA</p><h1><?= e($product['name']) ?></h1><p class="detail-price"><?= money($product['price']) ?></p><p class="muted"><?= nl2br(e($product['description'])) ?></p><p class="stock"><?= (int)$product['stock'] > 0 ? 'En stock : ' . (int)$product['stock'] : 'Rupture de stock' ?></p>
<?php if ((int)$product['stock'] > 0): ?><form method="post" class="add-form"><?= csrf_field() ?><label for="quantity">Quantité</label><input id="quantity" type="number" name="quantity" min="1" max="<?= (int)$product['stock'] ?>" value="1"><button class="button" type="submit">Ajouter au panier</button></form><?php endif; ?></div></div>
<?php endif; ?></main><footer class="footer"><a class="logo" href="index.php">NOVA<span>BOUTIQUE</span></a><small>© <?= date('Y') ?> Nova Boutique</small></footer></body></html>
