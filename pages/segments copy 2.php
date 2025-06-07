<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

$search = $_GET['search'] ?? '';

$query = "SELECT * FROM segments";
$params = [];

if ($search) {
  $query .= " WHERE title LIKE ? OR description LIKE ?";
  $params = ["%$search%", "%$search%"];
}
$query .= " ORDER BY id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$segments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Our Segments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
/* === SEGMENT === */

    .segment-card {
      border-left: 4px solid #0d6efd;
      background: #f8f9fa;
      padding: 1rem;
      margin-bottom: 1rem;
      border-radius: .5rem;
      display: flex;
      align-items: center;
      transition: all 0.2s ease-in-out;
    }
    .segment-card:hover {
      background-color: #e2e6ea;
      text-decoration: none;
    }
    .segment-card img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      margin-right: 15px;
      border-radius: 6px;
    }
    .segment-title {
      font-size: 1.2rem;
      font-weight: bold;
    }
  </style>
</head>
<body>
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">🌍 Explore Our Segments</h2>
    <form method="GET" class="d-flex" role="search">
      <input class="form-control me-2" type="search" name="search" placeholder="Search segments..." value="<?= htmlspecialchars($search) ?>">
      <button class="btn btn-outline-primary" type="submit">Search</button>
    </form>
  </div>

  <?php if (count($segments) > 0): ?>
    <div class="row">
      <?php foreach ($segments as $seg): ?>
        <div class="col-md-6">
          <a href="segment_detail.php?id=<?= $seg['id'] ?>" class="segment-card shadow-sm text-dark text-decoration-none">
            <?php if (!empty($seg['image'])): ?>
              <img src="../assets/images/<?= htmlspecialchars($seg['image']) ?>" alt="<?= htmlspecialchars($seg['title']) ?>">
            <?php else: ?>
              <img src="../assets/images/default_icon.png" alt="Segment Icon">
            <?php endif; ?>
            <div>
              <div class="segment-title"><?= htmlspecialchars($seg['title']) ?></div>
              <p class="mb-0 small text-muted"><?= htmlspecialchars($seg['description']) ?></p>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="text-muted text-center">No segments found.</p>
  <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
</body>
</html>
