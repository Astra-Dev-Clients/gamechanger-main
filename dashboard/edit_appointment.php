<?php
session_start();
require '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];
  $full_name = $_POST['full_name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $service = $_POST['service'];
  $date = $_POST['appointment_date'];
  $time = $_POST['appointment_time'];
  $notes = $_POST['notes'];

  $stmt = $conn->prepare("UPDATE appointments SET full_name=?, email=?, phone=?, service=?, appointment_date=?, appointment_time=?, notes=? WHERE id=?");
  $stmt->bind_param("sssssssi", $full_name, $email, $phone, $service, $date, $time, $notes, $id);
  $stmt->execute();

  $_SESSION['success'] = "Appointment updated successfully!";
  header("Location: ../admin/appointments.php");
  exit;
}
?>
