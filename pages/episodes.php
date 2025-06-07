<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

// Get list of categories
$categoryList = $pdo->query("SELECT DISTINCT category FROM episodes WHERE category IS NOT NULL ORDER BY category ASC")->fetchAll(PDO::FETCH_COLUMN);

// Handle filter
$selectedCategory = $_GET['category'] ?? '';
$categoryCondition = '';
$params = [];

if (!empty($selectedCategory)) {
  $categoryCondition = "AND category = ?";
  $params[] = $selectedCategory;
}

// Featured episode (does not apply filter)
$featured = $pdo->query("SELECT * FROM episodes WHERE is_featured = 1 ORDER BY created_at DESC LIMIT 1")->fetch();

// Pagination setup
$limit = 4;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Count filtered episodes
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM episodes WHERE is_featured = 0 $categoryCondition");
$countStmt->execute($params);
$total = $countStmt->fetchColumn();
$totalPages = ceil($total / $limit);

// Fetch filtered episodes
$query = "SELECT * FROM episodes WHERE is_featured = 0 $categoryCondition ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$episodes = $stmt->fetchAll();
?>

<section class="py-5 bg-light">
  <div class="container">
    <h1 class="text-center mb-4">Watch Our Latest Episodes</h1>

    <!-- Filter -->
    <form method="get" class="mb-5">
      <div class="row justify-content-center">
        <div class="col-md-4">
          <select name="category" class="form-select" onchange="this.form.submit()">
            <option value="">🎯 All Categories</option>
            <?php foreach ($categoryList as $cat): ?>
              <option value="<?= htmlspecialchars($cat) ?>" <?= $selectedCategory === $cat ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </form>

    <!-- Featured -->
    <?php if ($featured): ?>
      <div class="mb-5">
        <h4 class="text-center text-primary">🌟 Featured Episode</h4>
        <div class="card mb-4 shadow-sm">
          <div class="ratio ratio-16x9">
            <iframe src="<?= htmlspecialchars($featured['video_link']) ?>" frameborder="0" allowfullscreen></iframe>
          </div>
          <div class="card-body">
            <h5><?= htmlspecialchars($featured['title']) ?></h5>
            <p class="text-muted"><?= date('F j, Y', strtotime($featured['created_at'])) ?> | <?= htmlspecialchars($featured['category']) ?></p>
            <p><?= nl2br(strip_tags($featured['description'])) ?></p>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Episodes -->
    <div class="row g-4">
      <?php if (count($episodes) === 0): ?>
        <div class="col-12 text-center">
          <div class="alert alert-info">No episodes found for this category.</div>
        </div>
      <?php endif; ?>

      <?php foreach ($episodes as $ep): ?>
        <div class="col-md-6">
          <div class="card h-100 shadow-sm">
            <div class="ratio ratio-16x9">
              <iframe src="<?= htmlspecialchars($ep['video_link']) ?>" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($ep['title']) ?></h5>
              <p class="text-muted"><?= date('F j, Y', strtotime($ep['created_at'])) ?> | <?= htmlspecialchars($ep['category']) ?></p>
              <p class="card-text"><?= substr(strip_tags($ep['description']), 0, 120) ?>...</p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
      <nav class="mt-5">
        <ul class="pagination justify-content-center">
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $i ?>&category=<?= urlencode($selectedCategory) ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
    <?php endif; ?>

    <!-- Subscribe -->
    <div class="text-center mt-5">
      <h4>📩 Subscribe to Get Episode Updates</h4>
      <form action="https://formspree.io/f/your-id" method="POST" class="row justify-content-center g-2 mt-3">
        <div class="col-md-4">
          <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-outline-dark w-100">Subscribe</button>
        </div>
      </form>
    </div>
  </div>
</section>

<?php require_once '../includes/footer.php'; ?>
