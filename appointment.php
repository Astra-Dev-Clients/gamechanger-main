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



<!-- header -->
<?php include('./includes/header.php'); ?>


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
 

<!-- header -->
<?php include('./includes/footer.php'); ?>