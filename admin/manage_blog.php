<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'desc';

// Build search condition
$where = '';
$params = [];
if ($search) {
  $where = "WHERE title LIKE ? OR category LIKE ?";
  $params = ["%$search%", "%$search%"];
}

// Pagination setup
$limit = 5;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM blog_posts $where");
$countStmt->execute($params);
$totalPosts = $countStmt->fetchColumn();
$totalPages = ceil($totalPosts / $limit);

// Fetch paginated blog posts
$query = "SELECT * FROM blog_posts $where ORDER BY created_at $sort LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$posts = $stmt->fetchAll();

// Fetch categories from episode_categories table
$catStmt = $pdo->query("SELECT name FROM episode_categories ORDER BY name ASC");
$categories = $catStmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Blog Posts</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
  <?php include 'includes/admin_sidebar.php'; ?>
  <div class="flex-grow-1 p-4 content-shift" style="margin-left: 240px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">📝 Manage Blog Posts</h2>
      <a href="dashboard.php" class="btn btn-outline-secondary">← Back to Dashboard</a>
    </div>

    <a href="add_blog.php" class="btn btn-primary mb-3"><i class="bi bi-plus-circle"></i> Add New Blog Post</a>

    <!-- Search & Sort -->
    <form class="row mb-3 g-2">
      <div class="col-md-6">
        <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($search) ?>" placeholder="🔍 Search title or category">
      </div>
      <div class="col-md-3">
        <select name="sort" class="form-select" onchange="this.form.submit()">
          <option value="desc" <?= $sort == 'desc' ? 'selected' : '' ?>>Newest First</option>
          <option value="asc" <?= $sort == 'asc' ? 'selected' : '' ?>>Oldest First</option>
        </select>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-dark w-100">Filter</button>
      </div>
    </form>

    <!-- Blog Posts Table -->
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title">Existing Blog Posts</h5>
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Image</th>
              <th>Title</th>
              <th>Category</th>
              <th>Status</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($posts as $post): ?>
              <tr>
                <td>
                  <?php if (!empty($post['image'])): ?>
                    <img src="../assets/images/<?= htmlspecialchars($post['image']) ?>" alt="thumb" width="60">
                  <?php else: ?>
                    —
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($post['title']) ?></td>
                <td><?= htmlspecialchars($post['category']) ?></td>
                <td>
                  <span class="badge bg-<?= $post['status'] === 'published' ? 'success' : 'secondary' ?>">
                    <?= ucfirst($post['status']) ?>
                  </span>
                </td>
                <td><?= date('M d, Y', strtotime($post['created_at'])) ?></td>
                <td>
                  <a href="edit_blog.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-secondary"><i class="bi bi-pencil"></i></a>
                  <a href="delete_blog.php?id=<?= $post['id'] ?>" onclick="return confirm('Delete this post?')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
          <nav class="mt-4">
            <ul class="pagination justify-content-center">
              <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                  <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&sort=<?= $sort ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>
            </ul>
          </nav>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>
</body>
</html>
