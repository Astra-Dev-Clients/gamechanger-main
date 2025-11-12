<?php
session_start();
require '../database/db.php';

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        die("Invalid blog ID.");
    }

    // Optional: fetch blog to delete banner image
    $stmt = $conn->prepare("SELECT banner FROM blog_posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $blog = $result->fetch_assoc();
    $stmt->close();

    if (!$blog) {
        die("Blog post not found.");
    }

    // Delete the blog record
    $stmt = $conn->prepare("DELETE FROM blog_posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $deleted = $stmt->execute();
    $stmt->close();

    // Delete banner file if exists
    if ($deleted && !empty($blog['banner']) && file_exists("../" . $blog['banner'])) {
        unlink("../" . $blog['banner']);
    }

    // Redirect back to dashboard (optional: add success message via GET)
    header("Location: ../dashboard/blog.php");
    exit;
} else {
    die("Invalid request method.");
}
?>
