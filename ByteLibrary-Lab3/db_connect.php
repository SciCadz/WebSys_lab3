<?php
// ============================================================
//  ByteLibrary — Database Connection
//  Lab Activity 3 · Part 2: Create Database Connection
//  File: db_connect.php
// ============================================================

$conn = new mysqli("localhost", "root", "", "bytelibrary_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
