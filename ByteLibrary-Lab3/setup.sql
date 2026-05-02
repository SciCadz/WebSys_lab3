-- ============================================================
--  ByteLibrary — Database Setup
--  File: setup.sql
--  Run this ONCE in phpMyAdmin or MySQL CLI:
--    mysql -u root -p < setup.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS bytelibrary_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE bytelibrary_db;

-- ── USERS (library staff accounts) ──────────────────────────
CREATE TABLE IF NOT EXISTS users (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  display_name VARCHAR(150) NOT NULL,
  username     VARCHAR(80)  UNIQUE NOT NULL,
  password     VARCHAR(255) NOT NULL,        -- bcrypt hash
  role         ENUM('Librarian','Admin') DEFAULT 'Librarian',
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── BOOKS ───────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS books (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  isbn           VARCHAR(20) UNIQUE,
  title          VARCHAR(255) NOT NULL,
  author         VARCHAR(150),
  genre          VARCHAR(80),
  publisher      VARCHAR(150),
  year_published YEAR,
  copies         INT DEFAULT 1,
  description    TEXT,
  status         ENUM('Available','Borrowed','Overdue') DEFAULT 'Available',
  created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── BORROWERS (library members) ─────────────────────────────
CREATE TABLE IF NOT EXISTS borrowers (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(150) NOT NULL,
  email      VARCHAR(150) UNIQUE,
  phone      VARCHAR(30),
  status     ENUM('Active','Suspended') DEFAULT 'Active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── LOANS ───────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS loans (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  book_id     INT NOT NULL,
  borrower_id INT NOT NULL,
  issue_date  DATE NOT NULL,
  due_date    DATE NOT NULL,
  return_date DATE NULL,
  penalty     DECIMAL(8,2) DEFAULT 0.00,
  status      ENUM('Active','Returned','Overdue') DEFAULT 'Active',
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (book_id)     REFERENCES books(id)     ON DELETE CASCADE,
  FOREIGN KEY (borrower_id) REFERENCES borrowers(id) ON DELETE CASCADE
);

-- ── SAMPLE DATA ─────────────────────────────────────────────
INSERT IGNORE INTO books (isbn, title, author, genre, publisher, year_published, copies, status) VALUES
('978-0-7432-7356-5', 'The Great Gatsby',        'F. Scott Fitzgerald', 'Fiction',     'Scribner',     1925, 2, 'Available'),
('978-0-06-112008-4', 'To Kill a Mockingbird',   'Harper Lee',          'Fiction',     'HarperCollins',1960, 1, 'Borrowed'),
('978-0-14-028329-7', '1984',                    'George Orwell',       'Fiction',     'Penguin',      1949, 3, 'Available'),
('978-0-7432-7357-2', 'Sapiens',                 'Yuval Noah Harari',   'History',     'Harper',       2011, 2, 'Borrowed'),
('978-0-525-55360-5', 'Educated',                'Tara Westover',       'Biography',   'Random House', 2018, 1, 'Overdue'),
('978-0-385-33348-1', 'The Alchemist',           'Paulo Coelho',        'Fiction',     'HarperOne',    1988, 2, 'Available'),
('978-0-14-243723-0', 'A Brief History of Time', 'Stephen Hawking',     'Science',     'Bantam',       1988, 1, 'Borrowed'),
('978-0-385-54734-9', 'Atomic Habits',           'James Clear',         'Non-Fiction', 'Penguin',      2018, 2, 'Available');

INSERT IGNORE INTO borrowers (name, email, phone, status) VALUES
('Juan Dela Cruz',  'juan@email.com',  '0917-123-4567', 'Active'),
('Maria Santos',    'maria@email.com', '0918-987-6543', 'Active'),
('Ana Cruz',        'ana@email.com',   '0920-111-2233', 'Active'),
('Pedro Bautista',  'pedro@email.com', '0918-999-8877', 'Active'),
('Rosa Gonzales',   'rosa@email.com',  '0919-444-5566', 'Suspended');

INSERT IGNORE INTO loans (book_id, borrower_id, issue_date, due_date, status) VALUES
(2, 2, '2025-06-01', '2025-06-15', 'Active'),
(4, 3, '2025-05-20', '2025-06-03', 'Active'),
(5, 3, '2025-05-10', '2025-05-24', 'Overdue'),
(7, 2, '2025-06-05', '2025-06-19', 'Active'),
(3, 1, '2025-05-08', '2025-05-22', 'Overdue');
