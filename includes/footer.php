<!-- ======= Footer Section ======= -->
<footer class="pt-5 bg-dark text-light mt-5">
  <div class="container">
    <div class="row gy-4">

      <!-- Contact Info -->
      <div class="col-md-3">
        <h5 class="fw-bold mb-3 text-uppercase text-warning">Contact Us</h5>
        <p><i class="bi bi-geo-alt-fill me-2"></i> Mombasa, Kenya</p>
        <p><i class="bi bi-envelope-fill me-2"></i> info@gcstudyabroad.co.ke</p>
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
          <li>Canada</li>
          <li>United Kingdom</li>
          <li>USA</li>
          <li>Australia</li>
          <li>Germany</li>
        </ul>
      </div>

    </div>

    <hr class="my-4 border-light">
    <div class="text-center small">
      <p class="mb-0">&copy; <?= date('Y') ?> GameChanger Consulting. All Rights Reserved.</p>
    </div>
  </div>
</footer>



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


<script>
function copyLink() {
  const url = window.location.href;
  navigator.clipboard.writeText(url);
  alert("Link copied to clipboard!");
}
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>