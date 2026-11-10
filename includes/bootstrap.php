<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once dirname(__DIR__) . '/config.php';

$pdo = null;
$db_error = null;
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $exception) {
    // Le détail technique reste dans les journaux du serveur, pas dans la page publique.
    error_log('Nova Boutique DB: ' . $exception->getMessage());
    $db_error = 'La base de données n’est pas encore configurée. Importez database/schema.sql dans phpMyAdmin, puis actualisez cette page.';
}

function e(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function money(int|float|string $amount): string {
    return CURRENCY_SYMBOL . number_format((float)$amount, 2, ',', ' ');
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void {
    $submitted = (string)($_POST['csrf_token'] ?? '');
    if (!hash_equals(csrf_token(), $submitted)) {
        http_response_code(419);
        exit('Session expirée ou formulaire invalide. Revenez en arrière et réessayez.');
    }
}

function cart_count(): int {
    $count = 0;
    foreach (($_SESSION['cart'] ?? []) as $quantity) {
        $count += max(0, (int)$quantity);
    }
    return $count;
}

function cart_items(PDO $pdo): array {
    $cart = $_SESSION['cart'] ?? [];
    if (!$cart) return [];
    $ids = array_values(array_filter(array_map('intval', array_keys($cart)), fn($id) => $id > 0));
    if (!$ids) return [];
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $statement = $pdo->prepare("SELECT id, name, price, image, stock FROM products WHERE active = 1 AND id IN ($placeholders)");
    $statement->execute($ids);
    $items = [];
    foreach ($statement->fetchAll() as $product) {
        $quantity = max(1, (int)($cart[$product['id']] ?? 1));
        $product['quantity'] = min($quantity, (int)$product['stock']);
        $product['subtotal'] = $product['price'] * $product['quantity'];
        $items[] = $product;
    }
    return $items;
}

function cart_total(array $items): float {
    return array_sum(array_map(fn($item) => (float)$item['subtotal'], $items));
}
