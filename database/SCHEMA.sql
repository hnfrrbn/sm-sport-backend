-- ============================================
-- SM SPORT CENTER - Database Schema
-- ============================================

-- Table: users
CREATE TABLE IF NOT EXISTS users (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(20) NULL,
  role ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
  address TEXT NULL,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  email_verified_at TIMESTAMP NULL,
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_email (email),
  INDEX idx_role (role),
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel pengguna (Admin dan Pelanggan)';

-- Table: fields
CREATE TABLE IF NOT EXISTS fields (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  type ENUM('futsal', 'badminton') NOT NULL,
  capacity INT NOT NULL DEFAULT 4,
  price_per_hour DECIMAL(10,2) NOT NULL,
  status ENUM('available', 'unavailable', 'maintenance') NOT NULL DEFAULT 'available',
  description TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_type (type),
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel lapangan olahraga';

-- Table: reservations
CREATE TABLE IF NOT EXISTS reservations (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  field_id BIGINT UNSIGNED NOT NULL,
  reservation_date DATE NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  duration_hours INT NOT NULL,
  total_price DECIMAL(10,2) NOT NULL,
  status ENUM('pending', 'confirmed', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
  notes TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (field_id) REFERENCES fields(id) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX idx_user_id (user_id),
  INDEX idx_field_id (field_id),
  INDEX idx_reservation_date (reservation_date),
  INDEX idx_status (status),
  UNIQUE KEY unique_booking (field_id, reservation_date, start_time, end_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel reservasi lapangan';

-- Table: transactions
CREATE TABLE IF NOT EXISTS transactions (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  reservation_id BIGINT UNSIGNED NULL,
  transaction_code VARCHAR(255) NOT NULL UNIQUE,
  amount DECIMAL(10,2) NOT NULL,
  payment_method ENUM('cash', 'transfer', 'card') NOT NULL DEFAULT 'cash',
  status ENUM('pending', 'completed', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
  description TEXT NULL,
  payment_date TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE SET NULL ON UPDATE CASCADE,
  INDEX idx_user_id (user_id),
  INDEX idx_reservation_id (reservation_id),
  INDEX idx_transaction_code (transaction_code),
  INDEX idx_status (status),
  INDEX idx_payment_date (payment_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel transaksi pembayaran';

-- Table: activity_logs
CREATE TABLE IF NOT EXISTS activity_logs (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  action VARCHAR(255) NOT NULL,
  model_type VARCHAR(255) NULL,
  model_id BIGINT UNSIGNED NULL,
  description TEXT NULL,
  old_values JSON NULL,
  new_values JSON NULL,
  ip_address VARCHAR(45) NULL,
  user_agent VARCHAR(500) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX idx_user_id (user_id),
  INDEX idx_action (action),
  INDEX idx_model_type (model_type),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel log aktivitas pengguna';

-- ============================================
-- Insert Sample Data
-- ============================================

-- Insert Admin User
INSERT IGNORE INTO users (name, email, password, phone, role, address, status) VALUES
('Admin', 'admin@gmail.com', '$2y$12$rrdQEUZBIKTb9gkjVcs3e6fm7tGdv9Tb.Kf4wXd3d6vDRDl5s67G', '08123456789', 'admin', 'Jl. Olahraga No. 1, Jakarta', 'active');

-- Insert Customer Users
INSERT IGNORE INTO users (name, email, password, phone, role, address, status) VALUES
('Budi Santoso', 'budi@email.com', '$2y$12$W.Q6Fk3H5cA2nM8xL9pR0O3qN7J6kL5mP9V2sD4eW1r8Y3t6u9q', '08111111111', 'customer', 'Jl. Merdeka No. 1, Jakarta', 'active'),
('Siti Nurhaliza', 'siti@email.com', '$2y$12$W.Q6Fk3H5cA2nM8xL9pR0O3qN7J6kL5mP9V2sD4eW1r8Y3t6u9q', '08222222222', 'customer', 'Jl. Sudirman No. 2, Jakarta', 'active'),
('Ahmad Wijaya', 'ahmad@email.com', '$2y$12$W.Q6Fk3H5cA2nM8xL9pR0O3qN7J6kL5mP9V2sD4eW1r8Y3t6u9q', '08333333333', 'customer', 'Jl. Gatot Subroto No. 3, Jakarta', 'active'),
('Rini Handayani', 'rini@email.com', '$2y$12$W.Q6Fk3H5cA2nM8xL9pR0O3qN7J6kL5mP9V2sD4eW1r8Y3t6u9q', '08444444444', 'customer', 'Jl. Diponegoro No. 4, Jakarta', 'active'),
('Doni Pratama', 'doni@email.com', '$2y$12$W.Q6Fk3H5cA2nM8xL9pR0O3qN7J6kL5mP9V2sD4eW1r8Y3t6u9q', '08555555555', 'customer', 'Jl. Ahmad Yani No. 5, Jakarta', 'active');

-- Insert Fields
INSERT IGNORE INTO fields (name, type, capacity, price_per_hour, status, description) VALUES
('Futsal 1', 'futsal', 10, 150000.00, 'available', 'Lapangan futsal standar dengan fasilitas lengkap'),
('Futsal 2', 'futsal', 10, 150000.00, 'available', 'Lapangan futsal standar dengan fasilitas lengkap'),
('Badminton 1', 'badminton', 4, 80000.00, 'available', 'Lapangan badminton dengan AC'),
('Badminton 2', 'badminton', 4, 80000.00, 'available', 'Lapangan badminton dengan AC'),
('Badminton 3', 'badminton', 4, 80000.00, 'available', 'Lapangan badminton dengan AC');

-- ============================================
-- Views (Optional)
-- ============================================

-- View: Monthly Revenue
CREATE OR REPLACE VIEW vw_monthly_revenue AS
SELECT 
  DATE_TRUNC(DATE(t.payment_date), MONTH) AS bulan,
  SUM(t.amount) AS total_revenue,
  COUNT(DISTINCT t.id) AS jumlah_transaksi
FROM transactions t
WHERE t.status = 'completed'
GROUP BY DATE_TRUNC(DATE(t.payment_date), MONTH);

-- View: Field Statistics
CREATE OR REPLACE VIEW vw_field_statistics AS
SELECT 
  f.id,
  f.name,
  f.type,
  COUNT(r.id) AS total_reservations,
  SUM(CASE WHEN r.status = 'confirmed' THEN 1 ELSE 0 END) AS confirmed_count,
  SUM(CASE WHEN r.status = 'completed' THEN 1 ELSE 0 END) AS completed_count,
  SUM(r.total_price) AS total_revenue
FROM fields f
LEFT JOIN reservations r ON f.id = r.field_id
GROUP BY f.id, f.name, f.type;

-- ============================================
-- Stored Procedures (Optional)
-- ============================================

DELIMITER $$

-- Procedure: Check Field Availability
CREATE PROCEDURE sp_check_availability(
  IN p_field_id INT,
  IN p_date DATE,
  IN p_start_time TIME,
  IN p_end_time TIME,
  OUT p_is_available BOOLEAN
)
BEGIN
  DECLARE conflict_count INT DEFAULT 0;
  
  SELECT COUNT(*) INTO conflict_count
  FROM reservations
  WHERE field_id = p_field_id
    AND reservation_date = p_date
    AND status IN ('pending', 'confirmed')
    AND (
      (start_time < p_end_time AND end_time > p_start_time)
    );
  
  SET p_is_available = (conflict_count = 0);
END$$

-- Procedure: Create Reservation with Transaction
CREATE PROCEDURE sp_create_reservation(
  IN p_user_id INT,
  IN p_field_id INT,
  IN p_date DATE,
  IN p_start_time TIME,
  IN p_end_time TIME,
  IN p_notes TEXT
)
BEGIN
  DECLARE duration INT;
  DECLARE price DECIMAL(10,2);
  DECLARE field_price DECIMAL(10,2);
  
  -- Calculate duration
  SET duration = HOUR(TIMEDIFF(p_end_time, p_start_time));
  
  -- Get field price
  SELECT price_per_hour INTO field_price FROM fields WHERE id = p_field_id;
  
  -- Calculate total price
  SET price = duration * field_price;
  
  -- Insert reservation
  INSERT INTO reservations (user_id, field_id, reservation_date, start_time, end_time, duration_hours, total_price, notes)
  VALUES (p_user_id, p_field_id, p_date, p_start_time, p_end_time, duration, price, p_notes);
  
  -- Log activity
  INSERT INTO activity_logs (user_id, action, model_type, model_id, description)
  VALUES (p_user_id, 'create', 'Reservation', LAST_INSERT_ID(), CONCAT('Membuat reservasi untuk lapangan ', p_field_id));
END$$

DELIMITER ;

-- ============================================
-- Indexes for Performance
-- ============================================

CREATE INDEX idx_users_email_role ON users(email, role);
CREATE INDEX idx_fields_status_type ON fields(status, type);
CREATE INDEX idx_reservations_user_date ON reservations(user_id, reservation_date);
CREATE INDEX idx_reservations_field_date ON reservations(field_id, reservation_date);
CREATE INDEX idx_transactions_status_date ON transactions(status, payment_date);
CREATE INDEX idx_activity_logs_user_date ON activity_logs(user_id, created_at);

-- ============================================
-- End of Schema
-- ============================================
