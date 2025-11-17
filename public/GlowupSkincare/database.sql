-- Buat database: CREATE DATABASE glowupskincare CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- Gunakan database: USE glowupskincare;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','customer') NOT NULL DEFAULT 'customer',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  description TEXT,
  price DECIMAL(12,2) NOT NULL DEFAULT 0,
  stock INT NOT NULL DEFAULT 0,
  category_id INT DEFAULT NULL,
  image VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  total_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  price DECIMAL(12,2) NOT NULL DEFAULT 0,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed data
INSERT INTO users (name, email, password_hash, role) VALUES
('Admin', 'admin@glowup.com', '$2y$10$Qv6M7mE2oO3dD3cXzT2X4e7l0w0b0p8yV6wzCqvZ5mXw1b5oJ7Hri', 'admin');
-- Password hash di atas untuk: admin123

INSERT INTO categories (name) VALUES
('Cleanser'), ('Toner'), ('Serum'), ('Moisturizer'), ('Sunscreen');

INSERT INTO products (name, description, price, stock, category_id, image) VALUES
('Gentle Cleanser', 'Pembersih wajah lembut untuk semua jenis kulit.', 59000, 50, 1, NULL),
('Hydrating Toner', 'Toner melembapkan dengan sensasi menyegarkan.', 69000, 40, 2, NULL),
('Brightening Serum', 'Serum pencerah dengan Vitamin C.', 129000, 30, 3, NULL),
('Lightweight Moisturizer', 'Krim pelembap ringan tidak lengket.', 99000, 60, 4, NULL),
('Daily Sunscreen SPF50', 'Perlindungan sinar UV harian.', 89000, 70, 5, NULL);
