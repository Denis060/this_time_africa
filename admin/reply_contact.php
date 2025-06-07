<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
  header('Location: manage_contacts.php');
  exit;
}

$id = (int) $_GET['id'];
$msg = '';
$error = '';

$stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ?");
$stmt->execute([$id]);
$message = $stmt->fetch();

if (!$message) {
  die("Message not found.");
}

// Handle reply form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $reply = trim($_POST['reply']);

  if ($reply) {
    $stmt = $pdo->prepare("UPDATE contact_messages SET reply_message = ?, replied_at = NOW(), is_read = 1 WHERE id = ?");
    $stmt->execute([$reply, $id]);
    $msg = "✅ Reply saved. You may copy it and send via email.";
  } else {
    $error = "❌ Reply cannot be empty.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reply to Message</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
  <?php include 'includes/admin_sidebar.php'; ?>
  <div class="container-fluid p-4 content-shift">

    <h2 class="mb-4">📩 Reply to: <?= htmlspecialchars($message['name']) ?> (<?= htmlspecialchars($message['email']) ?>)</h2>

    <?php if ($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title">Original Message</h5>
        <p><strong>Subject:</strong> <?= htmlspecialchars($message['subject']) ?></p>
        <p><strong>Message:</strong><br><?= nl2br(htmlspecialchars($message['message'])) ?></p>
        <p class="text-muted"><small>Sent on: <?= date('F j, Y H:i', strtotime($message['created_at'])) ?></small></p>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <form method="POST">
          <div class="mb-3">
            <label>Your Reply</label>
            <textarea name="reply" rows="6" class="form-control" required><?= htmlspecialchars($message['reply_message'] ?? '') ?></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Save Reply</button>
          <a href="manage_contacts.php" class="btn btn-secondary">Back</a>
        </form>
      </div>
    </div>

  </div>
</div>
</body>
</html>
