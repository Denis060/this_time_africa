<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  die("❌ Invalid segment.");
}

$segmentId = (int)$_GET['id'];

// Fetch segment
$stmt = $pdo->prepare("SELECT * FROM segments WHERE id = ?");
$stmt->execute([$segmentId]);
$segment = $stmt->fetch();

if (!$segment) {
  die("❌ Segment not found.");
}

// Fetch episodes and blogs
$episodes = $pdo->prepare("SELECT * FROM episodes WHERE segment_id = ? AND status = 'published' ORDER BY created_at DESC");
$episodes->execute([$segmentId]);
$episodes = $episodes->fetchAll();

$blogs = $pdo->prepare("SELECT * FROM blog_posts WHERE segment_id = ? AND status = 'published' ORDER BY created_at DESC");
$blogs->execute([$segmentId]);
$blogs = $blogs->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($segment['title']) ?> – Segment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">

  <!-- Sponsor Message -->
  <?php if (!empty($segment['sponsor_name'])): ?>
    <div class="alert alert-warning text-center mb-4">
      <?php if (!empty($segment['sponsor_logo'])): ?>
        <img src="../assets/sponsors/<?= htmlspecialchars($segment['sponsor_logo']) ?>" alt="Sponsor Logo" height="50" class="mb-2">
      <?php endif; ?>
      <p class="mb-1">This segment is brought to you by <strong><?= htmlspecialchars($segment['sponsor_name']) ?></strong>.</p>
      <?php if (!empty($segment['sponsor_url'])): ?>
        <a href="<?= htmlspecialchars($segment['sponsor_url']) ?>" class="btn btn-sm btn-dark" target="_blank">Visit Sponsor</a>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <!-- Segment Info -->
  <div class="text-center mb-5">
    <?php if (!empty($segment['image'])): ?>
      <img src="../assets/images/<?= htmlspecialchars($segment['image']) ?>" class="mb-3 img-fluid" style="max-height: 150px;" alt="Segment Icon">
    <?php endif; ?>
    <h1 class="fw-bold"><?= htmlspecialchars($segment['title']) ?></h1>
    <p class="lead"><?= nl2br(htmlspecialchars($segment['description'])) ?></p>
  </div>

  <!-- Related Episodes -->
  <h4 class="mb-3">🎬 Related Episodes</h4>
  <?php if ($episodes): ?>
    <div class="row g-4 mb-5">
      <?php foreach ($episodes as $ep): ?>
        <div class="col-md-6">
          <div class="card h-100 shadow-sm">
            <div class="ratio ratio-16x9">
              <iframe src="<?= htmlspecialchars($ep['video_link']) ?>" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($ep['title']) ?></h5>
              <p class="card-text"><?= substr(strip_tags($ep['description']), 0, 100) ?>...</p>
              <a href="../pages/episodes.php" class="btn btn-outline-primary btn-sm">More Episodes</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="text-muted">No episodes found for this segment.</p>
  <?php endif; ?>

  <!-- Related Blog Posts -->
  <h4 class="mb-3">📰 Related Blog Posts</h4>
  <?php if ($blogs): ?>
    <div class="row g-4">
      <?php foreach ($blogs as $post): ?>
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <?php if (!empty($post['image'])): ?>
              <img src="../assets/images/<?= htmlspecialchars($post['image']) ?>" class="card-img-top" alt="Blog Image">
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
              <p class="text-muted small"><?= date('M d, Y', strtotime($post['created_at'])) ?></p>
              <p><?= substr(strip_tags($post['content']), 0, 90) ?>...</p>
              <a href="blog_post.php?slug=<?= urlencode($post['slug']) ?>" class="btn btn-sm btn-outline-dark">Read More</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="text-muted">No blog posts found for this segment.</p>
  <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
</body>
</html>
