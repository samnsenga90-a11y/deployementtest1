CREATE DATABASE IF NOT EXISTS nova_boutique CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE nova_boutique;

CREATE TABLE IF NOT EXISTS products (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(160) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  image VARCHAR(500) DEFAULT NULL,
  stock INT UNSIGNED NOT NULL DEFAULT 0,
  featured TINYINT(1) NOT NULL DEFAULT 0,
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS orders (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  customer_name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  phone VARCHAR(40) NOT NULL,
  address VARCHAR(255) NOT NULL,
  city VARCHAR(120) NOT NULL,
  notes TEXT DEFAULT NULL,
  total DECIMAL(10,2) NOT NULL,
  status VARCHAR(40) NOT NULL DEFAULT 'En attente',
  payment_method VARCHAR(80) NOT NULL DEFAULT 'Paiement à la livraison',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_orders_created_at (created_at),
  INDEX idx_orders_status (status)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED DEFAULT NULL,
  product_name VARCHAR(160) NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO products (name, description, price, image, stock, featured, active)
SELECT * FROM (
  SELECT 'Casque audio sans fil' AS name, 'Un casque confortable pour écouter votre musique et travailler au quotidien.' AS description, 35.00 AS price, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=900&q=80' AS image, 12 AS stock, 1 AS featured, 1 AS active
  UNION ALL SELECT 'Montre connectée', 'Une montre pratique pour suivre vos activités et recevoir vos notifications.', 49.00, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=900&q=80', 8, 1, 1
  UNION ALL SELECT 'Sac à dos urbain', 'Un sac polyvalent avec plusieurs compartiments pour vos déplacements.', 29.00, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=900&q=80', 15, 1, 1
  UNION ALL SELECT 'Lampe de bureau', 'Un éclairage sobre pour aménager un espace de travail agréable.', 22.00, 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=900&q=80', 10, 0, 1
  UNION ALL SELECT 'Appareil photo compact', 'Un appareil compact pour capturer vos souvenirs au quotidien.', 115.00, 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=900&q=80', 4, 0, 1
  UNION ALL SELECT 'Clavier mécanique', 'Un clavier agréable et réactif pour la bureautique et le jeu.', 58.00, 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=900&q=80', 7, 0, 1
) AS demo
WHERE NOT EXISTS (SELECT 1 FROM products LIMIT 1);
