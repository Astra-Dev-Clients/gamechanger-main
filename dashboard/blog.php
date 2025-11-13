<?php
session_start();
require '../database/db.php';

// For demo, using user_id = 1 (replace with session user_id)
$user_id = $_SESSION['user_id'] ?? 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Blog Dashboard</title>

  <!-- Bootstrap & DataTables -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <!-- Quill Editor -->
  <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
  <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

  <style>
    body { background-color: #f8f9fa; }
    .navbar { background-color: #143D60; }
    .btn-primary { background-color: #143D60; border: none; }
    footer { text-align:center; padding:10px 0; color:gray; margin-top:40px; }
    .banner-thumb { width: 120px; height: 60px; object-fit: cover; border-radius: 6px; }

    /* Modal scroll & footer fix */
    .modal-body-scrollable {
      max-height: calc(80vh - 130px);
      overflow-y: auto;
    }
    #quillEditor { min-height: 300px; }
  </style>
</head>
<body>

<?php include('./includes/navbar.php'); ?>



<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="fw-bold">All Blogs</h2>
    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBlogModal">
      <i class="bi bi-plus-circle"></i> Create Blog
    </a>
  </div>

  <table id="blogsTable" class="table table-striped table-bordered text-center align-middle">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Title</th>
        <th>Author</th>
        <th>Banner</th>
        <th>Created At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT * FROM blog_posts ORDER BY created_at DESC";
      $result = $conn->query($sql);
      $sn = 1;
      while ($row = $result->fetch_assoc()):
      ?>
      <tr>
        <td><?= $sn++ ?></td>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['author']) ?></td>
        <td>
          <?php if($row['banner']): ?>
            <img src="../backend/<?= htmlspecialchars($row['banner']) ?>" class="banner-thumb" alt="Banner">
          <?php else: ?> -
          <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($row['created_at']) ?></td>
        <td>
          <button class="btn btn-sm btn-secondary viewBtn" 
              data-title="<?= htmlspecialchars($row['title']) ?>"
              data-author="<?= htmlspecialchars($row['author']) ?>"
              data-banner="../backend/<?= htmlspecialchars($row['banner']) ?>"
              data-json='<?= htmlspecialchars($row['blog_json'], ENT_QUOTES, 'UTF-8') ?>'
              data-bs-toggle="modal" data-bs-target="#viewModal">
              <i class="bi bi-eye"></i>
          </button>

         <button class="btn btn-sm btn-info text-white editBtn"
            data-id="<?= $row['id'] ?>"
            data-title="<?= htmlspecialchars($row['title']) ?>"
            data-banner="../backend/<?= htmlspecialchars($row['banner']) ?>"
            data-json='<?= htmlspecialchars($row['blog_json'], ENT_QUOTES, 'UTF-8') ?>'
            data-bs-toggle="modal" data-bs-target="#editBlogModal">
            <i class="bi bi-pencil-square"></i>
            </button>


          <button class="btn btn-sm btn-danger deleteBtn" 
            data-id="<?= $row['id'] ?>" 
            data-bs-toggle="modal" data-bs-target="#deleteModal">
            <i class="bi bi-trash"></i>
          </button>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Add Blog Modal -->
<div class="modal fade" id="addBlogModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Create Blog</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="../backend/add_blog.php" method="POST" enctype="multipart/form-data">
        <div class="modal-body modal-body-scrollable">
          <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Banner Image</label>
            <input type="file" name="banner" class="form-control" accept="image/*" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Content</label>
            <div id="quillEditor"></div>
            <input type="hidden" name="content_json" id="content_json">
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-primary" type="submit">Save Blog</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View Blog Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="viewTitle"></h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body modal-body-scrollable">
        <p><strong>Author:</strong> <span id="viewAuthor"></span></p>
        <img id="viewBanner" src="" alt="Banner" class="banner-thumb mb-3 d-none">
        <hr>
        <div id="viewContent"></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form action="../backend/delete_blog.php" method="POST" class="modal-content">
      <div class="modal-body text-center">
        <h5>Are you sure you want to delete this blog post?</h5>
        <input type="hidden" name="id" id="delete_id">
        <button type="submit" class="btn btn-danger mt-3">Yes, Delete</button>
      </div>
    </form>
  </div>
</div>



<!-- Edit Blog Modal -->
<div class="modal fade" id="editBlogModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Edit Blog</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="../backend/update_blog.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" id="edit_id">
        <div class="modal-body modal-body-scrollable">
          <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" id="edit_title" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Current Banner</label><br>
            <img id="edit_banner_preview" src="" class="banner-thumb mb-2 d-none" alt="Banner">
            <input type="file" name="banner" class="form-control" accept="image/*">
            <small class="text-muted">Leave empty to keep existing banner.</small>
          </div>

          <div class="mb-3">
            <label class="form-label">Content</label>
            <div id="editQuillEditor"></div>
            <input type="hidden" name="content_json" id="edit_content_json">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>




<div id="tempQuillContainer" style="display:none;"></div>


<footer>
  &copy; 2025 Astra Softwares
</footer>

<script>
$(document).ready(() => $('#blogsTable').DataTable());

// Delete Modal
$('#deleteModal').on('show.bs.modal', e => {
  $('#delete_id').val($(e.relatedTarget).data('id'));
});

// Initialize Quill for Add Blog
var quill = new Quill('#quillEditor', {
  theme: 'snow',
  placeholder: 'Write your blog content here...',
  modules: {
    toolbar: [
      [{ 'header': [1, 2, 3, false] }],
      ['bold', 'italic', 'underline'],
      ['link', 'image'],
      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
      ['clean']
    ]
  }
});

// Save Quill content as JSON on submit
$('#addBlogModal form').on('submit', function(){
  const delta = quill.getContents();
  $('#content_json').val(JSON.stringify(delta));
});

// View Blog Modal - render content correctly
// View Blog Modal - render content correctly
$('.viewBtn').on('click', function() {
  const title = $(this).data('title');
  const author = $(this).data('author');
  const banner = $(this).data('banner');
  let json = $(this).data('json'); // might be object OR string

  $('#viewTitle').text(title);
  $('#viewAuthor').text(author);

  if (banner && banner !== '../backend/') {
    $('#viewBanner').attr('src', banner).removeClass('d-none');
  } else {
    $('#viewBanner').addClass('d-none');
  }

  // 🧠 Debug Output
  console.log("Debug JSON Preview:", json);

  // ✅ If it's already an object, skip parsing
  let blog = (typeof json === 'object') ? json : JSON.parse(json);

  let delta = null;

  if (blog.content && Array.isArray(blog.content.ops)) {
    delta = blog.content;
  } else if (blog.ops) {
    delta = blog;
  } else if (Array.isArray(blog.content)) {
    delta = { ops: blog.content };
  }

  if (delta && delta.ops && delta.ops.length > 0) {
    const tempDiv = document.createElement('div');
    const tempQuill = new Quill(tempDiv);
    tempQuill.setContents(delta);
    const html = tempDiv.querySelector('.ql-editor').innerHTML;
    $('#viewContent').html(html);
  } else {
    $('#viewContent').html('<p><em>No content available for this blog.</em></p>');
  }
});



// Initialize Quill for Edit Modal
var editQuill = new Quill('#editQuillEditor', {
  theme: 'snow',
  placeholder: 'Edit your blog content...',
  modules: {
    toolbar: [
      [{ 'header': [1, 2, 3, false] }],
      ['bold', 'italic', 'underline'],
      ['link', 'image'],
      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
      ['clean']
    ]
  }
});

// When Edit button is clicked
$('.editBtn').on('click', function() {
  const id = $(this).data('id');
  const title = $(this).data('title');
  const banner = $(this).data('banner');
  const json = $(this).data('json');

  $('#edit_id').val(id);
  $('#edit_title').val(title);

  if (banner && banner !== '../backend/') {
    $('#edit_banner_preview').attr('src', banner).removeClass('d-none');
  } else {
    $('#edit_banner_preview').addClass('d-none');
  }

  try {
    const blog = (typeof json === 'object') ? json : JSON.parse(json);
    let delta = blog.content?.ops ? blog.content : (blog.ops ? blog : null);
    editQuill.setContents(delta || { ops: [] });
  } catch (err) {
    console.error('Error loading blog into editor:', err);
    editQuill.setContents({ ops: [] });
  }
});

// Save JSON on submit
$('#editBlogModal form').on('submit', function(){
  const delta = editQuill.getContents();
  $('#edit_content_json').val(JSON.stringify(delta));
});


</script>


</body>
</html>
