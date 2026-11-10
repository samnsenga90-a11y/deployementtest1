<?php
require_once __DIR__ . '/includes/bootstrap.php';
$order = $_SESSION['last_order'] ?? null;
unset($_SESSION['last_order']);
if (!$order) { header('Location: index.php'); exit; }
?>
<!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Commande reçue — <?= e(SITE_NAME) ?></title><link rel="stylesheet" href="assets/css/style.css"></head><body><header class="header"><a class="logo" href="index.php">NOVA<span>BOUTIQUE</span></a></header><main class="section narrow"><div class="success-mark">✓</div><p class="eyebrow">COMMANDE ENREGISTRÉE</p><h1>Merci <?= e($order['name']) ?> !</h1><div class="notice success">Votre commande n°<?= (int)$order['id'] ?> a bien été enregistrée. Notre équipe vous contactera pour confirmer la livraison.</div><div class="checkout-summary"><span>Total de la commande</span><strong><?= money($order['total']) ?></strong></div><a class="button full-button" href="index.php">Retour à la boutique</a></main><footer class="footer"><small>© <?= date('Y') ?> Nova Boutique</small></footer></body></html>
