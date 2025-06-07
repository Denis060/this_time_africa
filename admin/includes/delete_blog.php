<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

if (isset($_GET['id'])) {
  $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = ?");
  $stmt->execute([$_GET['id']]);
}

header("Location: manage_blog.php");
exit;
