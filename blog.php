<?php
include ('./config/database.php');

// Set how many blogs to show per page
$blogsPerPage = 4;

// Get current page from URL (default to 1)
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) $currentPage = 1;

// Calculate the OFFSET
$offset = ($currentPage - 1) * $blogsPerPage;

// Fetch total number of blogs
$totalResult = $conn->query("SELECT COUNT(*) as total FROM blog");
$totalBlogs = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalBlogs / $blogsPerPage);

// Fetch current page blogs
$result = $conn->query("SELECT id, blog_json FROM blog ORDER BY id DESC LIMIT $blogsPerPage OFFSET $offset");

$blogs = [];
while ($row = $result->fetch_assoc()) {
    $blog = json_decode($row['blog_json'], true);
    $blog['id'] = $row['id'];
    $blogs[] = $blog;
}

$conn->close();
?>



<?php 
    $pageTitle  = "Astra Softwares | Stay updated with the latest news & insights";
    include ('./includes/header.php');
?>



<!-- Hero -->
<section class="section-header bg-primary text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 text-center">
                <h1 class="display-2 mb-3">Our Blog</h1>
                <p>Stay updated with the latest news, insights, and stories from Astra Softwares. Explore our articles on technology, AI, software development, and more.</p>
            </div>
        </div>
    </div>
</section>
<!-- End of Hero section -->



<div class="container py-5">
    <?php if (empty($blogs)): ?>
        <p>No blogs found.</p>
    <?php else: ?>
        <?php foreach ($blogs as $index => $blog): ?>
            <div class="card mb-4 shadow">
                <?php if (!empty($blog['banner'])): ?>
                    <div class="position-relative" style="height: 250px; background-image: url('<?= htmlspecialchars($blog['banner']) ?>'); background-size: cover; background-position: center; border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.5); border-top-left-radius: .5rem; border-top-right-radius: .5rem;"></div>
                        <h1 class="card-title position-absolute bottom-0 start-0 text-white p-3 m-0" style="z-index: 2; text-transform: capitalize;"><?= htmlspecialchars($blog['title']) ?></h1>
                    </div>
                <?php endif; ?>
                
                <div class="card-body">
                    <p class="text-muted">
                        By <strong><?= htmlspecialchars($blog['author']) ?></strong> on <?= htmlspecialchars($blog['date']) ?>
                    </p>
                    <hr>

                    <?php
                        // Show only the first content section
                        $firstBlock = $blog['content'][0] ?? null;
                        if ($firstBlock && $firstBlock['type'] === 'text'):
                    ?>
                        <h4><?= htmlspecialchars($firstBlock['heading']) ?></h4>
                    <p>
                        <?= (mb_substr($firstBlock['body'], 0, 300)) ?>
                    </p>
                    <?php elseif ($firstBlock && $firstBlock['type'] === 'image'): ?>
                        <div class="my-3">
                            <img src="<?= htmlspecialchars($firstBlock['url']) ?>" class="img-fluid rounded mx-auto d-block" style="max-width: 300px;" alt="<?= htmlspecialchars($firstBlock['url']) ?>">
                            <small class="text-muted d-block"><?= htmlspecialchars($firstBlock['caption']) ?></small>
                        </div>
                    <?php endif; ?>

                    <div class="mt-3">
                        <a href="blog_details.php?id=<?= urlencode($blog['id']) ?>" class="btn btn-primary">Read More</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>


<!-- Pagination -->
<nav aria-label="Page navigation">
  <ul class="pagination justify-content-center">

    <!-- Prev Button -->
    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?= $currentPage - 1 ?>" aria-label="Previous">
        <span aria-hidden="true">&laquo; Prev</span>
      </a>
    </li>

    <!-- Page Numbers -->
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>

    <!-- Next Button -->
    <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?= $currentPage + 1 ?>" aria-label="Next">
        <span aria-hidden="true">Next &raquo;</span>
      </a>
    </li>
    
  </ul>
</nav>


<?php
  include ('./includes/footer.php');
?>











