USE incidencias_db;


CREATE TABLE IF NOT EXISTS provinces (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS municipalities (
  id INT AUTO_INCREMENT PRIMARY KEY,
  province_id INT NOT NULL,
  name VARCHAR(120) NOT NULL,
  CONSTRAINT fk_muni_prov FOREIGN KEY (province_id) REFERENCES provinces(id) ON DELETE CASCADE,
  UNIQUE KEY uniq_muni (province_id, name)
);

CREATE TABLE IF NOT EXISTS barrios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  municipality_id INT NOT NULL,
  name VARCHAR(120) NOT NULL,
  CONSTRAINT fk_barrio_muni FOREIGN KEY (municipality_id) REFERENCES municipalities(id) ON DELETE CASCADE,
  UNIQUE KEY uniq_barrio (municipality_id, name)
);

CREATE TABLE IF NOT EXISTS incident_types (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL UNIQUE,
  icon VARCHAR(16) NULL 
);


CREATE TABLE IF NOT EXISTS incident_type_pivot (
  incident_id INT NOT NULL,
  type_id INT NOT NULL,
  PRIMARY KEY (incident_id, type_id),
  CONSTRAINT fk_pivot_inc FOREIGN KEY (incident_id) REFERENCES incidents(id) ON DELETE CASCADE,
  CONSTRAINT fk_pivot_type FOREIGN KEY (type_id) REFERENCES incident_types(id) ON DELETE CASCADE
);


ALTER TABLE incidents
  ADD COLUMN occurred_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ADD COLUMN province_id INT NULL,
  ADD COLUMN municipality_id INT NULL,
  ADD COLUMN barrio_id INT NULL,
  ADD COLUMN lat DECIMAL(10,7) NULL,
  ADD COLUMN lng DECIMAL(10,7) NULL,
  ADD COLUMN dead INT NULL,
  ADD COLUMN injured INT NULL,
  ADD COLUMN loss_rd DECIMAL(12,2) NULL,
  ADD COLUMN social_link VARCHAR(255) NULL,
  ADD COLUMN photo VARCHAR(255) NULL,
  ADD COLUMN status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  ADD COLUMN merged_into_id INT NULL,
  ADD INDEX idx_inc_time (occurred_at),
  ADD INDEX idx_inc_status (status),
  ADD INDEX idx_inc_geo (lat, lng);

ALTER TABLE incidents
  ADD CONSTRAINT fk_inc_prov FOREIGN KEY (province_id) REFERENCES provinces(id) ON DELETE SET NULL,
  ADD CONSTRAINT fk_inc_muni FOREIGN KEY (municipality_id) REFERENCES municipalities(id) ON DELETE SET NULL,
  ADD CONSTRAINT fk_inc_barrio FOREIGN KEY (barrio_id) REFERENCES barrios(id) ON DELETE SET NULL,
  ADD CONSTRAINT fk_inc_merged FOREIGN KEY (merged_into_id) REFERENCES incidents(id) ON DELETE SET NULL;


CREATE TABLE IF NOT EXISTS comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  incident_id INT NOT NULL,
  user_id INT NULL,
  body TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_comment_inc FOREIGN KEY (incident_id) REFERENCES incidents(id) ON DELETE CASCADE,
  CONSTRAINT fk_comment_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);


CREATE TABLE IF NOT EXISTS corrections (
  id INT AUTO_INCREMENT PRIMARY KEY,
  incident_id INT NOT NULL,
  user_id INT NULL,
  field VARCHAR(40) NOT NULL, 
  new_value VARCHAR(255) NULL, 
  new_lat DECIMAL(10,7) NULL,  
  new_lng DECIMAL(10,7) NULL,
  status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  reviewed_at DATETIME NULL,
  reviewed_by INT NULL,
  CONSTRAINT fk_corr_inc FOREIGN KEY (incident_id) REFERENCES incidents(id) ON DELETE CASCADE,
  CONSTRAINT fk_corr_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Semillas mínimas
INSERT IGNORE INTO incident_types (name, icon) VALUES
('accidente','🚗'),('pelea','🥊'),('robo','🕵️'),('desastre','🌪️');

INSERT IGNORE INTO provinces (name) VALUES ('Santo Domingo'),('Distrito Nacional');
INSERT IGNORE INTO municipalities (province_id,name)
  SELECT p.id,'Santo Domingo Este' FROM provinces p WHERE p.name='Santo Domingo'
  UNION ALL SELECT p.id,'Santo Domingo Norte' FROM provinces p WHERE p.name='Santo Domingo'
  UNION ALL SELECT p.id,'Santo Domingo Oeste' FROM provinces p WHERE p.name='Santo Domingo';
