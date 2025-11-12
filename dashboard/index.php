<?php
session_start();
require '../database/db.php';

// $user_id = $_SESSION['user_id'] ?? 1;
$user_id = 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>The Game Changer - <?= htmlspecialchars($_SESSION['store_name'] ?? 'Astra Portal'); ?></title>

  <!-- Bootstrap & DataTables -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

  <style>
    body { background-color: #f8f9fa; }
    .btn-primary { background-color: #143D60; border: none; }
    .navbar { background-color: #143D60; }
    footer { text-align: center; padding: 10px 0; color: gray; margin-top: 40px; }
    .modal-img { width: 100%; max-height: 300px; object-fit: cover; border-radius: 10px; margin-bottom: 15px; }

      .nav-icon { font-size: 1.8rem; }
  .navbar-brand small { font-size: 0.75rem; font-weight: 500; margin-top: -6px; }
  .nav-link { display: flex; flex-direction: column; align-items: center; padding: 0.5rem 0.75rem; }
  .nav-link span { font-size: 0.7rem; }

  .nav-icon-wrapper {
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 0.75rem;
    padding: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    margin-right: 8px;
    transition: background-color 0.3s ease;
  }

  .nav-link {
    display: flex;
    align-items: center;
    gap: 5px;
    font-weight: 500;
  }

  .nav-link:hover .nav-icon-wrapper {
    background-color: #ffffff33;
  }

  .nav-icon {
    font-size: 18px;
    color: white;
  }

  .navbar-nav .nav-item {
    margin-left: 10px;
  }

  .nav-active{
    border:1px solid #fff;
  }
  </style>
</head>
<body>

<!-- Status Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080;">
  <?php if(isset($_SESSION['success'])): ?>
    <div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" id="statusToast">
      <div class="d-flex">
        <div class="toast-body">
          <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  <?php elseif(isset($_SESSION['error'])): ?>
    <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" id="statusToast">
      <div class="d-flex">
        <div class="toast-body">
          <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  <?php endif; ?>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toastEl = document.getElementById('statusToast');
    if (toastEl) {
      const toast = new bootstrap.Toast(toastEl, { delay: 4000 }); // 4 seconds
      toast.show();
    }
  });
</script>
<!-- end of toast -->



<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #143D60;"> 
  <div class="container">
  <a class="navbar-brand d-flex align-items-start" href="#">
    <h4>The Game Changer</h4>
  </a>


    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarIcons">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarIcons">
      <ul class="navbar-nav mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link text-white" href="index.php">
            <span class="nav-icon-wrapper nav-active"><i class="bi bi-house nav-icon "></i></span> Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="sell.php">
            <span class="nav-icon-wrapper"><i class="bi bi-ticket nav-icon"></i></span> Appointments
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="transactions.php">
            <span class="nav-icon-wrapper"><i class="bi bi-people  nav-icon"></i></span> Users
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="blog.php">
            <span class="nav-icon-wrapper"><i class="bi bi-megaphone nav-icon"></i></span> Blog
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="stock_kpis.php">
            <span class="nav-icon-wrapper"><i class="bi bi-bar-chart-line nav-icon"></i></span> Analytics
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="settings.php">
            <span class="nav-icon-wrapper"><i class="bi bi-gear nav-icon"></i></span> Settings
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="../auth/logout.php">
            <span class="nav-icon-wrapper"><i class="bi bi-box-arrow-right nav-icon"></i></span> Logout
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>






<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="fw-bold">All Opportunities</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOpportunityModal">
      <i class="bi bi-plus-circle"></i> Add Opportunity
    </button>
  </div>

  <table id="opportunityTable" class="table table-striped table-bordered text-center align-middle">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Title</th>
        <th>Type</th>
        <th>Country</th>
        <th>Institution / Company</th>
        <th>Sponsorship</th>
        <th>Created At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT o.*, t.type_name 
              FROM opportunities o
              LEFT JOIN opportunity_types t ON o.type_id = t.id
              WHERE posted_by = ?
              ORDER BY o.created_at DESC";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();
      $sn = 1;

      while ($row = $result->fetch_assoc()):
      ?>
      <tr>
        <td><?= $sn++ ?></td>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['type_name']) ?></td>
        <td><?= htmlspecialchars($row['country'] ?? '-') ?></td>
        <td><?= htmlspecialchars($row['institution'] ?? $row['industry'] ?? '-') ?></td>
        <td><?= htmlspecialchars($row['sponsorship']) ?></td>
        <td><?= htmlspecialchars($row['created_at']) ?></td>
        <td>



         

          <button class="btn btn-sm btn-secondary viewBtn"
            data-bs-toggle="modal" data-bs-target="#viewModal"
            data-title="<?= htmlspecialchars($row['title']) ?>"
            data-description="<?= htmlspecialchars($row['description']) ?>"
            data-country="<?= htmlspecialchars($row['country']) ?>"
            data-sponsorship="<?= htmlspecialchars($row['sponsorship']) ?>"
            data-type="<?= htmlspecialchars($row['type_name']) ?>"
            data-institution="<?= htmlspecialchars($row['institution'] ?? '-') ?>"
            data-website="<?= htmlspecialchars($row['co_web'] ?? '') ?>"
            data-image="<?= htmlspecialchars($row['image_url'] ?? '') ?>"
            data-link="<?= htmlspecialchars($row['opp_url'] ?? '') ?>">
            <i class="bi bi-eye"></i>
          </button>

          <button class="btn btn-sm btn-info text-white editBtn"
            data-bs-toggle="modal" data-bs-target="#editModal"
            data-id="<?= $row['id'] ?>"
            data-title="<?= htmlspecialchars($row['title']) ?>"
            data-country="<?= htmlspecialchars($row['country']) ?>"
            data-sponsorship="<?= htmlspecialchars($row['sponsorship']) ?>"
            data-description="<?= htmlspecialchars($row['description']) ?>"
            data-institution="<?= htmlspecialchars($row['institution'] ?? '') ?>"
            data-website="<?= htmlspecialchars($row['co_web'] ?? '') ?>"
            data-course="<?= htmlspecialchars($row['course'] ?? '') ?>"
            data-image="<?= htmlspecialchars($row['image_url'] ?? '') ?>"
            data-url="<?= htmlspecialchars($row['opp_url'] ?? '') ?>"
            data-typeid="<?= $row['type_id'] ?>">
            
            <i class="bi bi-pencil-square"></i>
          </button>



          <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal"
            data-id="<?= $row['id'] ?>">
            <i class="bi bi-trash"></i>
          </button>

          
          <a href="poster.php?id=<?= $row['id'] ?>" 
            class="btn btn-sm  text-white btn-warning" 
            title="Open Link">
            <i class="bi bi-box-arrow-up-right"></i>
          </a>


        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Add Opportunity Modal -->
