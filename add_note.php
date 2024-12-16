<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to add a note.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contact_id = $_POST['contact_id'] ?? null;
    $comment = htmlspecialchars($_POST['comment']);
    $created_by = $_SESSION['user_id'];

    if (!$contact_id || empty($comment)) {
        die("Contact ID and comment are required.");
    }

    $stmt = $pdo->prepare("INSERT INTO Notes (contact_id, comment, created_by) VALUES (?, ?, ?)");
    $stmt->execute([$contact_id, $comment, $created_by]);

    echo "Note added successfully!";
}
?>
