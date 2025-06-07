<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Validate episode ID
if (!isset($_GET['id'])) {
  header("Location: manage_episodes.php");
  exit;
}

$id = (int) $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM episodes WHERE id = ?");
$stmt->execute([$id]);
$episode = $stmt->fetch();

if (!$episode) {
  die("Episode not found.");
}

// Fetch categories
$categories = $pdo->query("SELECT name FROM episode_categories ORDER BY name ASC")->fetchAll(PDO::FETCH_COLUMN);

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title']);
  $video_link = trim($_POST['video_link']);
  $description = trim($_POST['description']);
  $category = trim($_POST['category']);
  $is_featured = isset($_POST['is_featured']) ? 1 : 0;
  $is_playlist = isset($_POST['is_playlist']) ? 1 : 0;
  $status = $_POST['status'];
  $thumbnail = $episode['thumbnail'];
  // Convert YouTube/Vimeo formats
  if (strpos($video_link, 'watch?v=') !== false) {
    $video_link = str_replace('watch?v=', 'embed/', $video_link);
  } elseif (strpos($video_link, 'youtu.be/') !== false) {
    preg_match("#youtu\.be/([a-zA-Z0-9_-]+)#", $video_link, $matches);
    if (isset($matches[1])) {
      $video_link = 'https://www.youtube.com/embed/' . $matches[1];
    }
  }
$thumbnail = $episode['thumbnail'];
if (!empty($_FILES['thumbnail']['name'])) {
  $thumbnail = time() . '_' . basename($_FILES['thumbnail']['name']);
  move_uploaded_file($_FILES['thumbnail']['tmp_name'], '../assets/images/' . $thumbnail);
}

  // Update episode
  $stmt = $pdo->prepare("UPDATE episodes SET title = ?, video_link = ?, description = ?, category = ?, is_featured = ?, is_playlist = ?, status = ?, thumbnail = ? WHERE id = ?");
$stmt->execute([$title, $video_link, $description, $category, $is_featured, $is_playlist, $status, $thumbnail, $id]);

  header("Location: manage_episodes.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Episode</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
  <?php include 'includes/admin_sidebar.php'; ?>
  <div class="container-fluid p-4 content-shift">

    <h2 class="mb-4">✏️ Edit Episode</h2>

    <form method="POST">
      <div class="row mb-3">
        <div class="col-md-6">
          <label>Episode Title</label>
          <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($episode['title']) ?>" required>
        </div>
        <div class="col-md-6">
          <label>Category</label>
          <select name="category" class="form-select" required>
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= htmlspecialchars($cat) ?>" <?= $cat == $episode['category'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label>Video Link (YouTube/Vimeo)</label>
        <input type="text" name="video_link" class="form-control" value="<?= htmlspecialchars($episode['video_link']) ?>" required>
      </div>

      <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="5" required><?= htmlspecialchars($episode['description']) ?></textarea>
      </div>
        <div class="mb-3">
        <label>Change Thumbnail</label>
        <input type="file" name="thumbnail" class="form-control">
        <?php if (!empty($episode['thumbnail'])): ?>
          <div class="mt-2">
            <small>Current:</small><br>
            <img src="../assets/images/<?= htmlspecialchars($episode['thumbnail']) ?>" width="100">
          </div>
        <?php endif; ?>
      </div>
    
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" name="is_featured" id="featuredCheck" <?= $episode['is_featured'] ? 'checked' : '' ?>>
        <label class="form-check-label" for="featuredCheck">⭐ Mark as Featured</label>
      </div>
        <div class="mb-3">
      <label>Status</label>
      <select name="status" class="form-select">
        <option value="published" <?= $episode['status'] === 'published' ? 'selected' : '' ?>>Published</option>
        <option value="draft" <?= $episode['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
      </select>
    </div>

      <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" name="is_playlist" id="playlistCheck" <?= $episode['is_playlist'] ? 'checked' : '' ?>>
        <label class="form-check-label" for="playlistCheck">📂 Is a Playlist?</label>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Update Episode</button>
        <a href="manage_episodes.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancel</a>
      </div>
    </form>

  </div>
</div>
</body>
</html>
