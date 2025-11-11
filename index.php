<?php
require_once('database/db.php');

// Fetch all filter options
$countries = $conn->query("SELECT DISTINCT country FROM opportunities WHERE country IS NOT NULL AND country != '' ORDER BY country ASC");
$courses = $conn->query("SELECT DISTINCT course FROM opportunities WHERE course IS NOT NULL AND course != '' ORDER BY course ASC");
$institutions = $conn->query("SELECT DISTINCT institution FROM opportunities WHERE institution IS NOT NULL AND institution != '' ORDER BY institution ASC");
$types = $conn->query("SELECT * FROM opportunity_types ORDER BY type_name ASC");
$query = "SELECT o.*, t.type_name FROM opportunities o 
          LEFT JOIN opportunity_types t ON o.type_id = t.id 
          WHERE 1=1";

if (!empty($_GET['type'])) {
  $type = $conn->real_escape_string($_GET['type']);
  $query .= " AND t.type_name = '$type'";
}
if (!empty($_GET['country'])) {
  $country = $conn->real_escape_string($_GET['country']);
  $query .= " AND o.country = '$country'";
}
if (!empty($_GET['course'])) {
  $course = $conn->real_escape_string($_GET['course']);
  $query .= " AND o.course = '$course'";
}
if (!empty($_GET['institution'])) {
  $institution = $conn->real_escape_string($_GET['institution']);
  $query .= " AND o.institution = '$institution'";
}
if (!empty($_GET['sponsorship'])) {
  $sponsorship = $conn->real_escape_string($_GET['sponsorship']);
  $query .= " AND o.sponsorship = '$sponsorship'";
}

$query .= " ORDER BY o.created_at DESC LIMIT 6";
$result = $conn->query($query);

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Game Changer Study and Work Abroad Consulting</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fc;
    }

    .logo-box {
      background-color: #072F5F;
      color: #fff;
      padding: 4px 10px;
      border-radius: 4px;
      margin-right: 4px;
    }

    .nav-link {
      color: #072F5F !important;
    }

    .nav-link:hover {
      color: #FF7A00 !important;
    }

    .btn-orange {
      background-color: #FF7A00;
      color: #fff;
      border: none;
      transition: 0.3s;
    }

    .btn-orange:hover {
      background-color: #e66b00;
    }

    .hero-section {
      background-color: #fff;
      min-height: 80vh;
      display: flex;
      align-items: center;
      position: relative;
    }

    .hero-section h1 {
      color: #072F5F;
    }

    .text-primary {
      color: #072F5F !important;
    }

    .btn-lg {
      padding: 0.75rem 2rem;
      border-radius: 30px;
    }

    footer {
      background-color: #fff;
      border-top: 1px solid #e5e5e5;
      padding: 30px 0;
      text-align: center;
    }

    /* Filter Section Styles */
    .filter-section {
      background-color: #fff;
      padding: 30px 0;
      border-bottom: 2px solid #e5e5e5;
    }

    .filter-section .form-check-label {
      font-size: 14px;
    }

    .filter-cards {
      margin-top: 30px;
      padding-bottom: 60px;
    }

    .card {
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      transition: 0.3s;
    }

    .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .card-body {
      padding: 20px;
    }

    .card-title {
      font-size: 18px;
      font-weight: bold;
      color: #072F5F;
    }

    .card-text {
      font-size: 14px;
      color: #555;
    }

    .filter-section h5 {
      color: #072F5F;
      margin-bottom: 10px;
    }

    @media (max-width: 768px) {
      .hero-section h1 {
        font-size: 2rem;
      }
    }


    .filter-section {
  background-color: #fff;
  padding: 40px 0;
  border-bottom: 2px solid #e5e5e5;
}

.filter-section h5 {
  font-size: 16px;
  font-weight: 600;
  color: #072F5F;
  margin-bottom: 10px;
}

.form-select {
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 14px;
  padding: 10px;
  transition: 0.3s;
}

.form-select:focus {
  border-color: #FF7A00;
  box-shadow: 0 0 0 0.2rem rgba(255, 122, 0, 0.25);
}



  </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-sm">
  <div class="container">
    <!-- Logo -->
