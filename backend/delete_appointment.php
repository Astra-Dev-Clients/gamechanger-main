<?php
session_start();
require '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];
  $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  $_SESSION['success'] = "Appointment deleted successfully!";
  header("Location: ../dashboard/appointments.php");
  exit;
}
?>
