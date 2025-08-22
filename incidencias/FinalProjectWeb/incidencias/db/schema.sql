CREATE DATABASE IF NOT EXISTS incidencias_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE incidencias_db;


CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','user') DEFAULT 'user'
);


CREATE TABLE IF NOT EXISTS incidents (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  user_id INT NULL,
  CONSTRAINT fk_incident_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