<a class="navbar-brand d-flex align-items-center fw-bold text-uppercase" href="#">
  <div class="logo-wrapper d-flex align-items-center justify-content-center me-2">
    <img src="Assets/images/brand/log.png" alt="Logo" class="img-fluid">
  </div>
  <div class="brand-text d-flex flex-column justify-content-center">
    <span class="text-primary fs-5">GameChanger</span>
    <small class="text-muted fw-normal" style="font-size: 0.7rem;">Study & work abroad</small>
  </div>
</a>


    <!-- Mobile Toggle -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Nav Links -->
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav me-4 mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link fw-semibold" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link fw-semibold" href="#">Services</a></li>
        <li class="nav-item"><a class="nav-link fw-semibold" href="#">Destinations</a></li>
        <li class="nav-item"><a class="nav-link fw-semibold" href="#">Careers</a></li>
        <li class="nav-item"><a class="nav-link fw-semibold" href="#">Contact us</a></li>
        <li class="nav-item"><a class="nav-link fw-semibold" href="#">Blog</a></li>
      </ul>

      <!-- CTA Button -->
      <div class="d-flex align-items-center mt-3 mt-lg-0">
        <a href="#" class="btn btn-orange btn-signup px-4 py-2 fw-semibold"><i class="fa-solid fa-calendar-week me-2"></i> Book Appointment</a>
        <!-- <a href="#" class="btn btn-orange btn-signup px-4 py-2 fw-semibold">Sign Up</a> -->
      </div>
    </div>
  </div>
</nav>

<style>
/* Logo Wrapper */
.logo-wrapper {
  background-color: #ff7b003f; /* brand color */
  padding: 6px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.logo-wrapper img {
  max-height: 50px;
  width: auto;
}

/* Navbar Links */
.navbar-nav .nav-link {
  color: #072F5F !important;
  margin: 0 8px;
  transition: 0.3s;
}

.navbar-nav .nav-link:hover {
  color: #FF7A00 !important;
}

/* Signup Button */
.btn-signup {
  border-radius: 30px;
  transition: 0.3s;
}

.btn-signup:hover {
  background-color: #e66b00;
  color: #fff;
}
</style>


  <!-- Hero Section -->
<section class="hero-section py-5">
  <div class="container">
    <div class="row align-items-center">
      <!-- Left Content -->
      <div class="col-lg-6">
        <h1 class="display-5 fw-bold">
          EXPLORE <span class="" style="color:#e66b00;">GLOBAL OPPORTUNITIES</span>
        </h1>
        <p class="lead text-muted mt-3 mb-5">
          Start your journey to study, work, and live abroad. Game Changer helps students and professionals unlock international education and career success.
        </p>
        <a href="#" class="btn btn-orange btn-lg fw-semibold">Explore Opportunities <i class="fa-solid fa-circle-chevron-right ms-2"></i></a>
      </div>

      <!-- Right Image Slider -->
      <div class="col-lg-6 mt-4 mt-lg-0">
        <div class="image-slider">
          <img src="Assets/images/illustrations/student-1.png" class="active" alt="Students abroad">
          <img src="Assets/images/illustrations/student-2.png" alt="Career abroad">
          <img src="Assets/images/illustrations/student-3.png" alt="International education">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CSS -->
<style>
.image-slider {
  position: relative;
  width: 100%;
  height: 400px; /* Adjust height as needed */
  overflow: hidden;
  border-radius: 2rem 0rem;
}

.image-slider img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  opacity: 0;
  transition: opacity 1s ease-in-out;
}

.image-slider img.active {
  opacity: 1;
}
</style>

<!-- JavaScript -->
<script>
let currentIndex = 0;
const images = document.querySelectorAll('.image-slider img');

function showNextImage() {
  images[currentIndex].classList.remove('active');
  currentIndex = (currentIndex + 1) % images.length;
  images[currentIndex].classList.add('active');
}

// Change image every 3 seconds
setInterval(showNextImage, 3000);
</script>



  
  <!-- Call to Action: IELTS English Certification -->
