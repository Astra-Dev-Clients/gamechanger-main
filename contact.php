<?php include('./includes/header.php'); ?>

<!-- ======= Contact Us Section ======= -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold text-primary">Contact Us</h2>
      <p class="text-muted">We’re here to guide you on your study and work abroad journey. Reach out anytime!</p>
    </div>

    <div class="row g-4">
      <!-- Contact Details -->
      <div class="col-md-5">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body p-4">
            <h5 class="fw-bold text-primary mb-3">Get in Touch</h5>
            <p class="mb-2"><i class="bi bi-geo-alt-fill text-warning me-2"></i><strong>Location:</strong> Mombasa, Kenya</p>
            <p class="mb-2"><i class="bi bi-envelope-fill text-warning me-2"></i><strong>Email:</strong> info@gcstudyabroad.co.ke</p>
            <p class="mb-2"><i class="bi bi-whatsapp text-success me-2"></i><strong>WhatsApp:</strong> <a href="https://wa.me/254726874170" target="_blank" class="text-decoration-none">+254 726 874 170</a></p>
            <p class="mb-2"><i class="bi bi-telephone-fill text-warning me-2"></i><strong>Phone:</strong> +254 726 874 170</p>

            <hr>

            <h6 class="fw-bold text-primary">Follow Us</h6>
            <div class="d-flex gap-3 fs-4 mt-2">
              <a href="#" target="_blank" class="text-dark"><i class="bi bi-facebook"></i></a>
              <a href="#" target="_blank" class="text-dark"><i class="bi bi-twitter-x"></i></a>
              <a href="#" target="_blank" class="text-dark"><i class="bi bi-tiktok"></i></a>
              <a href="#" target="_blank" class="text-dark"><i class="bi bi-youtube"></i></a>
            </div>
          </div>
        </div>
      </div>

      <!-- Contact Form -->
      <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body p-4">
            <h5 class="fw-bold text-primary mb-3">Send Us a Message</h5>
            <form action="backend/send_message.php" method="POST">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Full Name</label>
                  <input type="text" name="full_name" class="form-control" placeholder="Your Name" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Email</label>
                  <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Phone</label>
                  <input type="text" name="phone" class="form-control" placeholder="+254 ..." required>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Subject</label>
                  <input type="text" name="subject" class="form-control" placeholder="Subject" required>
                </div>
                <div class="col-12">
                  <label class="form-label fw-semibold">Message</label>
                  <textarea name="message" class="form-control" rows="4" placeholder="Type your message..." required></textarea>
                </div>
                <div class="col-12 text-end">
                  <button type="submit" class="btn btn-orange px-4">Send Message</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include('./includes/footer.php'); ?>

<!-- ======= Styles ======= -->
<style>
.btn-orange {
  background-color: #ff6600;
  color: #fff;
  border: none;
  border-radius: 30px;
  font-weight: 600;
  padding: 10px 25px;
  transition: all 0.3s ease;
}
.btn-orange:hover {
  background-color: #e65c00;
  transform: scale(1.05);
}
.card {
  border-radius: 12px;
}
</style>
