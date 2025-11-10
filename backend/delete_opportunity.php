<?php
session_start();
require '../database/db.php';

// Ensure user is authenticated
// if (!isset($_SESSION['user_id'])) {
//     header("Location: ../auth/login.php");
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $opportunity_id = intval($_POST['id']);
    $user_id = $_SESSION['user_id'] ?? 1; // Use actual session user ID

    // Fetch image URL (to optionally delete the image file later)
    $query = $conn->prepare("SELECT image_url FROM opportunities WHERE id = ? AND posted_by = ?");
    $query->bind_param("ii", $opportunity_id, $user_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Opportunity not found or you don’t have permission.";
        header("Location: ../dashboard/index.php");
        exit();
    }

    $data = $result->fetch_assoc();
    $imagePath = $data['image_url'];

    // Delete from database
    $stmt = $conn->prepare("DELETE FROM opportunities WHERE id = ? AND posted_by = ?");
    $stmt->bind_param("ii", $opportunity_id, $user_id);

    if ($stmt->execute()) {
        // Delete image file if it exists
        if (!empty($imagePath) && file_exists("../uploads/" . basename($imagePath))) {
            unlink("../uploads/" . basename($imagePath));
        }

        $_SESSION['success'] = "Opportunity deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting opportunity. Try again.";
    }

    header("Location: ../dashboard/index.php");
    exit();
} else {
    header("Location: ../dashboard/index.php");
    exit();
}



?>