<section class="cta-section text-center py-5">
  <div class="container">
    <div class="row align-items-center justify-content-center">
      <div class="col-md-8">
        <h2 class="fw-bold text-white mb-3">Get IELTS English Certification at an Affordable Price!</h2>
        <p class="text-white-50 mb-4">
          Boost your chances of studying or working abroad by taking your IELTS test with us. 
          GameChanger Consulting offers guidance, preparation, and registration support at friendly rates — 
          helping you achieve the score you need for your dream university or career.
        </p>
        <a href="#" class="btn btn-light btn-lg fw-semibold px-5">Book Your IELTS Test Now</a>
      </div>
    </div>
  </div>
</section>

<style>
.cta-section {
  background: linear-gradient(135deg, #072F5F, #FF7A00);
  color: white;
  border-radius: 0;
  padding: 80px 20px;
}

.cta-section h2 {
  font-size: 30px;
}

.cta-section .btn-light {
  color: #072F5F;
  border-radius: 30px;
  transition: 0.3s;
}

.cta-section .btn-light:hover {
  background-color: #FF7A00;
  color: #fff;
  border-color: #FF7A00;
}
</style>


<section class="filter-section py-5 bg-light">
  <div class="container">
    <h3 class="text-primary mb-4 fw-bold">Find Global Opportunities</h3>
    <form method="GET">
      <div class="row g-4">
        
        <!-- Type Filter -->
        <div class="col-md-2">
          <h6>Type</h6>
          <select class="form-select" name="type">
            <option value="">All Types</option>
            <?php while($row = $types->fetch_assoc()): ?>
              <option value="<?= htmlspecialchars($row['type_name']) ?>" 
                <?= isset($_GET['type']) && $_GET['type'] == $row['type_name'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($row['type_name']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <!-- Country Filter -->
        <div class="col-md-2">
          <h6>Country</h6>
          <select class="form-select" name="country">
            <option value="">All Countries</option>
            <?php while($row = $countries->fetch_assoc()): ?>
              <option value="<?= htmlspecialchars($row['country']) ?>" 
                <?= isset($_GET['country']) && $_GET['country'] == $row['country'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($row['country']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <!-- Course Filter -->
        <div class="col-md-2">
          <h6>Course / Title</h6>
          <select class="form-select" name="course">
            <option value="">All Courses / Titles</option>
            <?php while($row = $courses->fetch_assoc()): ?>
              <option value="<?= htmlspecialchars($row['course']) ?>" 
                <?= isset($_GET['course']) && $_GET['course'] == $row['course'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($row['course']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <!-- Institution Filter -->
        <div class="col-md-2">
          <h6>Institution</h6>
          <select class="form-select" name="institution">
            <option value="">All Institutions</option>
            <?php while($row = $institutions->fetch_assoc()): ?>
              <option value="<?= htmlspecialchars($row['institution']) ?>" 
                <?= isset($_GET['institution']) && $_GET['institution'] == $row['institution'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($row['institution']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <!-- Sponsorship Filter -->
        <div class="col-md-2">
          <h6>Sponsorship</h6>
          <select class="form-select" name="sponsorship">
            <option value="">All Types</option>
            <option value="Sponsored" <?= isset($_GET['sponsorship']) && $_GET['sponsorship']=='Sponsored'?'selected':''; ?>>Sponsored</option>
            <option value="Unsponsored" <?= isset($_GET['sponsorship']) && $_GET['sponsorship']=='Unsponsored'?'selected':''; ?>>Unsponsored</option>
            <option value="Partially Sponsored" <?= isset($_GET['sponsorship']) && $_GET['sponsorship']=='Partially Sponsored'?'selected':''; ?>>Partially Sponsored</option>
          </select>
        </div>

        <!-- Apply Button -->
        <div class="col-md-2 d-flex align-items-end">
          <button class="btn btn-orange w-100 fw-semibold" type="submit">Filter</button>
        </div>
      </div>
    </form>
  </div>
</section>




<section class="filter-cards py-5">
  <div class="container">
    <div class="row">
      <?php if ($result->num_rows > 0): ?>
        <?php while ($op = $result->fetch_assoc()): ?>
          <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
              <img src="uploads/<?= htmlspecialchars($op['image_url']) ?>" class="card-img-top" alt="Opportunity Image">
              <div class="card-body">
                <span class="badge bg-primary mb-2"><?= htmlspecialchars($op['type_name']) ?></span>
                <h5 class="card-title"><?= htmlspecialchars($op['title']) ?></h5>
                <p class="card-text"><?= htmlspecialchars(substr($op['description'], 0, 150)) ?>...</p>
                <p><strong><?= htmlspecialchars($op['country']) ?></strong> • <?= htmlspecialchars($op['sponsorship']) ?></p>
                <a href="details.php?id=<?= $op['id'] ?>" class="btn btn-orange btn-sm">View Details</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center text-muted">No opportunities found for the selected filters.</p>
      <?php endif; ?>
    </div>
  </div>
</section>





<section class="services-section py-5" id="services">
  <div class="container text-center">
    <h2 class="fw-bold mb-4">
      Our <span class="text-primary">Services</span>
    </h2>
    <p class="text-muted mb-5">
      We provide expert support at every step — from choosing the right university to securing your visa and shaping your global career.
    </p>

    <div class="row g-4 justify-content-center">
      <!-- University Applications -->
      <div class="col-md-4 col-lg-3">
        <div class="service-card p-4 h-100 shadow-sm">
          <div class="icon mb-3 text-primary">
            <i class="fa-solid fa-graduation-cap fa-2x"></i>
          </div>
          <h5 class="fw-semibold mb-2">University Applications</h5>
          <p class="text-muted small">
            Get professional guidance to apply to top global universities with confidence.
          </p>
        </div>
      </div>

      <!-- Visa Applications -->
      <div class="col-md-4 col-lg-3">
        <div class="service-card p-4 h-100 shadow-sm">
          <div class="icon mb-3 text-primary">
            <i class="fa-solid fa-passport fa-2x"></i>
          </div>
          <h5 class="fw-semibold mb-2">Visa Applications</h5>
          <p class="text-muted small">
            Simplify your student and work visa process with expert documentation help.
          </p>
        </div>
      </div>

      <!-- University Choices Assistance -->
      <div class="col-md-4 col-lg-3">
        <div class="service-card p-4 h-100 shadow-sm">
          <div class="icon mb-3 text-primary">
            <i class="fa-solid fa-building-columns fa-2x"></i>
          </div>
          <h5 class="fw-semibold mb-2">University Choices Assistance</h5>
          <p class="text-muted small">
            Find the best-fit universities based on your goals, budget, and profile.
          </p>
        </div>
      </div>

      <!-- Career Guidance -->
      <div class="col-md-4 col-lg-3">
        <div class="service-card p-4 h-100 shadow-sm">
          <div class="icon mb-3 text-primary">
            <i class="fa-solid fa-briefcase fa-2x"></i>
          </div>
          <h5 class="fw-semibold mb-2">Career Guidance</h5>
          <p class="text-muted small">
            Receive insights and advice to prepare for global job opportunities.
          </p>
        </div>
      </div>

      <!-- Visa Guidance -->
      <div class="col-md-4 col-lg-3">
        <div class="service-card p-4 h-100 shadow-sm">
          <div class="icon mb-3 text-primary">
            <i class="fa-solid fa-earth-americas fa-2x"></i>
          </div>
          <h5 class="fw-semibold mb-2">Visa Guidance</h5>
          <p class="text-muted small">
            Get step-by-step support to ensure a smooth visa approval process.
          </p>
        </div>
      </div>

      <!-- Consultation -->
      <div class="col-md-4 col-lg-3">
        <div class="service-card p-4 h-100 shadow-sm">
          <div class="icon mb-3 text-primary">
            <i class="fa-solid fa-comments fa-2x"></i>
          </div>
          <h5 class="fw-semibold mb-2">Consultation</h5>
          <p class="text-muted small">
            Book a consultation to discuss your study or work abroad goals.
          </p>
        </div>
      </div>
    </div>

    <!-- CTA -->
    <div class="mt-5">
      <a href="#contact" class="btn btn-orange btn-lg fw-semibold px-4">
        Book Appointment <i class="fa-solid fa-circle-chevron-right ms-2"></i>
      </a>
    </div>
  </div>
</section>

<!-- CSS -->
<style>
.services-section {
  background: linear-gradient(135deg, #f9fbff 0%, #f3f7ff 100%);
}

.service-card {
  border-radius: 1rem;
  background: #ffffffcc;
  transition: all 0.3s ease;
  border: 1px solid rgba(0, 0, 0, 0.05);
}

.service-card:hover {
  transform: translateY(-8px);
  background: #fff;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.icon {
  color: #ff6600;
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
</style>




<!-- ===========================
     Blog Section
=========================== -->
<section class="blog-section py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold text-primary">Latest from Our Blog</h2>
      <p class="text-muted">
        Stay informed with tips, stories, and updates about studying, working, and living abroad.
      </p>
    </div>

    <div class="row g-4">
      <!-- Blog Post 1 -->
      <div class="col-md-4">
        <div class="card blog-card border-0 shadow-sm h-100">
          <img src="Assets/images/posts/countries.jpg" class="card-img-top" alt="Blog 1">
          <div class="card-body">
            <h5 class="card-title text-primary">Top 5 Countries to Study in 2025</h5>
            <p class="card-text text-muted">
              Explore the best destinations offering world-class education, scholarships, and career opportunities for international students.
            </p>
            <a href="#" class="btn btn-orange btn-sm">Read More</a>
          </div>
        </div>
      </div>

      <!-- Blog Post 2 -->
      <div class="col-md-4">
        <div class="card blog-card border-0 shadow-sm h-100">
          <img src="Assets/images/posts/jobs.jpg" class="card-img-top" alt="Blog 2">
          <div class="card-body">
            <h5 class="card-title text-primary">Landing a Job Abroad Made Easy</h5>
            <p class="card-text text-muted">
              A step-by-step guide to finding and applying for international job opportunities with visa sponsorship.
            </p>
            <a href="#" class="btn btn-orange btn-sm">Read More</a>
          </div>
        </div>
      </div>

      <!-- Blog Post 3 -->
      <div class="col-md-4">
        <div class="card blog-card border-0 shadow-sm h-100">
          <img src="Assets/images/posts/english.jpg" class="card-img-top" alt="Blog 3">
          <div class="card-body">
            <h5 class="card-title text-primary">How to Prepare for IELTS the Smart Way</h5>
            <p class="card-text text-muted">
              Discover effective study methods and resources to boost your IELTS score and meet university admission requirements.
            </p>
            <a href="#" class="btn btn-orange btn-sm">Read More</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>




<!-- Partners Section -->
<section class="partners-section py-5 bg-white">
  <div class="container text-center">
    <h2 class="fw-bold text-primary mb-4">Our Trusted Partners</h2>
    <p class="text-muted mb-5">
      We collaborate with top universities, organizations, and institutions across the world 
      to make your study and work abroad dreams a reality.
    </p>

<div class="partners-slider">
  <div class="partners-track">
    <img src="Assets/images/universities/alexander-college.svg" alt="Alexander College" class="partner-logo">
    <img src="Assets/images/universities/merito.png" alt="Merito University" class="partner-logo">
    <img src="Assets/images/universities/Olsztyn.png" alt="Olsztyn University" class="partner-logo">
    <img src="Assets/images/universities/The-International-University-of-Logistics-and-Transport-in-Wroclaw.png" alt="Wroclaw University" class="partner-logo">
    <img src="Assets/images/universities/vistula.svg" alt="Vistula University" class="partner-logo">

    <!-- duplicate images for seamless looping -->
    <img src="Assets/images/universities/alexander-college.svg" alt="Alexander College" class="partner-logo">
    <img src="Assets/images/universities/merito.png" alt="Merito University" class="partner-logo">
    <img src="Assets/images/universities/Olsztyn.png" alt="Olsztyn University" class="partner-logo">
    <img src="Assets/images/universities/The-International-University-of-Logistics-and-Transport-in-Wroclaw.png" alt="Wroclaw University" class="partner-logo">
    <img src="Assets/images/universities/vistula.svg" alt="Vistula University" class="partner-logo">
  </div>
</div>

  </div>
</section>

<style>
.partners-section h2 {
  font-size: 28px;
}

.partner-logo {
  max-height: 80px;
  transition: transform 0.3s ease;
  filter: grayscale(100%);
  opacity: 0.8;
}

.partner-logo:hover {
  transform: scale(1.05);
  filter: grayscale(0%);
  opacity: 1;
}

/* Auto-Scrolling Partner Logos */
.partners-slider {
  overflow: hidden;
  position: relative;
  width: 100%;
}

.partners-track {
  display: flex;
  width: calc(200%); /* for smooth looping */
  animation: scroll 20s linear infinite;
}

.partner-logo {
  height: 80px;
  margin: 0 40px;
  opacity: 0.9;
  transition: transform 0.3s ease, opacity 0.3s ease;
  filter: grayscale(100%);
}

.partner-logo:hover {
  transform: scale(1.1);
  filter: grayscale(0%);
  opacity: 1;
}

/* Keyframes for infinite scroll */
@keyframes scroll {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(-50%);
  }
}

/* Responsive tweaks */
@media (max-width: 768px) {
  .partner-logo {
    height: 60px;
    margin: 0 20px;
  }
}


</style>





<section class="testimonials-section py-5" id="testimonials">
  <div class="container text-center">
    <h2 class="fw-bold mb-4">
      What Our <span class="text-primary">Clients Say</span>
    </h2>
    <p class="text-muted mb-5">
      Hear from our students and professionals who successfully achieved their global dreams through our guidance.
    </p>

    <div class="row g-4 justify-content-center">
      <!-- Testimonial 1 -->
      <div class="col-md-6 col-lg-4">
        <div class="testimonial-card p-4 h-100 shadow-sm">
          <div class="testimonial-avatar mb-3">
            <img src="Assets/images/illustrations/user.png" alt="Client" class="rounded-circle">
          </div>
          <p class="text-muted fst-italic">
            “Game Changer made my university application stress-free. Their support and guidance helped me get accepted into my dream university in Canada.”
          </p>
          <h6 class="fw-semibold mt-3 mb-0">Sarah Wanjiku</h6>
          <small class="text-primary">Student, Canada</small>
          <div class="stars mt-2 text-warning">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star-half-stroke"></i>
          </div>
        </div>
      </div>

      <!-- Testimonial 2 -->
      <div class="col-md-6 col-lg-4">
        <div class="testimonial-card p-4 h-100 shadow-sm">
          <div class="testimonial-avatar mb-3">
            <img src="Assets/images/illustrations/user.png" alt="Client" class="rounded-circle">
          </div>
          <p class="text-muted fst-italic">
            “Their visa assistance was incredible. I had all my documents handled professionally and my student visa was approved faster than I expected!”
          </p>
          <h6 class="fw-semibold mt-3 mb-0">John Mwangi</h6>
          <small class="text-primary">Student, UK</small>
          <div class="stars mt-2 text-warning">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
          </div>
        </div>
      </div>

      <!-- Testimonial 3 -->
      <div class="col-md-6 col-lg-4">
        <div class="testimonial-card p-4 h-100 shadow-sm">
          <div class="testimonial-avatar mb-3">
            <img src="Assets/images/illustrations/user.png" alt="Client" class="rounded-circle">
          </div>
          <p class="text-muted fst-italic">
            “Thanks to their career guidance and consultation, I’m now working abroad and living my dream. They truly change lives!”
          </p>
          <h6 class="fw-semibold mt-3 mb-0">Mary Otieno</h6>
          <small class="text-primary">Professional, Australia</small>
          <div class="stars mt-2 text-warning">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star-half-stroke"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CSS -->
<style>
.testimonials-section {
  background: linear-gradient(135deg, #f9fbff 0%, #f3f7ff 100%);
}

.testimonial-card {
  border-radius: 1rem;
  background: #fff;
  transition: all 0.3s ease;
  border: 1px solid rgba(0, 0, 0, 0.05);
}

.testimonial-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.testimonial-avatar img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border: 3px solid #ff6600;
  padding: 2px;
}

.stars i {
  margin: 0 1px;
}

@media (max-width: 768px) {
  .testimonial-avatar img {
    width: 70px;
    height: 70px;
  }
}
</style>



  <!-- Footer -->
  <footer>
    <div class="container">
      <p class="mb-0 text-muted">© 2025 GameChanger Consulting LTD | All Rights Reserved</p>
    </div>
  </footer>


  <link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
/>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
