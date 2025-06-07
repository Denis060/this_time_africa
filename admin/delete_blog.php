<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

if (isset($_GET['id'])) {
  $id = (int) $_GET['id'];

  // Optional: Delete image file here too if needed

  $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = ?");
  $stmt->execute([$id]);
}

header("Location: manage_blog.php");
exit;
