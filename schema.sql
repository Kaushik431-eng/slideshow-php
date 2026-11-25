CREATE DATABASE IF NOT EXISTS slideshow CHARACTER SET utf8mb4;
USE slideshow;
CREATE TABLE IF NOT EXISTS visitors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  token_hash CHAR(64) NOT NULL UNIQUE,          -- sha256 of cookie token; raw token never stored
  name VARCHAR(40) NOT NULL,
  consent_at DATETIME NOT NULL,
  consent_version VARCHAR(10) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS photos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  visitor_id INT NOT NULL,
  filename VARCHAR(64) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (visitor_id) REFERENCES visitors(id) ON DELETE CASCADE,
  INDEX (created_at)
);
-- hello word