<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

$msg = '';
$error = '';

// Add new item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
  $title = trim($_POST['title']);
  $description = trim($_POST['description']);
  $link = trim($_POST['link']);
  $visibility = $_POST['visibility'] ?? 'visible';

  if ($title && $description) {
    $stmt = $pdo->prepare("INSERT INTO get_involved (title, description, link, visibility) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $description, $link ?: null, $visibility]);
    $msg = "✅ Item added successfully.";
  } else {
    $error = "❌ Title and Description are required.";
  }
}

// Delete
if (isset($_GET['delete'])) {
  $stmt = $pdo->prepare("DELETE FROM get_involved WHERE id = ?");
  $stmt->execute([$_GET['delete']]);
  header("Location: manage_get_involved.php");
  exit;
}

// Fetch all items
$items = $pdo->query("SELECT * FROM get_involved ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Get Involved</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
  <?php include 'includes/admin_sidebar.php'; ?>
  <div class="container-fluid p-4 content-shift">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">🤝 Manage Get Involved</h2>
      <a href="dashboard.php" class="btn btn-outline-secondary">← Back to Dashboard</a>
    </div>

    <?php if ($msg): ?>
      <div class="alert alert-success"><?= $msg ?></div>
    <?php elseif ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Add Form -->
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title">➕ Add New</h5>
        <form method="POST">
          <input type="hidden" name="add" value="1">
          <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
          </div>
          <div class="mb-3">
            <label>Link (optional)</label>
            <input type="text" name="link" class="form-control">
          </div>
          <div class="mb-3">
            <label>Visibility</label>
            <select name="visibility" class="form-select">
              <option value="visible">Visible</option>
              <option value="hidden">Hidden</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add</button>
        </form>
      </div>
    </div>

    <!-- Items List -->
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">📋 Existing Entries</h5>
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-light">
            <tr>
              <th>Title</th>
              <th>Description</th>
              <th>Link</th>
              <th>Visibility</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $item): ?>
              <tr>
                <td><?= htmlspecialchars($item['title']) ?></td>
                <td><?= nl2br(htmlspecialchars($item['description'])) ?></td>
                <td><?= $item['link'] ? '<a href="' . htmlspecialchars($item['link']) . '" target="_blank">Visit</a>' : '—' ?></td>
                <td>
                  <span class="badge bg-<?= $item['visibility'] === 'visible' ? 'success' : 'secondary' ?>">
                    <?= ucfirst($item['visibility']) ?>
                  </span>
                </td>
                <td>
                  <a href="edit_get_involved.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-secondary">
                    <i class="bi bi-pencil"></i> Edit
                  </a>
                  <a href="?delete=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?')">
                    <i class="bi bi-trash"></i> Delete
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
              <tr><td colspan="5" class="text-center text-muted">No items found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
</body>
</html>
