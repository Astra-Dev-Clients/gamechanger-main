<?php
session_start();
require '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and fetch form data
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $service_type = trim($_POST['service_type']);
    $preferred_datetime = $_POST['preferred_datetime'] ?? null; // from form
    $preferred_platform = trim($_POST['preferred_platform']);
    $message = trim($_POST['message']);

    // Convert datetime-local input to MySQL DATETIME format
    $preferred_datetime_mysql = null;
    if (!empty($preferred_datetime)) {
        $dt = DateTime::createFromFormat('Y-m-d\TH:i', $preferred_datetime);
        if ($dt !== false) {
            $preferred_datetime_mysql = $dt->format('Y-m-d H:i:s');
        }
    }

    // Prepare insert statement
    $stmt = $conn->prepare("
        INSERT INTO appointments 
        (full_name, email, phone, service_type, preferred_datetime, preferred_platform, message)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    

    if ($stmt) {
        $stmt->bind_param(
            "sssssss",
            $full_name,
            $email,
            $phone,
            $service_type,
            $preferred_datetime_mysql,
            $preferred_platform,
            $message
        );

        $stmt->execute();
        $stmt->close();

        $_SESSION['success'] = "Appointment added successfully!";
    } else {
        $_SESSION['error'] = "Failed to prepare statement: " . $conn->error;
    }

    header("Location: ../dashboard/appointments.php");
    exit;
}
?>
