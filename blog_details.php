<?php
require_once('database/db.php');

// Get blog ID from URL
$blogId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch current blog
$blogQuery = $conn->prepare("SELECT * FROM blog_posts WHERE id = ?");
$blogQuery->bind_param("i", $blogId);
$blogQuery->execute();
$blogResult = $blogQuery->get_result();

if ($blogResult->num_rows === 0) {
    echo "<p class='text-center py-5'>Blog post not found.</p>";
    exit;
}

$blog = $blogResult->fetch_assoc();
$blogData = json_decode($blog['blog_json'], true);

// Extract content HTML from Quill JSON
$contentHtml = '';
if (!empty($blogData['ops'])) {
    foreach ($blogData['ops'] as $op) {
        if (isset($op['insert'])) {
            $text = htmlspecialchars($op['insert']);
            if (isset($op['attributes'])) {
                if (isset($op['attributes']['bold'])) $text = "<strong>$text</strong>";
                if (isset($op['attributes']['italic'])) $text = "<em>$text</em>";
                if (isset($op['attributes']['underline'])) $text = "<u>$text</u>";
                if (isset($op['attributes']['list'])) {
                    $text = "<li>$text</li>";
                }
            }
            $contentHtml .= $text;
        }
    }
    if (strpos($contentHtml, '<li>') !== false) $contentHtml = "<ol>$contentHtml</ol>";
}

// Fetch related blogs (latest 3 excluding current)
$otherQuery = $conn->prepare("SELECT id, title, banner FROM blog_posts WHERE id != ? ORDER BY created_at DESC LIMIT 3");
$otherQuery->bind_param("i", $blogId);
$otherQuery->execute();
$otherBlogs = $otherQuery->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($blog['title']) ?> | GameChanger</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body { font-family: 'Poppins', sans-serif; background-color: #f8f9fc; }
.text-primary { color: #072F5F !important; }
.btn-orange { background-color: #FF7A00; color: #fff; border-radius: 0.6rem; transition: 0.3s; }
.btn-orange:hover { background-color: #e66b00; transform: translateY(-2px); }
.card { border-radius: 0.8rem; box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: 0.3s; }
.card:hover { transform: translateY(-6px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
.blog-banner { width: 100%; border-radius: 0.8rem; max-height: 400px; object-fit: cover; margin-bottom: 30px; }
.share-buttons i { font-size: 1.4rem; margin-right: 10px; cursor: pointer; }
.other-blog-card img { height: 180px; object-fit: cover; border-radius: 0.6rem; }
</style>
</head>
<body>


<!-- Navbar -->
<?php include('./includes/header.php'); ?>

<div class="container py-5">
    
    <h1 class="fw-bold mb-2"><?= htmlspecialchars($blog['title']) ?></h1>
    <p class="text-muted">By <?= htmlspecialchars($blog['author']) ?> | <?= date('F j, Y', strtotime($blog['created_at'])) ?></p>

    <?php if (!empty($blog['banner'])): ?>
        <img src="./backend/<?= htmlspecialchars($blog['banner']) ?>" class="blog-banner" alt="Banner">
    <?php endif; ?>

    <div class="blog-content mb-5"><?= $contentHtml ?></div>

    <!-- Share Buttons -->
    <div class="mb-5">
        <h5>Share this post:</h5>
        <?php 
            $currentUrl = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $encodedUrl = urlencode($currentUrl);
            $encodedTitle = urlencode($blog['title']);
        ?>
        <div class="share-buttons">
            <a href="https://wa.me/?text=<?= $encodedTitle ?>%20<?= $encodedUrl ?>" target="_blank"><i class="fab fa-whatsapp text-success"></i></a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $encodedUrl ?>" target="_blank"><i class="fab fa-facebook text-primary"></i></a>
            <a href="https://twitter.com/intent/tweet?text=<?= $encodedTitle ?>&url=<?= $encodedUrl ?>" target="_blank"><i class="fab fa-x-twitter text-info"></i></a>
            <i class="fas fa-link text-dark" id="copyLink" title="Copy link"></i>
        </div>
    </div>

    <!-- Other Blogs -->
    <h4 class="mb-4">You might also like</h4>
    <div class="row g-4">
        <?php if ($otherBlogs->num_rows > 0): ?>
            <?php while($other = $otherBlogs->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card other-blog-card h-100">
                        <?php if (!empty($other['banner'])): ?>
                            <img src="<?= htmlspecialchars($other['banner']) ?>" class="card-img-top" alt="<?= htmlspecialchars($other['title']) ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h6 class="card-title"><?= htmlspecialchars($other['title']) ?></h6>
                            <a href="blog_details.php?id=<?= $other['id'] ?>" class="btn btn-orange btn-sm">Read More</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted">No other blogs found.</p>
        <?php endif; ?>
    </div>
</div>



<script>
document.getElementById('copyLink').addEventListener('click', function() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Blog link copied to clipboard!');
    });
});
</script>



<!-- header -->
<?php include('./includes/footer.php'); ?>
