<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Fetch existing categories
$catStmt = $pdo->query("SELECT DISTINCT category FROM blog_posts WHERE category IS NOT NULL AND category != ''");
$categories = $catStmt->fetchAll(PDO::FETCH_COLUMN);

// Handle new post
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title']);
  $slug = strtolower(str_replace(' ', '-', $title));
  $category = $_POST['category'] === '__custom__' ? trim($_POST['category_custom']) : $_POST['category'];
  $content = trim($_POST['content']);
  $status = $_POST['status'] ?? 'draft';

  $imageName = '';
  if (!empty($_FILES['image']['name'])) {
    $imageName = time() . '_' . basename($_FILES['image']['name']);
    $targetPath = '../assets/images/' . $imageName;
    move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
  }

  $stmt = $pdo->prepare("INSERT INTO blog_posts (title, slug, category, content, image, status) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->execute([$title, $slug, $category, $content, $imageName, $status]);
  $msg = "✅ Blog post added!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Blog Post</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
  <?php include 'includes/admin_sidebar.php'; ?>
  <div class="flex-grow-1 p-4" style="margin-left: 240px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">➕ Add Blog Post</h2>
      <a href="manage_blog.php" class="btn btn-outline-secondary">← Back to Blog Manager</a>
    </div>

    <?php if ($msg): ?>
      <div class="alert alert-success"><?= $msg ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Category</label>
        <select name="category" class="form-select" onchange="toggleCustomCategory(this)">
          <option value="">-- Choose Category --</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
          <?php endforeach; ?>
          <option value="__custom__">➕ Add New Category</option>
        </select>
        <input type="text" name="category_custom" class="form-control mt-2" placeholder="Enter new category" id="customCategoryInput" style="display:none;">
      </div>

      <div class="mb-3">
        <label>Content</label>
        <textarea name="content" class="form-control" rows="6" required></textarea>
      </div>

      <div class="mb-3">
        <label>Image</label>
        <input type="file" name="image" class="form-control">
      </div>

      <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-select">
          <option value="published">Published</option>
          <option value="draft" selected>Draft</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Post</button>
    </form>
  </div>
</div>

<script>
  function toggleCustomCategory(select) {
    const input = document.getElementById('customCategoryInput');
    input.style.display = select.value === '__custom__' ? 'block' : 'none';
  }
</script>
</body>
</html>
