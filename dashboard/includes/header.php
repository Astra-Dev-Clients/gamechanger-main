<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>GameChanger Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <!-- Custom -->
  <style>
    body {
      background-color: #f8f9fc;
      font-family: 'Poppins', sans-serif;
    }
    .sidebar {
      width: 250px;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      background-color: #072F5F;
      color: white;
      transition: all 0.3s ease;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 15px 20px;
      border-left: 3px solid transparent;
    }
    .sidebar a:hover {
      background-color: #0A3C78;
      border-left: 3px solid #FF7A00;
    }
    .sidebar .active {
      background-color: #0A3C78;
      border-left: 3px solid #FF7A00;
    }
  </style>
</head>
<body>