<div class="modal fade" id="addOpportunityModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="../backend/add_opportunity.php" method="POST" enctype="multipart/form-data" class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Add New Opportunity</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body row g-3">
        <div class="col-md-6">
          <label class="form-label">Title</label>
          <input type="text" name="title" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Type</label>
          <select name="type_id" class="form-select" required>
            <option value="">Select type</option>
            <?php
            $typeQ = $conn->query("SELECT * FROM opportunity_types");
            while ($t = $typeQ->fetch_assoc()) {
              echo "<option value='{$t['id']}'>{$t['type_name']}</option>";
            }
            ?>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Country</label>
          <input type="text" name="country" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Sponsorship</label>
          <select name="sponsorship" class="form-select">
            <option value="Sponsored">Sponsored</option>
            <option value="Unsponsored">Unsponsored</option>
            <option value="Partially Sponsored">Partially Sponsored</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Institution / Company</label>
          <input type="text" name="institution" class="form-control" placeholder="e.g. Harvard University or Google" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Website</label>
          <input type="text" name="web" class="form-control" placeholder="company web link" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Opportunity Url</label>
          <input type="text" name="opp_url" class="form-control" placeholder="link" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Course / Job Title</label>
          <input type="text" name="course" class="form-control" placeholder="e.g. Computer Science or Software Engineer">
        </div>

        <div class="col-md-12">
          <label class="form-label">Description</label>
          <textarea name="description" rows="3" class="form-control" required></textarea>
        </div>

        <div class="col-md-12">
          <label class="form-label">Image</label>
          <input type="file" name="image_url" class="form-control">
        </div>

      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content p-4">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">View Opportunity</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <img id="viewImage" src="" alt="Opportunity Image" class="modal-img d-none">
        <h4 id="viewTitle"></h4>
        <p><strong>Type:</strong> <span id="viewType"></span></p>
        <p><strong>Country:</strong> <span id="viewCountry"></span></p>
        <p><strong>Sponsorship:</strong> <span id="viewSponsorship"></span></p>
        <p><strong>Institution:</strong> <span id="viewInstitution"></span></p>
        <p><strong>Website </strong> <a id="viewWeb" href="" ></a></p>
         <p><strong>Oppotunity Link </strong> <a href="" id="viewLink"></a></p>
        <hr>
        <p id="viewDescription"></p>
      </div>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="../backend/edit_opportunity.php" method="POST" enctype="multipart/form-data" class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Edit Opportunity</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body row g-3">
        <input type="hidden" name="id" id="edit_id">
        <div class="col-md-6">
          <label class="form-label">Title</label>
          <input type="text" name="title" id="edit_title" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Type</label>
          <select name="type_id" id="edit_type_id" class="form-select" required>
            <option value="">Select type</option>
            <?php
            $typeQ = $conn->query("SELECT * FROM opportunity_types");
            while ($t = $typeQ->fetch_assoc()) {
              echo "<option value='{$t['id']}'>{$t['type_name']}</option>";
            }
            ?>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Country</label>
          <input type="text" name="country" id="edit_country" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Sponsorship</label>
          <select name="sponsorship" id="edit_sponsorship" class="form-select">
            <option value="Sponsored">Sponsored</option>
            <option value="Unsponsored">Unsponsored</option>
            <option value="Partially Sponsored">Partially Sponsored</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Institution / Company</label>
          <input type="text" name="institution" id="edit_institution" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Website</label>
          <input type="text" name="web" id="edit_web" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Opportunity Link</label>
          <input type="text" name="opp_url" id="edit_link" class="form-control" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Course / Job Title</label>
          <input type="text" name="course" id="edit_course" class="form-control">
        </div>

        <div class="col-md-12">
          <label class="form-label">Description</label>
          <textarea name="description" id="edit_description" rows="3" class="form-control" required></textarea>
        </div>
        <div class="col-md-12">
          <label class="form-label">Current Image</label>
          <img id="edit_preview" src="" class="modal-img d-none" alt="Current Image">
          <label class="form-label mt-2">Upload New Image</label>
          <input type="file" name="image_url" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form action="../backend/delete_opportunity.php" method="POST" class="modal-content">
      <div class="modal-body text-center">
        <h5>Are you sure you want to delete this opportunity?</h5>
        <input type="hidden" name="id" id="delete_id">
        <button type="submit" class="btn btn-danger mt-3">Yes, Delete</button>
      </div>
    </form>
  </div>
