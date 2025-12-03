<?php
// save_application.php

// 1. Database connection settings
$host     = "192.168.1.9";
$dbname   = "customer_db2";
$username = "webuser2";
$password = "password123";

try {
    // 2. Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 3. Check if form submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Simple validation
        $full_name = trim($_POST['full_name'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $phone     = trim($_POST['phone'] ?? '');
        $position  = trim($_POST['position'] ?? '');

        if ($full_name === '' || $email === '') {
            echo "<p class='message'>Name and email are required.</p>";
            exit;
        }

        // 4. Insert using prepared statement (prevents SQL injection)
        $sql = "INSERT INTO applications (full_name, email, phone, position)
                VALUES (:full_name, :email, :phone, :position)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':full_name' => $full_name,
            ':email'     => $email,
            ':phone'     => $phone,
            ':position'  => $position,
        ]);

        echo "<p class='message'>Application submitted successfully!</p>";
    } else {
        echo "<p class='message'>Invalid request.</p>";
    }

} catch (PDOException $e) {
    echo "<p class='message'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
