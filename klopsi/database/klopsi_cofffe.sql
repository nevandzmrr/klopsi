CREATE DATABASE IF NOT EXISTS klopsi_cofffe;
USE klopsi_cofffe;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role VARCHAR(20) DEFAULT 'admin',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE reservations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  reservation_date DATE NOT NULL,
  time_slot VARCHAR(60) NOT NULL,
  package_name VARCHAR(120) NOT NULL,
  guest_count INT NOT NULL,
  notes TEXT,
  status VARCHAR(20) DEFAULT 'baru',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reservation_dates (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reserve_date DATE NOT NULL UNIQUE,
  capacity INT NOT NULL DEFAULT 70,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE gallery_images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(120),
  caption VARCHAR(255),
  image_path VARCHAR(255) NOT NULL,
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(60) NOT NULL UNIQUE,
  setting_value VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO settings (setting_key, setting_value) VALUES
('address', 'Jl. Mawar No. 18, Bandung'),
('whatsapp', '0812-3456-7890'),
('instagram', 'https://www.instagram.com/klopsicoffee/'),
('open_hours', 'All day (16.00 WIB)'),
('close_hours', 'Weekday (23.00 WIB) • Weekend (00.00 WIB)');

INSERT INTO users (name, email, password_hash, role)
VALUES ('Admin Klopsi', 'admin@klopsi.id', '$2y$10$SdUL5l/.c2wTYT5XF1nuOu1ZAMt0yueiQdMpc7iogWby0RgGZkSAW
', 'admin');
