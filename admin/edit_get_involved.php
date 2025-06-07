<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
  header("Location: manage_get_involved.php");
  exit;
}

$id = (int) $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM get_involved WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
  die("Item not found.");
}

$msg = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title']);
  $description = trim($_POST['description']);
  $link = trim($_POST['link']);
  $visibility = $_POST['visibility'] ?? 'visible';

  if ($title && $description) {
    $stmt = $pdo->prepare("UPDATE get_involved SET title = ?, description = ?, link = ?, visibility = ? WHERE id = ?");
    $stmt->execute([$title, $description, $link ?: null, $visibility, $id]);
    $msg = "✅ Item updated successfully.";
    // Refresh item
    $stmt = $pdo->prepare("SELECT * FROM get_involved WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
  } else {
    $error = "❌ Title and Description are required.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Get Involved</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
  <?php include 'includes/admin_sidebar.php'; ?>
  <div class="container-fluid p-4 content-shift">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">✏️ Edit Get Involved Item</h2>
      <a href="manage_get_involved.php" class="btn btn-outline-secondary">← Back</a>
    </div>

    <?php if ($msg): ?>
      <div class="alert alert-success"><?= $msg ?></div>
    <?php elseif ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($item['title']) ?>" required>
      </div>
      <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="5" required><?= htmlspecialchars($item['description']) ?></textarea>
      </div>
      <div class="mb-3">
        <label>Link (optional)</label>
        <input type="text" name="link" class="form-control" value="<?= htmlspecialchars($item['link']) ?>">
      </div>
      <div class="mb-3">
        <label>Visibility</label>
        <select name="visibility" class="form-select">
          <option value="visible" <?= $item['visibility'] === 'visible' ? 'selected' : '' ?>>Visible</option>
          <option value="hidden" <?= $item['visibility'] === 'hidden' ? 'selected' : '' ?>>Hidden</option>
        </select>
      </div>
      <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Update</button>
    </form>

  </div>
</div>
</body>
</html>
