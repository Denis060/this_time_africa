<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Fetch counts
$totalEpisodes = $pdo->query("SELECT COUNT(*) FROM episodes")->fetchColumn();
$totalCategories = $pdo->query("SELECT COUNT(*) FROM episode_categories")->fetchColumn();
$totalPosts = $pdo->query("SELECT COUNT(*) FROM blog_posts")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
  <?php include 'includes/admin_sidebar.php'; ?>
  <div class="container-fluid p-4 content-shift">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">🎬 Admin Dashboard</h2>
      <span class="text-muted">Welcome back!</span>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-5">
      <div class="col-md-4">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <h5 class="card-title"><i class="bi bi-film"></i> Total Episodes</h5>
            <p class="fs-3 fw-bold"><?= $totalEpisodes ?></p>
            <a href="manage_episodes.php" class="btn btn-outline-primary btn-sm">Manage Episodes</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <h5 class="card-title"><i class="bi bi-folder"></i> Categories</h5>
            <p class="fs-3 fw-bold"><?= $totalCategories ?></p>
            <a href="manage_categories.php" class="btn btn-outline-primary btn-sm">Manage Categories</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <h5 class="card-title"><i class="bi bi-journal-text"></i> Blog Posts</h5>
            <p class="fs-3 fw-bold"><?= $totalPosts ?></p>
            <a href="manage_blog.php" class="btn btn-outline-primary btn-sm">Manage Blog</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
      <div class="col-md-6 mb-3">
        <a href="manage_episodes.php" class="btn btn-lg btn-primary w-100"><i class="bi bi-plus-circle"></i> Add New Episode</a>
      </div>
      <div class="col-md-6 mb-3">
        <a href="manage_blog.php" class="btn btn-lg btn-secondary w-100"><i class="bi bi-pencil-square"></i> Add New Blog Post</a>
      </div>
    </div>

  </div>
</div>
</body>
</html>
