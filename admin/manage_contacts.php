<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Fetch all messages
$messages = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Contact Messages</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
  <?php include 'includes/admin_sidebar.php'; ?>
  <div class="container-fluid p-4 content-shift">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">📩 Contact Messages</h2>
      <a href="dashboard.php" class="btn btn-outline-secondary">← Back to Dashboard</a>
    </div>

    <div class="card shadow-sm">
      <div class="card-body">
        <?php if (empty($messages)): ?>
          <div class="alert alert-info">No messages received yet.</div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-bordered align-middle table-hover">
              <thead class="table-light">
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Subject</th>
                  <th>Message</th>
                  <th>Date</th>
                  <th>Reply</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($messages as $msg): ?>
                <tr>
                  <td><?= htmlspecialchars($msg['name']) ?></td>
                  <td><?= htmlspecialchars($msg['email']) ?></td>
                  <td><?= htmlspecialchars($msg['subject'] ?? '-') ?></td>
                  <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                  <td><?= date('M d, Y h:i A', strtotime($msg['created_at'])) ?></td>
                  <td>
                    <?php if (!empty($msg['reply_message'])): ?>
                      <span class="badge bg-success">✔ Replied</span>
                    <?php else: ?>
                      <span class="badge bg-warning text-dark">Pending</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <a href="reply_contact.php?id=<?= $msg['id'] ?>" class="btn btn-sm btn-primary">
                      <i class="bi bi-reply"></i> Reply
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
</body>
</html>
