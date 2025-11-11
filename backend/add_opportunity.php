<?php
session_start();
require '../database/db.php';

// ✅ If authentication is enabled
// if (!isset($_SESSION['user_id'])) {
//     header("Location: ../auth/login.php");
//     exit();
// }

// Temporary user ID for testing
$user_id = $_SESSION['user_id'] ?? 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $country = trim($_POST['country']);
    $course = trim($_POST['course'] ?? '');
    $institution = trim($_POST['institution'] ?? '');
    $web = trim($_POST['web'] ?? '');
    $job_title = trim($_POST['job_title'] ?? '');
    $industry = trim($_POST['industry'] ?? '');
    $opp_url = trim($_POST['opp_url'] ?? '');
    $sponsorship = $_POST['sponsorship'] ?? 'Unsponsored';
    $type_id = intval($_POST['type_id']);

    // === Handle Image Upload ===
    $image_url = null;
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_tmp = $_FILES['image_url']['tmp_name'];
        $file_name = uniqid() . '_' . basename($_FILES['image_url']['name']);
        $target_path = $upload_dir . $file_name;

        if (move_uploaded_file($file_tmp, $target_path)) {
            $image_url = $target_path;
        }
    }

    // === Insert into database ===
    $sql = "INSERT INTO opportunities 
        (title, description, country, course, institution,co_web, job_title, industry, sponsorship, type_id, image_url,opp_url, posted_by) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssssssi",
        $title,
        $description,
        $country,
        $course,
        $institution,
        $web,
        $job_title,
        $industry,
        $sponsorship,
        $type_id,
        $image_url,
        $opp_url,
        $user_id
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = "✅ Opportunity added successfully!";
        header("Location: ../dashboard/index.php");
        exit();
    } else {
        $_SESSION['error'] = "❌ Failed to add opportunity: " . $conn->error;
        header("Location: ../dashboard/index.php");
        exit();
    }
}
?>
