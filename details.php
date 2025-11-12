<?php
require_once('database/db.php');

// Validate & fetch the opportunity
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT o.*, t.type_name 
          FROM opportunities o 
          LEFT JOIN opportunity_types t ON o.type_id = t.id 
          WHERE o.id = $id LIMIT 1";
$result = $conn->query($query);
$opportunity = $result->fetch_assoc();

if (!$opportunity) {
  die("<div class='container py-5 text-center'><h3 class='text-danger'>Opportunity not found.</h3></div>");
}

// Fetch related opportunities (same type or country)
$relatedQuery = "SELECT o.*, t.type_name 
                 FROM opportunities o 
                 LEFT JOIN opportunity_types t ON o.type_id = t.id 
                 WHERE o.id != $id 
                 AND (o.country = '{$conn->real_escape_string($opportunity['country'])}' 
                 OR o.type_id = '{$opportunity['type_id']}') 
                 ORDER BY RAND() 
                 LIMIT 3";
$related = $conn->query($relatedQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($opportunity['title']) ?> - GameChanger</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f9fbff;
    }
    .hero-header {
      background: linear-gradient(135deg, #072F5F, #FF7A00);
      color: #fff;
      padding: 80px 0;
      text-align: center;
    }
    .hero-header h1 { font-weight: 700; }
    .btn-orange {
      background-color: #FF7A00;
      color: #fff;
      border: none;
      border-radius: 25px;
      padding: 10px 20px;
    }
    .btn-orange:hover { background-color: #e66b00; }
    .btn-blue {
      background-color: #072F5F;
      color: #fff;
      border: none;
      border-radius: 25px;
      padding: 10px 20px;
    }
    .btn-blue:hover { background-color: #051f3d; }
    .details-section {
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.05);
      margin-top: -60px;
      position: relative;
      z-index: 2;
    }
    .share-section {
      margin-top: 50px;
      text-align: center;
    }
    .share-buttons a, .share-buttons button {
      margin: 5px;
      border-radius: 25px;
      padding: 10px 18px;
      color: white;
      text-decoration: none;
      display: inline-block;
    }
    .share-facebook { background-color: #1877F2; }
    .share-whatsapp { background-color: #25D366; }
    .share-copy { background-color: #555; border: none; }
    .related-section {
      margin-top: 80px;
    }
    .card {
      border: 1px solid #ddd;
      border-radius: 10px;
      transition: 0.3s;
    }
    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<!-- Navbar -->
<?php include('./includes/navbar.php'); ?>

<!-- Hero Header -->
<section class="hero-header">
  <div class="container">
    <h1><?= htmlspecialchars($opportunity['title']) ?></h1>
    <p class="lead"><?= htmlspecialchars($opportunity['country']) ?> • <?= htmlspecialchars($opportunity['sponsorship']) ?></p>
  </div>
</section>

<!-- Opportunity Details -->
<div class="container">
  <div class="details-section">
    <div class="row">
      <div class="col-lg-6">
        <img src="uploads/<?= htmlspecialchars($opportunity['image_url']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($opportunity['title']) ?>">
      </div>
      <div class="col-lg-6">
        <h3 class="text-primary"><?= htmlspecialchars($opportunity['title']) ?></h3>
        <span class="badge bg-primary mb-3"><?= htmlspecialchars($opportunity['type_name']) ?></span>
        <p><strong>Institution:</strong> <?= htmlspecialchars($opportunity['institution']) ?></p>
        <p><strong>Country:</strong> <?= htmlspecialchars($opportunity['country']) ?></p>
        <p><strong>Sponsorship:</strong> <?= htmlspecialchars($opportunity['sponsorship']) ?></p>
        <hr>
        <p><?= nl2br(htmlspecialchars($opportunity['description'])) ?></p>

        <div class="mt-3 d-flex gap-2 flex-wrap">
          <?php if (!empty($opportunity['opp_url'])): ?>
            <a href="<?= htmlspecialchars($opportunity['opp_url']) ?>" target="_blank" class="btn btn-orange">Apply Now</a>
          <?php endif; ?>
          <?php if (!empty($opportunity['co_web'])): ?>
            <a href="<?= htmlspecialchars($opportunity['co_web']) ?>" target="_blank" class="btn btn-blue">Visit Official Website</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Share Section -->
  <div class="share-section">
    <h5 class="fw-bold mb-3 text-primary">Share this Opportunity</h5>
    <div class="share-buttons">
      <?php
        $currentUrl = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
      ?>
      <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($currentUrl) ?>" target="_blank" class="share-facebook">
        <i class="bi bi-facebook"></i> Facebook
      </a>
      <a href="https://wa.me/?text=<?= urlencode('Check this opportunity: ' . $currentUrl) ?>" target="_blank" class="share-whatsapp">
        <i class="bi bi-whatsapp"></i> WhatsApp
      </a>
      <button class="share-copy" onclick="copyLink()">Copy Link</button>
    </div>
  </div>
</div>

<!-- Related Opportunities -->
<section class="related-section py-5 bg-light">
  <div class="container">
    <h3 class="fw-bold text-primary mb-4">Related Opportunities</h3>
    <div class="row">
      <?php if ($related->num_rows > 0): ?>
        <?php while ($r = $related->fetch_assoc()): ?>
          <div class="col-md-4 mb-4">
            <div class="card h-100">
              <img src="uploads/<?= htmlspecialchars($r['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($r['title']) ?>">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($r['title']) ?></h5>
                <p class="text-muted"><?= htmlspecialchars(substr($r['description'], 0, 100)) ?>...</p>
                <a href="details.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-orange">View Details</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-muted">No related opportunities found.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ======= Footer Section ======= -->
<!-- ======= Footer Section ======= -->
<footer class="pt-5 bg-dark text-light mt-5">
  <div class="container">
    <div class="row gy-4">

      <!-- Contact Info -->
      <div class="col-md-3">
        <h5 class="fw-bold mb-3 text-uppercase text-warning">Contact Us</h5>
        <p><i class="bi bi-geo-alt-fill me-2"></i> Nairobi, Kenya</p>
        <p><i class="bi bi-envelope-fill me-2"></i> info@gamechanger.co.ke</p>
        <p><i class="bi bi-telephone-fill me-2"></i> +254 726 874 170</p>
      </div>

      <!-- Follow Us -->
      <div class="col-md-3">
        <h5 class="fw-bold mb-3 text-uppercase text-warning">Follow Us</h5>
        <p>Stay connected for the latest updates and opportunities.</p>
        <div class="d-flex gap-3 fs-4">
          <a href="#" target="_blank" class="text-light"><i class="bi bi-facebook"></i></a>
          <a href="#" target="_blank" class="text-light"><i class="bi bi-twitter-x"></i></a>
          <a href="#" target="_blank" class="text-light"><i class="bi bi-tiktok"></i></a>
          <a href="#" target="_blank" class="text-light"><i class="bi bi-youtube"></i></a>
        </div>
      </div>

      <!-- Newsletter -->
      <div class="col-md-3">
        <h5 class="fw-bold mb-3 text-uppercase text-warning">Newsletter</h5>
        <p>Subscribe for updates on study and work abroad opportunities.</p>
        <form class="d-flex">
          <input type="email" class="form-control me-2" placeholder="Enter your email" required>
          <button class="btn btn-warning text-dark fw-semibold" type="submit">Go</button>
        </form>
      </div>

      <!-- Partners & Destinations -->
      <div class="col-md-3">
        <h6 class="fw-bold text-uppercase text-warning mt-3">Top Destinations</h6>
        <ul class="list-unstyled mb-0">
          <li>🇨🇦 Canada</li>
          <li>🇬🇧 United Kingdom</li>
          <li>🇺🇸 USA</li>
          <li>🇦🇺 Australia</li>
          <li>🇩🇪 Germany</li>
        </ul>
      </div>

    </div>

    <hr class="my-4 border-light">
    <div class="text-center small">
      <p class="mb-0">&copy; <?= date('Y') ?> GameChanger Consulting. All Rights Reserved.</p>
    </div>
  </div>
</footer>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


<style>
footer {
  font-family: 'Poppins', sans-serif;
}
footer h5, footer h6 {
  letter-spacing: 1px;
}
footer ul li {
  color: #ccc;
  margin-bottom: 6px;
}
footer ul li:hover {
  color: #fff;
}
footer form input {
  border-radius: 25px;
  padding-left: 15px;
}
footer form button {
  border-radius: 25px;
}
footer a:hover {
  color: #ff7a00 !important;
}

</style>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


<script>
function copyLink() {
  const url = window.location.href;
  navigator.clipboard.writeText(url);
  alert("Link copied to clipboard!");
}
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</body>
</html>
