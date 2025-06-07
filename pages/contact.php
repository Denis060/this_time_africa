<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

$msg = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $subject = trim($_POST['subject'] ?? '');
  $message = trim($_POST['message'] ?? '');

  if ($name && $email && $subject && $message) {
    $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $subject, $message]);
    $msg = "✅ Thank you for reaching out. We'll get back to you!";
  } else {
    $error = "❌ Please fill in all fields before submitting.";
  }
}
?>


<main>
  <section class="py-5">
    <div class="container">
      <h2 class="mb-4 text-center">📬 Contact Us</h2>

      <?php if ($msg): ?>
        <div class="alert alert-success"><?= $msg ?></div>
      <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>
      

      <form method="POST" class="row g-3">
        <div class="col-md-6">
          <input type="text" name="name" class="form-control" placeholder="Your Name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
        </div>
        <div class="col-md-6">
          <input type="email" name="email" class="form-control" placeholder="Your Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>
        <div class="col-md-12">
          <input type="text" name="subject" class="form-control" placeholder="Subject" value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>" required>
        </div>
        <div class="col-12">
          <textarea name="message" class="form-control" rows="5" placeholder="Your Message" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
        </div>
        <div class="col-12 text-end">
          <button type="submit" class="btn btn-primary">Send Message</button>
        </div>
      </form>
    </div>
  </section>
</main>

<?php require_once '../includes/footer.php'; ?>
