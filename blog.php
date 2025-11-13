<?php
require_once('database/db.php');

// Pagination setup
$limit = 5; // blogs per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Count total posts
$countResult = $conn->query("SELECT COUNT(*) AS total FROM blog_posts");
$totalPosts = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalPosts / $limit);

// Fetch blogs with limit and offset
$query = "SELECT * FROM blog_posts ORDER BY created_at DESC LIMIT $start, $limit";
$result = $conn->query($query);
?>

<?php include('./includes/header.php'); ?>

<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold text-primary">Our Blog</h2>
      <p class="text-muted">Insights, stories, and updates about studying, working, and living abroad.</p>
    </div>

    <div class="row g-4">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($blog = $result->fetch_assoc()): ?>
          <?php
            $banner = htmlspecialchars($blog['banner'] ?? '');
            $title = htmlspecialchars($blog['title']);
            $created_at = date("F j, Y", strtotime($blog['created_at']));
            
            // Decode Quill JSON to plain text
            $excerpt = '';
            $content = json_decode($blog['blog_json'], true);
            if (!empty($content['ops'])) {
              foreach ($content['ops'] as $op) {
                if (isset($op['insert'])) {
                  $excerpt .= strip_tags($op['insert']);
                }
              }
            }
            $excerpt = substr($excerpt, 0, 180);
          ?>
          <div class="col-md-6 col-lg-4">
            <div class="card blog-card border-0 shadow-sm h-100">
              <?php if ($banner): ?>
                <img src="./backend/<?= $banner ?>" class="card-img-top" alt="<?= $title ?>">
              <?php endif; ?>
              <div class="card-body">
                <h5 class="fw-bold text-primary"><?= $title ?></h5>
                <small class="text-muted d-block mb-2"><?= $created_at ?></small>
                <p class="text-muted small"><?= htmlspecialchars($excerpt) ?>...</p>
                <a href="blog_details.php?id=<?= $blog['id'] ?>" class="btn btn-orange btn-sm">Read More</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center text-muted">No blog posts found.</p>
      <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
      <nav aria-label="Blog pagination">
        <ul class="pagination justify-content-center mt-5">
          <?php if ($page > 1): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?= $page - 1 ?>">« Prev</a>
            </li>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>

          <?php if ($page < $totalPages): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?= $page + 1 ?>">Next »</a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    <?php endif; ?>
  </div>
</section>

<style>
.blog-card img {
  height: 220px;
  object-fit: cover;
  border-radius: .5rem .5rem 0 0;
}

.btn-orange {
  background-color: #ff6600;
  color: #fff;
  border: none;
  border-radius: 0.6rem;
  transition: background-color 0.3s ease, transform 0.2s ease;
}
.btn-orange:hover {
  background-color: #e65c00;
  transform: translateY(-2px);
}
.pagination .page-item.active .page-link {
  background-color: #ff6600;
  border-color: #ff6600;
}
.pagination .page-link {
  color: #072F5F;
}
</style>


<?php include('./includes/footer.php'); ?>