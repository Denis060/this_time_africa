<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

$msg = '';
$error = '';

// Add new category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_category'])) {
  $name = trim($_POST['new_category']);
  if ($name !== '') {
    try {
      $stmt = $pdo->prepare("INSERT INTO episode_categories (name) VALUES (?)");
      $stmt->execute([$name]);
      $msg = "✅ Category added.";
    } catch (PDOException $e) {
      $error = "❌ Category already exists.";
    }
  } else {
    $error = "❌ Category name cannot be empty.";
  }
}

// Update category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'], $_POST['edit_name'])) {
  $id = $_POST['edit_id'];
  $name = trim($_POST['edit_name']);
  if ($name !== '') {
    try {
      $stmt = $pdo->prepare("UPDATE episode_categories SET name = ? WHERE id = ?");
      $stmt->execute([$name, $id]);
      $msg = "✅ Category updated.";
    } catch (PDOException $e) {
      $error = "❌ Name already exists.";
    }
  } else {
    $error = "❌ Category name cannot be blank.";
  }
}

// Delete category
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $stmt = $pdo->prepare("SELECT COUNT(*) FROM episodes WHERE category = (SELECT name FROM episode_categories WHERE id = ?)");
  $stmt->execute([$id]);
  $used = $stmt->fetchColumn();
  if ($used > 0) {
    $error = "⚠️ Cannot delete a category in use.";
  } else {
    $stmt = $pdo->prepare("DELETE FROM episode_categories WHERE id = ?");
    $stmt->execute([$id]);
    $msg = "🗑️ Category deleted.";
  }
}

// Fetch categories
$categories = $pdo->query("SELECT * FROM episode_categories ORDER BY name ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Categories</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    form.inline-edit {
      display: flex;
      gap: 0.5rem;
    }
    form.inline-edit input {
      flex: 1;
    }
    form.inline-edit button {
      white-space: nowrap;
    }
  </style>
</head>
<body>
<div class="d-flex">
  <?php include 'includes/admin_sidebar.php'; ?>
  <div class="container-fluid p-4" style="margin-left: 240px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Manage Episode Categories</h2>
      <a href="dashboard.php" class="btn btn-outline-secondary">
        ← Back to Dashboard
      </a>
    </div>

    <?php if ($msg): ?>
      <div class="alert alert-success"><?= $msg ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Search Bar -->
    <div class="row mb-3 justify-content-end">
      <div class="col-md-4">
        <input type="text" id="categorySearch" class="form-control" placeholder="🔍 Search categories...">
      </div>
    </div>

    <!-- Add Category -->
    <form method="POST" class="row g-2 mb-4">
      <div class="col-md-6">
        <input type="text" name="new_category" class="form-control" placeholder="New category..." required autofocus>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Add</button>
      </div>
    </form>

    <!-- Categories Table -->
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Name</th>
          <th style="width: 180px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($categories as $cat): ?>
          <tr>
            <td>
              <form method="POST" class="inline-edit">
                <input type="hidden" name="edit_id" value="<?= $cat['id'] ?>">
                <input type="text" name="edit_name" class="form-control" value="<?= htmlspecialchars($cat['name']) ?>" required>
                <button class="btn btn-sm btn-success" title="Save">
                  <i class="bi bi-check-lg"></i>
                </button>
              </form>
            </td>
            <td>
              <a href="?delete=<?= $cat['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">
                <i class="bi bi-trash"></i> Delete
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Scripts -->
<script>
  setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => el.remove());
  }, 3000);

  document.getElementById('categorySearch').addEventListener('input', function () {
    const filter = this.value.toLowerCase();
    document.querySelectorAll("table tbody tr").forEach(row => {
      const text = row.querySelector("input[name='edit_name']").value.toLowerCase();
      row.style.display = text.includes(filter) ? "" : "none";
    });
  });
</script>
</body>
</html>