</div>

<footer>
  &copy; 2025 Astra Softwares
</footer>

<script>
  $(document).ready(() => $('#opportunityTable').DataTable());

  // Delete
  $('#deleteModal').on('show.bs.modal', e => {
    $('#delete_id').val($(e.relatedTarget).data('id'));
  });

  // View
  $('.viewBtn').on('click', function() {
    $('#viewTitle').text($(this).data('title'));
    $('#viewType').text($(this).data('type'));
    $('#viewCountry').text($(this).data('country'));
    $('#viewSponsorship').text($(this).data('sponsorship'));
    $('#viewInstitution').text($(this).data('institution'));
    $('#viewDescription').text($(this).data('description'));
    const website = $(this).data('website');
    const oppLink = $(this).data('link');

    $('#viewWeb')
      .text(website)
      .attr('href', website.startsWith('http') ? website : 'https://' + website)
      .attr('target', '_blank');

    $('#viewLink')
      .text(oppLink)
      .attr('href', oppLink.startsWith('http') ? oppLink : 'https://' + oppLink)
      .attr('target', '_blank');




    const img = $(this).data('image');
    if (img) {
      $('#viewImage').attr('src', img).removeClass('d-none');
    } else {
      $('#viewImage').addClass('d-none');
    }
  });

  // Edit
$('.editBtn').on('click', function() {
  $('#edit_id').val($(this).data('id'));
  $('#edit_title').val($(this).data('title'));
  $('#edit_country').val($(this).data('country'));
  $('#edit_sponsorship').val($(this).data('sponsorship'));
  $('#edit_description').val($(this).data('description'));
  $('#edit_institution').val($(this).data('institution'));
  $('#edit_course').val($(this).data('course'));
  $('#edit_type_id').val($(this).data('typeid'));
  const website = $(this).data('website');
  const url = $(this).data('url');

  // Fix: These should update the EDIT modal inputs
  $('#edit_web').val(website);
  $('#edit_link').val(url);

  // Optional: Preview current links for convenience
  if (website) {
    $('#edit_web').val(website.startsWith('http') ? website : 'https://' + website);
  }
  if (url) {
    $('#edit_link').val(url.startsWith('http') ? url : 'https://' + url);
  }



  const img = $(this).data('image');
  if (img) {
    $('#edit_preview').attr('src', img).removeClass('d-none');
  } else {
    $('#edit_preview').addClass('d-none');
  }
});

</script>

</body>
</html>
