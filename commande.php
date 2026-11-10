<?php
require_once __DIR__ . '/includes/bootstrap.php';
if (!$pdo) { http_response_code(503); }
$items = $pdo ? cart_items($pdo) : [];
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo) {
    verify_csrf();
    $items = cart_items($pdo);
    $name = trim((string)($_POST['customer_name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $address = trim((string)($_POST['address'] ?? ''));
    $city = trim((string)($_POST['city'] ?? ''));
    $notes = trim((string)($_POST['notes'] ?? ''));
    if (!$items) $errors[] = 'Votre panier est vide.';
    if ($name === '' || mb_strlen($name) > 120) $errors[] = 'Veuillez saisir votre nom.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Adresse e-mail invalide.';
    if ($phone === '' || mb_strlen($phone) > 40) $errors[] = 'Veuillez saisir votre numéro de téléphone.';
    if ($address === '' || $city === '') $errors[] = 'Veuillez saisir votre adresse et votre ville.';
    if (!$errors) {
        try {
            $pdo->beginTransaction();
            $total = 0.0;
            $lockedItems = [];
            $lock = $pdo->prepare('SELECT id, name, price, stock FROM products WHERE id = ? AND active = 1 FOR UPDATE');
            foreach ($items as $item) {
                $lock->execute([(int)$item['id']]); $current = $lock->fetch();
                if (!$current || (int)$current['stock'] < (int)$item['quantity']) {
                    throw new RuntimeException('Stock insuffisant pour ' . $item['name'] . '. Veuillez actualiser votre panier.');
                }
                $current['quantity'] = (int)$item['quantity'];
                $current['subtotal'] = (float)$current['price'] * $current['quantity'];
                $total += $current['subtotal']; $lockedItems[] = $current;
            }
            $insertOrder = $pdo->prepare('INSERT INTO orders (customer_name, email, phone, address, city, notes, total, status, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $insertOrder->execute([$name, $email, $phone, $address, $city, $notes, $total, 'En attente', 'Paiement à la livraison']);
            $orderId = (int)$pdo->lastInsertId();
            $insertLine = $pdo->prepare('INSERT INTO order_items (order_id, product_id, product_name, unit_price, quantity, subtotal) VALUES (?, ?, ?, ?, ?, ?)');
            $decrement = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?');
            foreach ($lockedItems as $line) {
                $insertLine->execute([$orderId, $line['id'], $line['name'], $line['price'], $line['quantity'], $line['subtotal']]);
                $decrement->execute([$line['quantity'], $line['id'], $line['quantity']]);
                if ($decrement->rowCount() !== 1) throw new RuntimeException('Le stock a changé. Veuillez réessayer.');
            }
            $pdo->commit();
            $_SESSION['last_order'] = ['id' => $orderId, 'name' => $name, 'total' => $total];
            unset($_SESSION['cart']);
            header('Location: confirmation.php'); exit;
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $errors[] = $exception instanceof RuntimeException ? $exception->getMessage() : 'Impossible d’enregistrer la commande. Vérifiez la base de données puis réessayez.';
            error_log('Nova Boutique order: ' . $exception->getMessage());
        }
    }
}
$total = cart_total($items);
?>
<!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Commande — <?= e(SITE_NAME) ?></title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<header class="header"><a class="logo" href="index.php">NOVA<span>BOUTIQUE</span></a><nav><a href="panier.php">← Retour au panier</a></nav></header><main class="section narrow"><p class="eyebrow">DERNIÈRE ÉTAPE</p><h1>Finaliser la commande</h1>
<?php if ($db_error): ?><div class="notice warning"><?= e($db_error) ?></div><?php elseif (!$items): ?><div class="notice">Votre panier est vide. <a href="index.php">Voir les produits</a>.</div><?php else: ?>
<?php foreach ($errors as $error): ?><div class="notice warning"><?= e($error) ?></div><?php endforeach; ?>
<div class="checkout-summary"><span><?= cart_count() ?> article(s)</span><strong><?= money($total) ?></strong></div>
<form method="post" class="checkout-form"><?= csrf_field() ?><label for="customer_name">Nom complet *</label><input id="customer_name" name="customer_name" required maxlength="120" value="<?= e($_POST['customer_name'] ?? '') ?>"><label for="email">Adresse e-mail *</label><input id="email" name="email" type="email" required value="<?= e($_POST['email'] ?? '') ?>"><label for="phone">Téléphone *</label><input id="phone" name="phone" required maxlength="40" placeholder="+243..." value="<?= e($_POST['phone'] ?? '') ?>"><label for="address">Adresse de livraison *</label><input id="address" name="address" required value="<?= e($_POST['address'] ?? '') ?>"><label for="city">Ville *</label><input id="city" name="city" required value="<?= e($_POST['city'] ?? '') ?>"><label for="notes">Précisions (facultatif)</label><textarea id="notes" name="notes" rows="3"><?= e($_POST['notes'] ?? '') ?></textarea><div class="payment-note"><strong>Mode de paiement</strong><p>Paiement à la livraison. La boutique vous contactera pour confirmer les détails.</p></div><button class="button full-button" type="submit">Confirmer ma commande</button></form>
<?php endif; ?></main><footer class="footer"><a class="logo" href="index.php">NOVA<span>BOUTIQUE</span></a></footer></body></html>
