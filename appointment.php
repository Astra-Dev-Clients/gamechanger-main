<?php
require_once('database/db.php');
session_start();

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $service_type = $_POST['service_type'];
    $preferred_datetime = $_POST['preferred_datetime']; // datetime-local value
    $preferred_platform = $_POST['preferred_platform'];
    $message = trim($_POST['message']); // notes

if ($full_name && $email && $service_type && $preferred_datetime && $preferred_platform) {
    $stmt = $conn->prepare("
        INSERT INTO appointments 
        (full_name, email, phone, service_type, preferred_datetime, preferred_platform, message) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssssss", $full_name, $email, $phone, $service_type, $preferred_datetime, $preferred_platform, $message);

    
        if ($stmt->execute()) {
            $success = "Your appointment request has been submitted successfully!";
        } else {
            $error = "Something went wrong. Please try again later.";
        }
        $stmt->close();
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book Appointment | GameChanger</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body { font-family: 'Poppins', sans-serif; background-color: #f8f9fc; }
h2 { color: #072F5F; }
.btn-orange {
  background-color: #FF7A00; color: #fff; border-radius: 0.6rem;
  transition: 0.3s ease;
}
.btn-orange:hover { background-color: #e56c00; transform: translateY(-2px); }
.card { border: none; border-radius: 1rem; box-shadow: 0 5px 20px rgba(0,0,0,0.08); }
label { font-weight: 500; }
</style>
</head>
<body>

<!-- Navbar -->
<?php include('./includes/navbar.php'); ?>

<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold text-primary">Book an Appointment</h2>
      <p class="text-muted">We’re here to guide you at every step — from applications to visas and beyond.</p>
    </div>

    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card p-4">
          <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
          <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
          <?php endif; ?>

          <form method="POST" class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Full Name *</label>
              <input type="text" name="full_name" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Email *</label>
              <input type="email" name="email" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" class="form-control" placeholder="+254...">
            </div>

            <div class="col-md-6">
              <label class="form-label">Select Service *</label>
              <select name="service_type" class="form-select" required>
                <option value="">Choose a Service</option>
                <option value="University Applications">University Applications</option>
                <option value="Visa Applications">Visa Applications</option>
                <option value="University Choices Assistance">University Choices Assistance</option>
                <option value="Career Guidance">Career Guidance</option>
                <option value="Visa Guidance">Visa Guidance</option>
                <option value="Consultation">Consultation</option>
              </select>
            </div>

            <div class="col-md-6">
            <label class="form-label">Preferred Date & Time *</label>
            <input type="datetime-local" name="preferred_datetime" class="form-control" required>
            </div>


            <div class="col-md-6">
              <label class="form-label">Preferred Platform *</label>
              <select name="preferred_platform" class="form-select" required>
                <option value="">Choose a Platform</option>
                <option value="Phone Call">Phone Call</option>
                <option value="WhatsApp Call">WhatsApp Call</option>
                <option value="Google Meet">Google Meet</option>
              </select>
            </div>

            <div class="col-12">
              <label class="form-label">Message</label>
              <textarea name="message" rows="4" class="form-control" placeholder="Tell us a bit about what you need..."></textarea>
            </div>

            <div class="col-12 text-center mt-4">
              <button type="submit" class="btn btn-orange px-5 py-2 fw-semibold">
                <i class="fa-solid fa-calendar-check me-2"></i> Book Appointment
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
