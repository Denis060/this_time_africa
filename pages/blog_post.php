<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE slug = ?");
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) {
  echo "<div class='container mt-5'><h3>Post not found.</h3></div>";
  require_once '../includes/footer.php';
  exit;
}
?>

<section class="py-5">
  <div class="container">
    <h1 class="mb-3"><?= htmlspecialchars($post['title']) ?></h1>
    <p class="text-muted">
      Category: <strong><?= htmlspecialchars($post['category']) ?></strong> |
      Posted on <?= date('F j, Y', strtotime($post['created_at'])) ?>
    </p>

    <?php if (!empty($post['image'])): ?>
      <img src="../assets/images/<?= htmlspecialchars($post['image']) ?>" class="img-fluid mb-4" alt="Blog Image">
    <?php endif; ?>

    <div class="lead">
      <?= nl2br($post['content']) ?>
    </div>

    <a href="blog.php" class="btn btn-outline-secondary mt-4">← Back to Blog</a>
  </div>
</section>

<?php require_once '../includes/footer.php'; ?>
