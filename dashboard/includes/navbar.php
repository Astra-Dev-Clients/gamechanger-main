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
          <a class="nav-link text-white" href="appointments.php">
            <span class="nav-icon-wrapper"><i class="bi bi-ticket nav-icon"></i></span> Appointments
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="users.php">
            <span class="nav-icon-wrapper"><i class="bi bi-people  nav-icon"></i></span> Users
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="blog.php">
            <span class="nav-icon-wrapper"><i class="bi bi-megaphone nav-icon"></i></span> Blog
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="notifications.php">
            <span class="nav-icon-wrapper"><i class="bi bi-bell nav-icon"></i></span> Notifications
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
    background:1px solid #fff;
  }
  </style>