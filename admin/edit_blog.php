<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
  header("Location: manage_blog.php");
  exit;
}

$id = (int) $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
  die("Post not found.");
}

// Fetch categories
$catStmt = $pdo->query("SELECT name FROM episode_categories ORDER BY name ASC");
$categories = $catStmt->fetchAll(PDO::FETCH_COLUMN);

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title']);
  $category = trim($_POST['category']);
  $content = trim($_POST['content']);
  $status = $_POST['status'];

  // Image handling
  $imageName = $post['image'];
  if (!empty($_FILES['image']['name'])) {
    $imageName = time() . '_' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/' . $imageName);
  }

  // Update query
  $stmt = $pdo->prepare("UPDATE blog_posts SET title = ?, category = ?, content = ?, image = ?, status = ? WHERE id = ?");
  $stmt->execute([$title, $category, $content, $imageName, $status, $id]);

  header("Location: manage_blog.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Blog Post</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
  <?php include 'includes/admin_sidebar.php'; ?>
  <div class="container-fluid p-4" style="margin-left: 240px;">

    <h2 class="mb-4">✏️ Edit Blog Post</h2>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($post['title']) ?>" required>
      </div>

      <div class="mb-3">
        <label>Category</label>
        <select name="category" class="form-select" required>
          <option value="">-- Choose Category --</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat) ?>" <?= $post['category'] == $cat ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label>Content</label>
        <textarea name="content" class="form-control" rows="6" required><?= htmlspecialchars($post['content']) ?></textarea>
      </div>

      <div class="mb-3">
        <label>Change Image (optional)</label>
        <input type="file" name="image" class="form-control">
        <?php if (!empty($post['image'])): ?>
          <small class="text-muted">Current:</small><br>
          <img src="../assets/images/<?= htmlspecialchars($post['image']) ?>" alt="Current Image" width="120">
        <?php endif; ?>
      </div>

      <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-select">
          <option value="published" <?= $post['status'] === 'published' ? 'selected' : '' ?>>Published</option>
          <option value="draft" <?= $post['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
        </select>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Update Post</button>
        <a href="manage_blog.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancel</a>
      </div>
    </form>

  </div>
</div>
</body>
</html>
