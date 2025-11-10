<?php
session_start();
require '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $title = trim($_POST['title']);
    $country = trim($_POST['country']);
    $sponsorship = trim($_POST['sponsorship']);
    $description = trim($_POST['description']);
    $institution = trim($_POST['institution'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $posted_by = $_SESSION['user_id'] ?? 1; // default fallback

    // Handle image upload if any
    $image_url = null;
    if (!empty($_FILES['image_url']['name'])) {
        $target_dir = "../uploads/opportunities/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $file_name = time() . '_' . basename($_FILES["image_url"]["name"]);
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($file_type, $allowed)) {
            if (move_uploaded_file($_FILES["image_url"]["tmp_name"], $target_file)) {
                $image_url = "uploads/opportunities/" . $file_name;
            }
        }
    }

    // Prepare SQL update
    if ($image_url) {
        $sql = "UPDATE opportunities 
                SET title=?, country=?, sponsorship=?, description=?, institution=?, course=?, image_url=? 
                WHERE id=? AND posted_by=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssii", $title, $country, $sponsorship, $description, $institution, $course, $image_url, $id, $posted_by);
    } else {
        $sql = "UPDATE opportunities 
                SET title=?, country=?, sponsorship=?, description=?, institution=?, course=? 
                WHERE id=? AND posted_by=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssii", $title, $country, $sponsorship, $description, $institution, $course, $id, $posted_by);
    }

    // Execute and redirect
    if ($stmt->execute()) {
        $_SESSION['success'] = "Opportunity updated successfully.";
    } else {
        $_SESSION['error'] = "Error updating opportunity. Try again.";
    }

    header("Location: ../dashboard/index.php");
    exit();
} else {
    header("Location: ../dashboard/index.php");
    exit();
}
?>
