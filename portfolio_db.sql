-- portfolio_db.sql
-- Run this SQL to set up the database for Vishalkumar Sarena's portfolio

-- 1. Create the database
CREATE DATABASE IF NOT EXISTS portfolio_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE portfolio_db;

-- 2. Contact messages table
CREATE TABLE IF NOT EXISTS contact_messages (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100)  NOT NULL,
  email       VARCHAR(150)  NOT NULL,
  subject     VARCHAR(255)  NOT NULL,
  message     TEXT          NOT NULL,
  is_read     TINYINT(1)    NOT NULL DEFAULT 0,
  created_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Optional: simple admin user table (for a future admin panel)
-- CREATE TABLE IF NOT EXISTS admin_users (
--   id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--   username     VARCHAR(60)  NOT NULL UNIQUE,
--   password_hash VARCHAR(255) NOT NULL,   -- use PHP password_hash()
--   created_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Useful query: view all messages newest first
SELECT id, name, email, subject, LEFT(message,80) AS preview, created_at
FROM contact_messages
ORDER BY created_at DESC;
