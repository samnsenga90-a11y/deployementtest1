<?php
require_once __DIR__ . '/includes/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = (string)($_POST['action'] ?? '');
    $id = (int)($_POST['product_id'] ?? 0);
    if ($action === 'remove') { unset($_SESSION['cart'][$id]); }
    if ($action === 'update' && isset($_SESSION['cart'][$id]) && $pdo) {
        $stmt = $pdo->prepare('SELECT stock FROM products WHERE id = ? AND active = 1'); $stmt->execute([$id]); $stock = (int)$stmt->fetchColumn();
        $qty = (int)($_POST['quantity'] ?? 1);
        if ($qty <= 0 || $stock <= 0) unset($_SESSION['cart'][$id]); else $_SESSION['cart'][$id] = min($qty, $stock);
    }
    header('Location: panier.php'); exit;
}
$items = $pdo ? cart_items($pdo) : [];
$total = cart_total($items);
?>
<!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Mon panier — <?= e(SITE_NAME) ?></title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<header class="header"><a class="logo" href="index.php">NOVA<span>BOUTIQUE</span></a><nav><a href="index.php">Continuer mes achats</a><a class="cart-link" href="panier.php">Panier (<?= cart_count() ?>)</a></nav></header><main class="section narrow"><p class="eyebrow">VOTRE SÉLECTION</p><h1>Mon panier</h1>
<?php if ($db_error): ?><div class="notice warning"><?= e($db_error) ?></div><?php elseif (isset($_GET['ajoute'])): ?><div class="notice success">Produit ajouté au panier.</div><?php endif; ?>
<?php if (!$items): ?><div class="empty-state"><h2>Votre panier est vide</h2><p class="muted">Ajoutez un produit pour commencer votre commande.</p><a class="button" href="index.php">Voir les produits</a></div><?php else: ?>
<div class="cart-list"><?php foreach ($items as $item): ?><article class="cart-item"><img src="<?= e($item['image'] ?: 'https://placehold.co/160x130') ?>" alt=""><div class="cart-description"><h3><?= e($item['name']) ?></h3><p><?= money($item['price']) ?> / unité</p><form method="post" class="inline-form"><?= csrf_field() ?><input type="hidden" name="action" value="update"><input type="hidden" name="product_id" value="<?= (int)$item['id'] ?>"><label class="sr-only" for="q<?= (int)$item['id'] ?>">Quantité</label><input id="q<?= (int)$item['id'] ?>" type="number" name="quantity" min="1" max="<?= (int)$item['stock'] ?>" value="<?= (int)$item['quantity'] ?>"><button class="text-button">Actualiser</button></form></div><strong><?= money($item['subtotal']) ?></strong><form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="remove"><input type="hidden" name="product_id" value="<?= (int)$item['id'] ?>"><button class="remove-button" aria-label="Retirer ce produit">×</button></form></article><?php endforeach; ?></div>
<div class="cart-total"><span>Total estimé</span><strong><?= money($total) ?></strong></div><p class="muted tiny">La livraison et la disponibilité seront confirmées lors du traitement de la commande.</p><a class="button full-button" href="commande.php">Passer la commande</a><?php endif; ?>
</main><footer class="footer"><a class="logo" href="index.php">NOVA<span>BOUTIQUE</span></a></footer></body></html>
