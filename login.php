<?php
session_start();
require 'db.php';  // Correct database connection

// Function to insert a user with a hashed password
function insertUser($pdo, $firstname, $lastname, $email, $password, $role) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO Users (firstname, lastname, password, email, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$firstname, $lastname, $hashed_password, $email, $role]);
    echo "User successfully added.";
}

// Uncomment the line below to insert a user (run this once and then comment it out again)
// insertUser($pdo, 'John', 'Doe', 'john.doe@example.com', 'password123', 'Admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if ($email && $password) {
        $stmt = $pdo->prepare("SELECT id, firstname, lastname, password, role FROM Users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();


        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['firstname'] . ' ' . $user['lastname'];
            $_SESSION['user_role'] = $user['role'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dolphin CRM</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <img src="dolphin.png" alt="Dolphin Logo">
        <h1>Dolphin CRM</h1>
    </header>
    <div class="login-container">
        <h1>Login</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
        <p>&copy; 2024 Dolphin CRM</p>
    </div>
</body>
</html>
