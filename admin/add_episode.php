<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

$msg = '';
$error = '';

$categories = $pdo->query("SELECT name FROM episode_categories ORDER BY name ASC")->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title']);
  $video_link = trim($_POST['video_link']);
  $description = trim($_POST['description']);
  $category = trim($_POST['category']);
  $is_featured = isset($_POST['is_featured']) ? 1 : 0;
  $is_playlist = isset($_POST['is_playlist']) ? 1 : 0;
  $status = $_POST['status'];
  if (strpos($video_link, 'watch?v=') !== false) {
    $video_link = str_replace('watch?v=', 'embed/', $video_link);
  } elseif (strpos($video_link, 'youtu.be/') !== false) {
    preg_match("#youtu\.be/([a-zA-Z0-9_-]+)#", $video_link, $matches);
    if (isset($matches[1])) {
      $video_link = 'https://www.youtube.com/embed/' . $matches[1];
    }
  }

$thumbnail = '';
if (!empty($_FILES['thumbnail']['name'])) {
  $thumbnail = time() . '_' . basename($_FILES['thumbnail']['name']);
  $targetPath = '../assets/images/' . $thumbnail;
  move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetPath);
}

$stmt = $pdo->prepare("INSERT INTO episodes (title, video_link, description, category, is_featured, is_playlist, status, thumbnail)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$title, $video_link, $description, $category, $is_featured, $is_playlist, $status, $thumbnail]);
$msg = "✅ Episode added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Episode</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
  <?php include 'includes/admin_sidebar.php'; ?>
  <div class="container-fluid p-4 content-shift">

    <h2 class="mb-4">➕ Add New Episode</h2>
    <?php if ($msg): ?>
      <div class="alert alert-success"><?= $msg ?></div>
    <?php elseif ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="row mb-3">
        <div class="col-md-6">
          <label>Episode Title</label>
          <input type="text" name="title" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label>Category</label>
          <select name="category" class="form-select" required>
            <option value="">-- Select --</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label>YouTube/Vimeo Link</label>
        <input type="text" name="video_link" class="form-control" placeholder="e.g. https://www.youtube.com/watch?v=abc123" required>
      </div>

      <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="4" required></textarea>
      </div>
        <div class="mb-3">
  <label>Thumbnail Image (optional)</label>
  <input type="file" name="thumbnail" class="form-control">
</div>
  
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" name="is_featured" id="featuredCheck">
        <label class="form-check-label" for="featuredCheck">⭐ Mark as Featured</label>
      </div>

      <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" name="is_playlist" id="playlistCheck">
        <label class="form-check-label" for="playlistCheck">📂 Is a Playlist?</label>
      </div>
       <div class="mb-3">
  <label>Status</label>
  <select name="status" class="form-select" required>
    <option value="published" selected>Published</option>
    <option value="draft">Draft</option>
  </select>
</div>
   
      <button type="submit" class="btn btn-primary">➕ Add Episode</button>
      <a href="manage_episodes.php" class="btn btn-outline-secondary">← Back to Episodes</a>
    </form>

  </div>
</div>
</body>
</html>
