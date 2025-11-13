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



<?php include('./includes/header.php'); ?>

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
      <a href="appointment.php" class="btn btn-orange btn-lg fw-semibold px-4">
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
      <?php
      // Fetch latest 3 blog posts
      $blogQuery = "SELECT * FROM blog_posts ORDER BY created_at DESC LIMIT 3";
      $blogs = $conn->query($blogQuery);

      if ($blogs && $blogs->num_rows > 0):
        while ($blog = $blogs->fetch_assoc()):
            // Decode Quill Delta JSON
            $blogData = json_decode($blog['blog_json'], true);

            // Extract plain text from 'ops'
            $excerpt = '';
            if (!empty($blogData['ops'])) {
                foreach ($blogData['ops'] as $op) {
                    if (isset($op['insert'])) {
                        $excerpt .= strip_tags($op['insert']);
                    }
                }
            }
            $excerpt = substr($excerpt, 0, 150); // limit to 150 chars

            // Banner
            $banner = htmlspecialchars($blog['banner'] ?? '');
      ?>
      <div class="col-md-4">
        <div class="card blog-card border-0 shadow-sm h-100">
          <?php if ($banner): ?>
            <img src="./backend/<?= htmlspecialchars($banner) ?>" class="card-img-top" alt="Blog Banner">
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title text-primary"><?= htmlspecialchars($blog['title']) ?></h5>
            <p class="card-text text-muted"><?= htmlspecialchars($excerpt) ?>...</p>
            <a href="blog_details.php?id=<?= $blog['id'] ?>" class="btn btn-orange btn-sm">Read More</a>
          </div>
        </div>
      </div>
      <?php
        endwhile;
      else:
      ?>
        <p class="text-center text-muted">No blog posts found.</p>
      <?php endif; ?>
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



<!-- ======= Frequently Asked Questions Section ======= -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold text-primary">Frequently Asked Questions</h2>
      <p class="text-muted">Find answers to the most common questions about our services and process.</p>
    </div>

    <div class="accordion" id="faqAccordion">
      
      <div class="accordion-item">
        <h2 class="accordion-header" id="faq1Heading">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1">
            How do I book an appointment?
          </button>
        </h2>
        <div id="faq1" class="accordion-collapse collapse show" aria-labelledby="faq1Heading" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            You can book an appointment by filling out the appointment form on our website and selecting your preferred date, time, and platform. Our team will contact you to confirm.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="faq2Heading">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
            Which services do you provide?
          </button>
        </h2>
        <div id="faq2" class="accordion-collapse collapse" aria-labelledby="faq2Heading" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            We provide assistance with university applications, visa guidance, career counseling, and consultation for studying and working abroad.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="faq3Heading">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
            Can I reschedule or cancel my appointment?
          </button>
        </h2>
        <div id="faq3" class="accordion-collapse collapse" aria-labelledby="faq3Heading" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Yes, you can reschedule or cancel your appointment by contacting our support team via email or phone at least 24 hours in advance.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="faq4Heading">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">
            Do you charge for initial consultation?
          </button>
        </h2>
        <div id="faq4" class="accordion-collapse collapse" aria-labelledby="faq4Heading" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Our initial consultation is free. Fees may apply for specialized services such as visa application processing and university application guidance.
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
<!-- End FAQ Section -->




<?php include('./includes/footer.php'); ?>



