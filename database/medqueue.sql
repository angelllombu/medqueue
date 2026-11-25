-- MedQueue database (6 tabel + seed data)
-- Import lewat phpMyAdmin: tab Import -> pilih file ini -> Go
-- Akun seed:
--   admin  / admin123   (role admin)
--   pasien1 / pasien123 (role patient, untuk uji)

CREATE DATABASE IF NOT EXISTS medqueue
  CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE medqueue;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS queues;
DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS patients;
DROP TABLE IF EXISTS schedules;
DROP TABLE IF EXISTS doctors;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('patient','admin') NOT NULL DEFAULT 'patient',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE doctors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  specialization VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE schedules (
  id INT AUTO_INCREMENT PRIMARY KEY,
  doctor_id INT NOT NULL,
  date DATE NOT NULL,
  time TIME NOT NULL,
  quota INT NOT NULL DEFAULT 10,
  FOREIGN KEY (doctor_id) REFERENCES doctors(id),
  INDEX idx_schedule_date (date)
) ENGINE=InnoDB;

CREATE TABLE patients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  name VARCHAR(100) NOT NULL,
  phone VARCHAR(20) NULL,
  FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE appointments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL,
  schedule_id INT NOT NULL,
  status ENUM('booked','cancelled','done') NOT NULL DEFAULT 'booked',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (patient_id) REFERENCES patients(id),
  FOREIGN KEY (schedule_id) REFERENCES schedules(id),
  INDEX idx_app_schedule (schedule_id, status)
) ENGINE=InnoDB;

CREATE TABLE queues (
  id INT AUTO_INCREMENT PRIMARY KEY,
  appointment_id INT NOT NULL UNIQUE,
  queue_number VARCHAR(10) NOT NULL,
  status ENUM('waiting','called','done','skipped') NOT NULL DEFAULT 'waiting',
  FOREIGN KEY (appointment_id) REFERENCES appointments(id)
) ENGINE=InnoDB;

-- ===== SEED DATA =====
INSERT INTO users (name, username, password, role) VALUES
('Admin Klinik', 'admin',   '$2y$10$5LudOMWxVOb3h.nprxpgOeZGxMc.OhNyzU4JUbCkSuUyxALT42sRO', 'admin'),
('Pasien Uji',   'pasien1', '$2y$10$M/eX4KxF99rCdpaQyHXsYu6qmTSHA1AaLi0GLyUV7EFkaPhmXJf82', 'patient');

INSERT INTO patients (user_id, name, phone) VALUES
(2, 'Pasien Uji', '081234567890');

INSERT INTO doctors (name, specialization) VALUES
('dr. Andi Pratama',  'Umum'),
('drg. Sari Wulandari', 'Gigi'),
('dr. Budi Santoso',  'Umum');

-- Jadwal contoh: 7 hari ke depan, dihitung dari saat import
INSERT INTO schedules (doctor_id, date, time, quota) VALUES
(1, CURDATE() + INTERVAL 1 DAY, '09:00:00', 5),
(2, CURDATE() + INTERVAL 1 DAY, '10:00:00', 3),
(3, CURDATE() + INTERVAL 2 DAY, '09:00:00', 5),
(1, CURDATE() + INTERVAL 3 DAY, '13:00:00', 5),
(2, CURDATE() + INTERVAL 4 DAY, '09:30:00', 4),
(3, CURDATE() + INTERVAL 5 DAY, '14:00:00', 5);
