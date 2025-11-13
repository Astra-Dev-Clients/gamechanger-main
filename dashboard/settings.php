<?php
session_start();
require '../database/db.php';

// Ensure user is logged in
// if (!isset($_SESSION['user_id'])) {
//     header("Location: ../auth/login.php");
//     exit();
// }

$user_id = $_SESSION['user_id'] ?? 1;

// Fetch user data
$stmt = $conn->prepare("SELECT id, f_name, l_name, email, role, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("User not found.");
}

$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Poppins', sans-serif;
    }
    .card {
      border-radius: 10px;
    }
    .btn-primary {
      background-color: #143D60;
      border-color: #143D60;
    }
    .btn-primary:hover {
      background-color: #0f2e4b;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<?php include('./includes/navbar.php'); ?>


<div class="container my-5">
  <div class="card shadow-sm">
    <div class="card-header text-white" style="background-color: #143D60;">
      <h4 class="mb-0">Manage Your Profile</h4>
    </div>
    <form action="../backend/update_profile.php" method="POST" class="card-body">

      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
      <?php elseif (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
      <?php endif; ?>

      <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">First Name</label>
          <input type="text" name="f_name" class="form-control" value="<?= htmlspecialchars($user['f_name']) ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Last Name</label>
          <input type="text" name="l_name" class="form-control" value="<?= htmlspecialchars($user['l_name']) ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Role</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars(ucfirst($user['role'])) ?>" readonly>
        </div>
        <div class="col-md-6">
          <label class="form-label">Created At</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($user['created_at']) ?>" readonly>
        </div>
        <div class="col-md-6">
          <label class="form-label">New Password <small class="text-muted">(leave blank to keep current)</small></label>
          <input type="password" name="password" class="form-control" placeholder="••••••••">
        </div>
      </div>

      <div class="mt-4">
        <button type="submit" class="btn btn-primary px-4">Save Changes</button>
      </div>

    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
