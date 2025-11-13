<?php 
session_start();
require '../database/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Appointments - The Game Changer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
</head>
<body>


<?php include('./includes/navbar.php'); ?>



<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="fw-bold">All Appointments</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAppointmentModal">
      <i class="bi bi-plus-circle"></i> Add Appointment
    </button>
  </div>

  <table id="appointmentsTable" class="table table-striped table-bordered text-center align-middle">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Date</th>
        <th>Time</th>
        <th>Service</th>
        <th>Created At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $query = "SELECT * FROM appointments ORDER BY created_at DESC";
      $result = $conn->query($query);
      $sn = 1;
      while ($row = $result->fetch_assoc()):
          $dt = !empty($row['preferred_datetime']) ? new DateTime($row['preferred_datetime']) : null;
          $displayDate = $dt ? $dt->format('Y-m-d') : '-';
          $displayTime = $dt ? $dt->format('H:i') : '-';
          $service = $row['service_type'] ?? '-';
          $notes = $row['message'] ?? '-';
      ?>
      <tr>
        <td><?= $sn++ ?></td>
        <td><?= htmlspecialchars($row['full_name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><?= $displayDate ?></td>
        <td><?= $displayTime ?></td>
        <td><?= htmlspecialchars($service) ?></td>
        <td><?= htmlspecialchars($row['created_at']) ?></td>
        <td>
          <button class="btn btn-sm btn-secondary viewBtn"
            data-bs-toggle="modal" data-bs-target="#viewModal"
            data-name="<?= htmlspecialchars($row['full_name']) ?>"
            data-email="<?= htmlspecialchars($row['email']) ?>"
            data-phone="<?= htmlspecialchars($row['phone']) ?>"
            data-date="<?= $displayDate ?>"
            data-time="<?= $displayTime ?>"
            data-service="<?= htmlspecialchars($service) ?>"
            data-notes="<?= htmlspecialchars($notes) ?>">
            <i class="bi bi-eye"></i>
          </button>

          <button class="btn btn-sm btn-info editBtn"
            data-bs-toggle="modal" data-bs-target="#editAppointmentModal"
            data-id="<?= $row['id'] ?>"
            data-name="<?= htmlspecialchars($row['full_name']) ?>"
            data-email="<?= htmlspecialchars($row['email']) ?>"
            data-phone="<?= htmlspecialchars($row['phone']) ?>"
            data-service="<?= htmlspecialchars($service) ?>"
            data-date="<?= $displayDate ?>"
            data-time="<?= $displayTime ?>"
            data-notes="<?= htmlspecialchars($notes) ?>">
            <i class="bi bi-pencil-square"></i>
          </button>

          <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal"
            data-id="<?= $row['id'] ?>">
            <i class="bi bi-trash"></i>
          </button>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Add Appointment Modal -->
<div class="modal fade" id="addAppointmentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="../backend/add_appointment.php" method="POST" class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Add Appointment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body row g-3">
        <div class="col-md-6">
          <label class="form-label">Full Name</label>
          <input type="text" name="full_name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Service Type</label>
          <select name="service_type" class="form-select" required>
            <option value="">Select Service</option>
            <option value="University Applications">University Applications</option>
            <option value="Visa Applications">Visa Applications</option>
            <option value="University Choices Assistance">University Choices Assistance</option>
            <option value="Career Guidance">Career Guidance</option>
            <option value="Visa Guidance">Visa Guidance</option>
            <option value="Consultation">Consultation</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Preferred Date & Time</label>
          <input type="datetime-local" name="preferred_datetime" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Preferred Platform</label>
          <select name="preferred_platform" class="form-select">
            <option value="">Select Platform</option>
            <option value="Phone Call">Phone Call</option>
            <option value="WhatsApp Call">WhatsApp Call</option>
            <option value="Google Meet">Google Meet</option>
          </select>
        </div>
        <div class="col-md-12">
          <label class="form-label">Message / Notes</label>
          <textarea name="message" class="form-control" rows="3"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary">Save Appointment</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Appointment Modal -->
<div class="modal fade" id="editAppointmentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="../backend/edit_appointment.php" method="POST" class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Edit Appointment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body row g-3">
        <input type="hidden" name="id" id="edit_id">
        <div class="col-md-6">
          <label class="form-label">Full Name</label>
          <input type="text" name="full_name" id="edit_name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" id="edit_email" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" id="edit_phone" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Service</label>
          <input type="text" name="service_type" id="edit_service" class="form-control">
        </div>
          <div class="col-md-6">
            <label class="form-label">Preferred Date & Time</label>
            <input type="datetime-local" name="preferred_datetime" id="edit_datetime" class="form-control" required>
            </div>
            <div class="col-md-12">
            <label class="form-label">Notes</label>
            <textarea name="message" id="edit_notes" class="form-control" rows="3"></textarea>
            </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Appointment Details</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Name:</strong> <span id="vName"></span></p>
        <p><strong>Email:</strong> <span id="vEmail"></span></p>
        <p><strong>Phone:</strong> <span id="vPhone"></span></p>
        <p><strong>Service:</strong> <span id="vService"></span></p>
        <p><strong>Date:</strong> <span id="vDate"></span></p>
        <p><strong>Time:</strong> <span id="vTime"></span></p>
        <p><strong>Notes:</strong> <span id="vNotes"></span></p>
      </div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form action="../backend/delete_appointment.php" method="POST" class="modal-content">
      <div class="modal-body text-center">
        <h5>Are you sure you want to delete this appointment?</h5>
        <input type="hidden" name="id" id="delete_id">
        <button type="submit" class="btn btn-danger mt-3">Yes, Delete</button>
      </div>
    </form>
  </div>
</div>

<footer class="text-center mt-4 mb-3 text-muted">&copy; 2025 Astra Softwares</footer>

<script>
$(document).ready(() => $('#appointmentsTable').DataTable());

// View Appointment
$('.viewBtn').on('click', function() {
  $('#vName').text($(this).data('name'));
  $('#vEmail').text($(this).data('email'));
  $('#vPhone').text($(this).data('phone'));
  $('#vService').text($(this).data('service'));
  $('#vDate').text($(this).data('date'));
  $('#vTime').text($(this).data('time'));
  $('#vNotes').text($(this).data('notes'));
});

// Edit Appointment
$('.editBtn').on('click', function() {
  $('#edit_id').val($(this).data('id'));
  $('#edit_name').val($(this).data('name'));
  $('#edit_email').val($(this).data('email'));
  $('#edit_phone').val($(this).data('phone'));
  $('#edit_service').val($(this).data('service'));
  $('#edit_date').val($(this).data('date'));
  $('#edit_time').val($(this).data('time'));
  $('#edit_notes').val($(this).data('notes'));
});

// Delete Modal
$('#deleteModal').on('show.bs.modal', e => {
  $('#delete_id').val($(e.relatedTarget).data('id'));
});
</script>

</body>
</html>
