ByteLibrary — PHP + MySQL Version
==================================

SETUP INSTRUCTIONS
------------------
1. Copy the ByteLibrary-PHP folder into your web server root:
     XAMPP  → C:/xampp/htdocs/ByteLibrary-PHP/
     WAMP   → C:/wamp64/www/ByteLibrary-PHP/
     LAMP   → /var/www/html/ByteLibrary-PHP/

2. Create the database and tables:
   - Open phpMyAdmin → SQL tab
   - Paste and run the contents of setup.sql
   OR run in terminal:
     mysql -u root -p < setup.sql

3. Edit includes/db.php if needed:
     DB_USER → your MySQL username (default: root)
     DB_PASS → your MySQL password (default: empty)

4. Open in browser:
     http://localhost/ByteLibrary-PHP/login.php

5. Register a new account on the register page.
   (No default credentials — you must create one.)

FILES
-----
login.php        — Login (checks username + password against DB)
register.php     — Register (checks duplicate username, hashes password)
logout.php       — Destroys session, redirects to login
dashboard.php    — Live stats from DB
books.php        — Book list with search, filter, delete
add_book.php     — Add book form → inserts into books table
borrowers.php    — Borrower list, register borrower, suspend/activate
status.php       — Active loans and overdue from loans table
erd.php          — Database design / ERD page

includes/
  db.php         — MySQL connection
  auth.php       — Session guard (redirects to login if not logged in)
  sidebar.php    — Shared sidebar partial

css/style.css    — All styles
setup.sql        — Database schema + sample data

REQUIREMENTS
------------
- PHP 8.0+
- MySQL 5.7+ / MariaDB 10+
- XAMPP / WAMP / LAMP or any PHP server
