<?php
// Redirect to login.php if the user is not logged in
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
} else {
    // Redirect to dashboard if already logged in
    header("Location: dashboard.php");
    exit();
}
