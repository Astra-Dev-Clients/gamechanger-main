<?php
session_start();
require '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../dashboard/index.php");
    exit();
}

// Ensure these values exist
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$country = isset($_POST['country']) ? trim($_POST['country']) : '';
$sponsorship = isset($_POST['sponsorship']) ? trim($_POST['sponsorship']) : '';
$description = isset($_POST['description']) ? trim($_POST['description']) : '';
$institution = isset($_POST['institution']) ? trim($_POST['institution']) : '';
$web = isset($_POST['web']) ? trim($_POST['web']) : '';
$course = isset($_POST['course']) ? trim($_POST['course']) : '';
$opp_url = isset($_POST['opp_url']) ? trim($_POST['opp_url']) : '';
$posted_by = $_SESSION['user_id'] ?? 1; // fallback

// Initialize to avoid undefined variable warnings
$image_url = null;

// Handle image upload if provided
if (isset($_FILES['image_url']) && !empty($_FILES['image_url']['name'])) {
    $target_dir = __DIR__ . "/../uploads/opportunities/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = time() . '_' . basename($_FILES["image_url"]["name"]);
    $target_file = $target_dir . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($file_type, $allowed)) {
        if (move_uploaded_file($_FILES["image_url"]["tmp_name"], $target_file)) {
            // store relative path used in your UI
            $image_url = "uploads/opportunities/" . $file_name;
        } else {
            $_SESSION['error'] = "Failed to move uploaded file.";
            header("Location: ../dashboard/index.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid image type. Allowed: jpg, jpeg, png, webp.";
        header("Location: ../dashboard/index.php");
        exit();
    }
}

// Prepare SQL update
if ($image_url) {
    // NOTE: there's a comma between image_url and opp_url
    $sql = "UPDATE opportunities 
            SET title=?, country=?, sponsorship=?, description=?, institution=?, co_web=?, course=?, image_url=?, opp_url=?
            WHERE id=? AND posted_by=?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $_SESSION['error'] = "Prepare failed: " . $conn->error;
        header("Location: ../dashboard/index.php");
        exit();
    }
    // 9 strings then 2 integers -> 11 params total
    $stmt->bind_param(
        "sssssssssii",
        $title, $country, $sponsorship, $description, $institution,
        $web, $course, $image_url, $opp_url, $id, $posted_by
    );
} else {
    // No image provided: don't update image_url
    $sql = "UPDATE opportunities 
            SET title=?, country=?, sponsorship=?, description=?, institution=?, co_web=?, course=?, opp_url=?
            WHERE id=? AND posted_by=?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $_SESSION['error'] = "Prepare failed: " . $conn->error;
        header("Location: ../dashboard/index.php");
        exit();
    }
    // 8 strings then 2 integers -> 10 params total
    $stmt->bind_param(
        "ssssssssii",
        $title, $country, $sponsorship, $description, $institution,
        $web, $course, $opp_url, $id, $posted_by
    );
}

// Execute and redirect
if ($stmt->execute()) {
    $_SESSION['success'] = "Opportunity updated successfully.";
} else {
    $_SESSION['error'] = "Error updating opportunity: " . $stmt->error;
}

$stmt->close();
header("Location: ../dashboard/index.php");
exit();
?>
