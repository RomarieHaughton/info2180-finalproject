<?php
session_start();
require_once 'db.php';

// Ensure only Admins can add users
if ($_SESSION['role'] !== 'Admin') {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Password validation: at least 1 letter, 1 number, 1 uppercase, min 8 chars
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/', $password)) {
        die("Password must include at least 8 characters, 1 number, and 1 uppercase letter.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO Users (firstname, lastname, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$firstname, $lastname, $email, $hashed_password, $role]);

    echo "User successfully added.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User - Dolphin CRM</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <img src="dolphin.png" alt="Dolphin Logo">
        <h1>Dolphin CRM</h1>
    </header>
    <div class="login-container">
        <h1>Add User</h1>
        <form id="addUserForm" action="add_user.php" method="POST">
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" placeholder="First Name" required><br>
            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" placeholder="Last Name" required><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Email" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password" required><br>
            <label for="role">Role:</label>
            <select id="role" name="role">
                <option value="Admin">Admin</option>
                <option value="Member">Member</option>
            </select><br>
            <button type="submit">Add User</button>
        </form>
        <p>&copy; 2024 Dolphin CRM</p>
    </div>
</body>
</html>