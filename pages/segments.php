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
    .segment-card {
      display: flex;
      align-items: flex-start;
      background: #f9f9f9;
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 1.5rem;
      transition: box-shadow 0.2s ease;
    }

    .segment-card:hover {
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .segment-card img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      margin-right: 1rem;
      border-radius: 8px;
      flex-shrink: 0;
    }

    .segment-title {
      font-weight: 600;
      font-size: 1.1rem;
      margin-bottom: 0.25rem;
    }

    @media (max-width: 576px) {
      .segment-card {
        flex-direction: column;
        align-items: center;
        text-align: center;
      }

      .segment-card img {
        margin: 0 0 0.75rem 0;
      }
    }
  </style>
</head>
<body>
<div class="container py-5">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
    <h2 class="mb-0">🌍 Explore Our Segments</h2>
    <form method="GET" class="d-flex w-100 w-md-auto" role="search">
      <input class="form-control me-2" type="search" name="search" placeholder="Search segments..." value="<?= htmlspecialchars($search) ?>">
      <button class="btn btn-outline-primary" type="submit">Search</button>
    </form>
  </div>

  <?php if (count($segments) > 0): ?>
    <div class="row">
      <?php foreach ($segments as $seg): ?>
        <div class="col-md-6">
          <a href="segment_detail.php?id=<?= $seg['id'] ?>" class="segment-card text-dark text-decoration-none shadow-sm">
            <img src="../assets/images/<?= htmlspecialchars($seg['image'] ?: 'default_icon.png') ?>" alt="<?= htmlspecialchars($seg['title']) ?>">
            <div>
              <div class="segment-title"><?= htmlspecialchars($seg['title']) ?></div>
              <p class="mb-0 text-muted small"><?= htmlspecialchars(mb_strimwidth(strip_tags($seg['description']), 0, 140, '...')) ?></p>
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
