<?php
require_once '../includes/db.php';
require_once '../includes/header.php'; // Optional header include

$segments = $pdo->query("SELECT * FROM segments ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Our Segments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .segment-card {
      border-left: 5px solid #0d6efd;
      padding: 1rem;
      background: #f8f9fa;
      margin-bottom: 1rem;
      border-radius: .25rem;
    }
  </style>
</head>
<body>
<div class="container py-5">
  <h2 class="text-center mb-5">🌍 Explore Our Segments</h2>

  <?php if (count($segments) > 0): ?>
    <div class="row">
      <?php foreach ($segments as $seg): ?>
        <div class="col-md-6">
          <div class="segment-card shadow-sm">
            <h5 class="fw-bold"><?= htmlspecialchars($seg['title']) ?></h5>
            <p class="mb-0"><?= nl2br(htmlspecialchars($seg['description'])) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="text-muted text-center">No segments added yet.</p>
  <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
</body>
</html>
