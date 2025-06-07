<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

$msg = '';

// Handle delete
if (isset($_GET['delete'])) {
  $stmt = $pdo->prepare("DELETE FROM episodes WHERE id = ?");
  $stmt->execute([$_GET['delete']]);
  header("Location: manage_episodes.php");
  exit;
}

$search = $_GET['search'] ?? '';
$limit = 5;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$where = '';
$params = [];

if ($search) {
  $where = "WHERE title LIKE ? OR category LIKE ?";
  $params = ["%$search%", "%$search%"];
}

// Count total for pagination
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM episodes $where");
$countStmt->execute($params);
$totalEpisodes = $countStmt->fetchColumn();
$totalPages = ceil($totalEpisodes / $limit);

// Fetch paginated episodes
$query = "SELECT * FROM episodes $where ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$episodes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Episodes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
  <?php include 'includes/admin_sidebar.php'; ?>
  <div class="container-fluid p-4 content-shift">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>🎞 Manage Episodes</h2>
      <a href="add_episode.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Episode</a>
    </div>

    <!-- Search Bar -->
    <form class="mb-4 row g-2">
      <div class="col-md-6">
        <input type="text" name="search" class="form-control" placeholder="🔍 Search title or category" value="<?= htmlspecialchars($search) ?>">
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-dark w-100">Search</button>
      </div>
    </form>

    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title mb-3">All Episodes</h5>
        <div class="table-responsive">
          <table class="table table-bordered align-middle table-hover">
            <thead class="table-light">
              <tr>
                <th>Title</th>
                <th>Video</th>
                <th>Category</th>
                <th>Type</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($episodes as $ep): ?>
                <?php
                  $videoLink = $ep['video_link'];
                  if (strpos($videoLink, 'enablejsapi=1') === false) {
                    $videoLink .= (strpos($videoLink, '?') !== false ? '&' : '?') . 'enablejsapi=1';
                  }
                ?>
                <tr>
                  <td><?= htmlspecialchars($ep['title']) ?></td>
                  <td>
                    <div class="ratio ratio-16x9">
                      <iframe class="episode-video" src="<?= htmlspecialchars($videoLink) ?>" allowfullscreen></iframe>
                    </div>
                  </td>
                  <td><?= htmlspecialchars($ep['category']) ?></td>
                  <td>
                    <?= $ep['is_playlist'] ? '📂 Playlist' : '🎬 Single' ?>
                    <?= $ep['is_featured'] ? ' | ⭐ Featured' : '' ?>
                  </td>
                  <td>
                    <span class="badge bg-<?= $ep['status'] === 'published' ? 'success' : 'secondary' ?>">
                      <?= ucfirst($ep['status']) ?>
                    </span>
                  </td>
                  <td><?= date('M d, Y', strtotime($ep['created_at'])) ?></td>
                  <td>
                    <a href="edit_episode.php?id=<?= $ep['id'] ?>" class="btn btn-sm btn-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                    <a href="?delete=<?= $ep['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this episode?')" title="Delete"><i class="bi bi-trash"></i></a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
          <nav class="mt-4">
            <ul class="pagination justify-content-center">
              <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                  <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>
            </ul>
          </nav>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript: Only one video plays at a time -->
<script>
  const videos = document.querySelectorAll('.episode-video');
  videos.forEach((iframe, index) => {
    iframe.addEventListener('mouseenter', () => {
      videos.forEach((other, i) => {
        if (i !== index) {
          other.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
        }
      });
    });
  });
</script>
</body>
</html>
