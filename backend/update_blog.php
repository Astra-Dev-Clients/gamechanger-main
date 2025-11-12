<?php
require '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $title = $_POST['title'];
    $content_json = $_POST['content_json'];

    // Fetch existing blog
    $query = $conn->prepare("SELECT banner FROM blog_posts WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $old = $query->get_result()->fetch_assoc();
    $banner_path = $old['banner'];

    // Upload new banner if exists
    if (!empty($_FILES['banner']['name'])) {
        $banner_name = "banner_" . uniqid() . "." . pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION);
        $banner_path = "./posts/" . $banner_name;
        move_uploaded_file($_FILES['banner']['tmp_name'], $banner_path);
    }

    // Update record
    $stmt = $conn->prepare("UPDATE blog_posts SET title=?, blog_json=?, banner=? WHERE id=?");
    $stmt->bind_param("sssi", $title, $content_json, $banner_path, $id);
    $stmt->execute();

    header("Location: ../dashboard/blog.php");
    exit();
}
?>
