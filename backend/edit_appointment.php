<?php
session_start();
require '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data safely
    $id = $_POST['id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $service_type = $_POST['service_type'];
    $preferred_datetime = $_POST['preferred_datetime']; // combined date & time
    $preferred_platform = $_POST['preferred_platform'];
    $message = $_POST['message'];

    // Prepare the update statement
    $stmt = $conn->prepare("
        UPDATE appointments SET 
            full_name = ?, 
            email = ?, 
            phone = ?, 
            service_type = ?, 
            preferred_datetime = ?, 
            preferred_platform = ?, 
            message = ?
        WHERE id = ?
    ");

    if ($stmt === false) {
        $_SESSION['error'] = "Failed to prepare statement: " . $conn->error;
        header("Location: ../admin/appointments.php");
        exit;
    }

    $stmt->bind_param(
        "sssssssi",
        $full_name,
        $email,
        $phone,
        $service_type,
        $preferred_datetime,
        $preferred_platform,
        $message,
        $id
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = "Appointment updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update appointment: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: ../dashboard/appointments.php");
    exit;
} else {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../dashboard/appointments.php");
    exit;
}
?>
