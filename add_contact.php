<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $telephone = htmlspecialchars($_POST['telephone']);
    $company = htmlspecialchars($_POST['company']);
    $type = $_POST['type'];
    $assigned_to = $_POST['assigned_to'];
    $created_by = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO Contacts (title, firstname, lastname, email, telephone, company, type, assigned_to, created_by) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $firstname, $lastname, $email, $telephone, $company, $type, $assigned_to, $created_by]);

    echo "Contact added successfully!";
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Contact</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <!-- HTML Form -->
    <form method="POST" action="add_contact.php">
        <input type="text" name="title" placeholder="Title"><br>
        <input type="text" name="firstname" placeholder="First Name" required><br>
        <input type="text" name="lastname" placeholder="Last Name" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="text" name="telephone" placeholder="Telephone"><br>
        <input type="text" name="company" placeholder="Company"><br>
        <select name="type">
            <option value="Sales Lead">Sales Lead</option>
            <option value="Support">Support</option>
        </select><br>
        <button type="submit">Add Contact</button>
    </form>
</body>
</html>