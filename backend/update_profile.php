<?php
session_start();
require '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $f_name = trim($_POST['f_name']);
    $l_name = trim($_POST['l_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($f_name) || empty($l_name) || empty($email)) {
        $_SESSION['error'] = "Please fill in all required fields.";
        header("Location: ../dashboard/profile.php");
        exit;
    }

    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET f_name = ?, l_name = ?, email = ?, password_hash = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $f_name, $l_name, $email, $password_hash, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET f_name = ?, l_name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("sssi", $f_name, $l_name, $email, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update profile: " . $stmt->error;
    }

    header("Location: ../dashboard/profile.php");
    exit;
}
?>
