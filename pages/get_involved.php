<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

// Fetch only visible items
$stmt = $pdo->prepare("SELECT * FROM get_involved WHERE visibility = 'visible' ORDER BY id DESC");
$stmt->execute();
$items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Get Involved</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <main>
<div class="container py-5">
  <h2 class="text-center mb-5">🤝 Get Involved</h2>

  <?php if ($items): ?>
    <?php foreach ($items as $item): ?>
      <div class="get-card shadow-sm">
        <h5 class="fw-bold"><?= htmlspecialchars($item['title']) ?></h5>
        <p><?= nl2br(htmlspecialchars($item['description'])) ?></p>
        <?php if (!empty($item['link'])): ?>
          <a href="<?= htmlspecialchars($item['link']) ?>" class="btn btn-outline-primary btn-sm">Learn More</a>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="text-center text-muted">No involvement opportunities yet.</p>
  <?php endif; ?>
</div>
</main>
<?php require_once '../includes/footer.php'; ?>
</body>
</html>
