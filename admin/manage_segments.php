<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

$msg = '';
$error = '';

// Add Segment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_title'], $_POST['new_description'])) {
  $title = trim($_POST['new_title']);
  $desc = trim($_POST['new_description']);
  if ($title !== '') {
    $stmt = $pdo->prepare("INSERT INTO segments (title, description) VALUES (?, ?)");
    $stmt->execute([$title, $desc]);
    $msg = "✅ Segment added.";
  } else {
    $error = "❌ Title cannot be empty.";
  }
}

// Update Segment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'], $_POST['edit_title'], $_POST['edit_description'])) {
  $id = $_POST['edit_id'];
  $title = trim($_POST['edit_title']);
  $desc = trim($_POST['edit_description']);
  $stmt = $pdo->prepare("UPDATE segments SET title = ?, description = ? WHERE id = ?");
  $stmt->execute([$title, $desc, $id]);
  $msg = "✅ Segment updated.";
}

// Delete Segment
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $stmt = $pdo->prepare("DELETE FROM segments WHERE id = ?");
  $stmt->execute([$id]);
  $msg = "🗑️ Segment deleted.";
}

// Fetch all segments
$segments = $pdo->query("SELECT * FROM segments ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Segments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
  <?php include 'includes/admin_sidebar.php'; ?>
  <div class="container-fluid p-4 content-shift" style="margin-left: 240px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">📚 Manage Segments</h2>
      <a href="dashboard.php" class="btn btn-outline-secondary">← Back to Dashboard</a>
    </div>

    <?php if ($msg): ?>
      <div class="alert alert-success"><?= $msg ?></div>
    <?php elseif ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Add Segment -->
    <form method="POST" class="row g-2 mb-4">
      <div class="col-md-4">
        <input type="text" name="new_title" class="form-control" placeholder="Segment Title" required>
      </div>
      <div class="col-md-6">
        <input type="text" name="new_description" class="form-control" placeholder="Short description" required>
      </div>
      <div class="col-md-2">
        <button class="btn btn-primary w-100"><i class="bi bi-plus-circle"></i> Add Segment</button>
      </div>
    </form>

    <!-- Search -->
    <input type="text" class="form-control mb-3" placeholder="🔍 Search..." id="segmentSearch">

    <!-- Table -->
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>Title</th>
            <th>Description</th>
            <th style="width: 180px;">Actions</th>
          </tr>
        </thead>
        <tbody id="segmentTable">
          <?php foreach ($segments as $seg): ?>
            <tr>
              <td>
                <form method="POST" class="d-flex gap-2">
                  <input type="hidden" name="edit_id" value="<?= $seg['id'] ?>">
                  <input type="text" name="edit_title" class="form-control" value="<?= htmlspecialchars($seg['title']) ?>" required>
              </td>
              <td>
                  <input type="text" name="edit_description" class="form-control" value="<?= htmlspecialchars($seg['description']) ?>" required>
              </td>
              <td class="text-end">
                  <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i> Save</button>
                  <a href="?delete=<?= $seg['id'] ?>" onclick="return confirm('Delete this segment?')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => el.remove());
  }, 3000);

  document.getElementById('segmentSearch').addEventListener('input', function () {
    const value = this.value.toLowerCase();
    document.querySelectorAll('#segmentTable tr').forEach(row => {
      row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
    });
  });
</script>
</body>
</html>
