<?php
session_start();
require_once 'db.php';

if ($_SESSION['role'] !== 'Admin') {
    die("Unauthorized access.");
}

$stmt = $pdo->query("SELECT firstname, lastname, email, role, created_at FROM Users");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Users</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>List of Users</h2>
    <table border="1">
        <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Date Created</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['firstname'] . " " . $user['lastname']); ?></td>
                <td><?= htmlspecialchars($user['email']); ?></td>
                <td><?= htmlspecialchars($user['role']); ?></td>
                <td><?= htmlspecialchars($user['created_at']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
