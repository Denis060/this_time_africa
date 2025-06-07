<?php
// Define BASE_URL for local or live environment
define('BASE_URL', '/THIS_TIME_AFRICA/'); // Update this for live deployment

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>This Time Africa</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom Styles -->
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">

  <!-- Favicon -->
  <link rel="icon" href="<?= BASE_URL ?>assets/images/favicon.png" type="image/png">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>index.php">This Time Africa</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage === 'about.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>pages/about.php">About</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage === 'segments.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>pages/segments.php">Segments</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage === 'episodes.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>pages/episodes.php">Episodes</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage === 'blog.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>pages/blog.php">Blog</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage === 'get_involved.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>pages/get_involved.php">Get Involved</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage === 'contact.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>pages/contact.php">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>
