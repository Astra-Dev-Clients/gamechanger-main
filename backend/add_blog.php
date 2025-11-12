<?php
session_start();
require '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_SESSION['user_name'] ?? 'Admin';
    $date = date('Y-m-d H:i:s');

    // Handle banner upload
    $bannerPath = '';
    if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = './posts/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $fileTmpPath = $_FILES['banner']['tmp_name'];
        $fileName = basename($_FILES['banner']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFileName = uniqid('banner_', true) . '.' . $fileExt;
        $destPath = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $bannerPath = $destPath;
        } else {
            $error = "Banner upload failed!";
        }
    } else {
        $error = "Banner is required!";
    }

    // Get Quill JSON content (Delta) from hidden input
    $quillContentJson = $_POST['content_json'] ?? '';

    if (!empty($quillContentJson)) {
        $blog_data = [
            'title' => $title,
            'author' => $author,
            'date' => $date,
            'banner' => $bannerPath,
            'content' => json_decode($quillContentJson, true) // Delta array
        ];

        $json_string = json_encode($blog_data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        $stmt = $conn->prepare("INSERT INTO blog_posts (title, author, banner, blog_json) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $author, $bannerPath, $json_string);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Blog created successfully!";
            header("Location: ../dashboard/blog.php");
            exit;
        } else {
            $error = "Insert failed: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Blog content is required!";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Blog</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<style>
body { background-color: #f8f9fa; }
.quill-editor { min-height: 300px; }
</style>
</head>
<body>

<div class="container py-5">
    <div class="card p-4 shadow">
        <h2 class="mb-4">Create Blog Post</h2>

        <?php if(!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" id="blogForm">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Banner Image</label>
                <input type="file" name="banner" class="form-control" accept="image/*" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Content</label>
                <div id="quillEditor"></div>
                <input type="hidden" name="content_json" id="content_json">
            </div>

            <button type="submit" class="btn btn-primary">Save Blog Post</button>
        </form>
    </div>
</div>

<script>
// Initialize Quill
var quill = new Quill('#quillEditor', {
    theme: 'snow',
    placeholder: 'Write your blog content here...',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline'],
            ['link', 'image'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            ['clean']
        ]
    }
});

// On form submit, save Quill content as Delta JSON
$('#blogForm').on('submit', function() {
    const delta = quill.getContents();
    $('#content_json').val(JSON.stringify(delta));
});
</script>

</body>
</html>
