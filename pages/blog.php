<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

// Get distinct categories
$categories = $pdo->query("SELECT DISTINCT category FROM blog_posts WHERE category IS NOT NULL AND category != ''")->fetchAll(PDO::FETCH_COLUMN);

// Pagination setup
$limit = 6;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Filters
$selectedCategory = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

$conditions = [];
$params = [];

// Category condition
if (!empty($selectedCategory)) {
  $conditions[] = "category = ?";
  $params[] = $selectedCategory;
}

// Search condition
if (!empty($search)) {
  $conditions[] = "(title LIKE ? OR content LIKE ?)";
  $params[] = "%$search%";
  $params[] = "%$search%";
}

$whereSQL = count($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

// Total post count for pagination
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM blog_posts $whereSQL");
$countStmt->execute($params);
$totalPosts = $countStmt->fetchColumn();
$totalPages = ceil($totalPosts / $limit);

// Fetch paginated results
$query = "SELECT * FROM blog_posts $whereSQL ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$posts = $stmt->fetchAll();
?>
<main>

<section class="py-5 bg-light">
  <div class="container">
    <h1 class="mb-4 text-center">Latest Blog Articles</h1>

    <!-- Search + Filter Form -->
    <form method="get" class="mb-4">
      <div class="row justify-content-center g-2">
        <div class="col-md-4">
          <select name="category" class="form-select">
            <option value="">-- All Categories --</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= htmlspecialchars($cat) ?>" <?= $cat == $selectedCategory ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <input type="text" name="search" class="form-control" placeholder="Search by title or content..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-dark w-100">Filter</button>
        </div>
      </div>
    </form>

    <!-- Blog Cards -->
    <div class="row g-4">
      <?php if (count($posts) === 0): ?>
        <div class="col-12">
          <div class="alert alert-info text-center">No blog posts found.</div>
        </div>
      <?php endif; ?>

      <?php foreach ($posts as $post): ?>
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <?php if (!empty($post['image'])): ?>
              <img src="../assets/images/<?= htmlspecialchars($post['image']) ?>" class="card-img-top" alt="Blog Image">
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
              <p class="text-muted mb-1">
                <?= htmlspecialchars($post['category']) ?>
                <?php if (!empty($post['author'])): ?>
                  • By <?= htmlspecialchars($post['author']) ?>
                <?php endif; ?>
                • <?= date('M d, Y', strtotime($post['created_at'])) ?>
              </p>
              <p class="card-text">
                <?= substr(strip_tags($post['content']), 0, 100) ?>...
              </p>
              <a href="blog_post.php?slug=<?= urlencode($post['slug']) ?>" class="btn btn-sm btn-outline-primary">Read More</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
      <nav>
        <ul class="pagination justify-content-center mt-4">
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
              <a class="page-link"
                 href="?page=<?= $i ?>&category=<?= urlencode($selectedCategory) ?>&search=<?= urlencode($search) ?>">
                 <?= $i ?>
              </a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
    <?php endif; ?>
  </div>
</section>
</main>


<?php require_once '../includes/footer.php'; ?>
