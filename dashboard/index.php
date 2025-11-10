<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<!-- Main Content -->
<div class="main-content p-4" style="margin-left: 250px;">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Dashboard Analytics</h2>
    <button class="btn btn-primary"><i class="fas fa-sync-alt me-2"></i>Refresh</button>
  </div>

  <!-- Analytics Cards -->
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body d-flex align-items-center">
          <i class="fas fa-calendar-check fa-2x text-primary me-3"></i>
          <div>
            <h5 class="card-title mb-1">Total Appointments</h5>
            <h3 class="fw-bold">124</h3>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body d-flex align-items-center">
          <i class="fas fa-hourglass-half fa-2x text-warning me-3"></i>
          <div>
            <h5 class="card-title mb-1">Pending Approvals</h5>
            <h3 class="fw-bold">18</h3>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body d-flex align-items-center">
          <i class="fas fa-check-circle fa-2x text-success me-3"></i>
          <div>
            <h5 class="card-title mb-1">Completed Consultations</h5>
            <h3 class="fw-bold">89</h3>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Placeholder for Graph / Chart -->
  <div class="card mt-5 shadow-sm border-0">
    <div class="card-header bg-white fw-semibold">
      Appointment Trends
    </div>
    <div class="card-body">
      <canvas id="appointmentsChart" height="120"></canvas>
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>
