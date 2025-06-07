<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

$msg = '';
$error = '';

// Fetch existing entry
$stmt = $pdo->query("SELECT * FROM about_show ORDER BY created_at DESC LIMIT 1");
$about = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title']);
  $vision = trim($_POST['vision']);
  $bio = trim($_POST['bio']);
  $importance = trim($_POST['importance']);
  $quote = trim($_POST['quote']);
  $quote_author = trim($_POST['quote_author']);
  $imageName = $about['image'] ?? '';

  // Handle image upload
  if (!empty($_FILES['image']['name'])) {
    $imageName = time() . '_' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/' . $imageName);
  }

  if ($about) {
    // Update
    $stmt = $pdo->prepare("UPDATE about_show SET title=?, vision=?, bio=?, importance=?, quote=?, quote_author=?, image=? WHERE id=?");
    $stmt->execute([$title, $vision, $bio, $importance, $quote, $quote_author, $imageName, $about['id']]);
    $msg = "✅ About section updated successfully.";
  } else {
    // Insert
    $stmt = $pdo->prepare("INSERT INTO about_show (title, vision, bio, importance, quote, quote_author, image)
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $vision, $bio, $importance, $quote, $quote_author, $imageName]);
    $msg = "✅ About section created.";
  }

  // Refresh
  header("Location: manage_about.php?updated=1");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage About the Show</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
  <?php include 'includes/admin_sidebar.php'; ?>
  <div class="container-fluid p-4 content-shift">
    <h2 class="mb-4">🎙️ Manage "About the Show"</h2>

    <?php if ($msg): ?>
      <div class="alert alert-success"><?= $msg ?></div>
    <?php elseif (isset($_GET['updated'])): ?>
      <div class="alert alert-success">✅ Content saved successfully.</div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($about['title'] ?? '') ?>" required>
      </div>

      <div class="mb-3">
        <label>Vision / Mission</label>
        <textarea name="vision" class="form-control" rows="4" required><?= htmlspecialchars($about['vision'] ?? '') ?></textarea>
      </div>

      <div class="mb-3">
        <label>Bio of Dr. Matilda Banga</label>
        <textarea name="bio" class="form-control" rows="4" required><?= htmlspecialchars($about['bio'] ?? '') ?></textarea>
      </div>

      <div class="mb-3">
        <label>Why this show matters now</label>
        <textarea name="importance" class="form-control" rows="4" required><?= htmlspecialchars($about['importance'] ?? '') ?></textarea>
      </div>

      <div class="mb-3">
        <label>Quote</label>
        <input type="text" name="quote" class="form-control" value="<?= htmlspecialchars($about['quote'] ?? '') ?>">
      </div>

      <div class="mb-3">
        <label>Quote Author</label>
        <input type="text" name="quote_author" class="form-control" value="<?= htmlspecialchars($about['quote_author'] ?? '') ?>">
      </div>

      <div class="mb-3">
        <label>Image</label>
        <input type="file" name="image" class="form-control">
        <?php if (!empty($about['image'])): ?>
          <div class="mt-2">
            <img src="../assets/images/<?= htmlspecialchars($about['image']) ?>" width="150">
          </div>
        <?php endif; ?>
      </div>

      <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Save</button>
    </form>
  </div>
</div>
</body>
</html>
