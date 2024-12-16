<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$filter = $_GET['filter'] ?? 'All';
$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM Contacts";
$params = [];

if ($filter === 'Sales Leads') {
    $query .= " WHERE type = ?";
    $params[] = 'Sales Lead';
} elseif ($filter === 'Support') {
    $query .= " WHERE type = ?";
    $params[] = 'Support';
} elseif ($filter === 'Assigned') {
    $query .= " WHERE assigned_to = ?";
    $params[] = $user_id;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$contacts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        /* Styling for navigation */
        .header { display: flex; justify-content: space-between; align-items: center; padding: 10px; background-color: #f8f9fa; }
        .header a { text-decoration: none; margin: 0 10px; color: #333; }
        .header a:hover { color: #007bff; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['user_id']); ?>!</h2>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="view_users.php">View Users</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>

    <h1>Dashboard</h1>
    <div>
        <a href="?filter=All">All Contacts</a> |
        <a href="?filter=Sales Leads">Sales Leads</a> |
        <a href="?filter=Support">Support</a> |
        <a href="?filter=Assigned">Assigned to Me</a>
    </div>
    <a href="add_contact.php"><button style="margin: 10px 0;">Add New Contact</button></a>

    <table border="1">
        <tr>
            <th>Title</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Company</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($contacts as $contact): ?>
            <tr>
                <td><?= htmlspecialchars($contact['title']); ?></td>
                <td><?= htmlspecialchars($contact['firstname'] . " " . $contact['lastname']); ?></td>
                <td><?= htmlspecialchars($contact['email']); ?></td>
                <td><?= htmlspecialchars($contact['company']); ?></td>
                <td><?= htmlspecialchars($contact['type']); ?></td>
                <td>
                    <a href="view_contact.php?id=<?= $contact['id']; ?>">View Details</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
