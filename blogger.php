<?php
session_start();
include('../database/db.php');

// Initialize messages
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $author = $_SESSION['user_name'] ?? 'Unknown';
    $date = date('Y-m-d');

    // ---- Banner Upload ----
    $bannerPath = '';
    if (!empty($_FILES['banner']['name'])) {
        $uploadDir = '../uploads/posts/'; // safer path outside public HTML if possible
        $fileTmpPath = $_FILES['banner']['tmp_name'];
        $fileName = basename($_FILES['banner']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($fileExt, $allowedExtensions)) {
            $error = "Invalid image format. Only JPG, PNG, and WEBP are allowed.";
        } else {
            $newFileName = uniqid('banner_', true) . '.' . $fileExt;
            $destPath = $uploadDir . $newFileName;

            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $bannerPath = $destPath;
            } else {
                $error = "Banner upload failed.";
            }
        }
    } else {
        $error = "Please upload a banner image.";
    }

    // ---- Build Content JSON ----
    $types = $_POST['type'] ?? [];
    $headings = $_POST['heading'] ?? [];
    $bodies = $_POST['body'] ?? [];
    $urls = $_POST['url'] ?? [];
    $captions = $_POST['caption'] ?? [];

    $content = [];
    for ($i = 0; $i < count($types); $i++) {
        $block = ["type" => $types[$i]];

        if ($types[$i] === "text") {
            $block["heading"] = $headings[$i] ?? '';
            $block["body"] = $bodies[$i] ?? '';
        } elseif ($types[$i] === "image") {
            $block["url"] = $urls[$i] ?? '';
            $block["caption"] = $captions[$i] ?? '';
        }
        $content[] = $block;
    }

    // ---- Create JSON ----
    $blog_data = [
        "title" => $title,
        "author" => $author,
        "date" => $date,
        "banner" => $bannerPath,
        "content" => $content
    ];
    $json_string = json_encode($blog_data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

    // ---- Save to Database ----
    if (empty($error)) {
        $stmt = $conn->prepare("INSERT INTO blog (blog_json) VALUES (?)");
        $stmt->bind_param("s", $json_string);

        if ($stmt->execute()) {
            $success = "✅ Blog post created successfully!";
        } else {
            $error = "Database error: " . htmlspecialchars($stmt->error);
        }

        $stmt->close();
    }

    $conn->close();
}
?>
