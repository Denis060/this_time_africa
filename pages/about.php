<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

// Fetch content
$stmt = $pdo->query("SELECT * FROM about_show ORDER BY created_at DESC LIMIT 1");
$about = $stmt->fetch();
?>

<section class="py-5 bg-light">
  <div class="container">
    <h2 class="text-center mb-4">🎙️ About the Show</h2>

    <?php if ($about): ?>
    <div class="card shadow border-0">
      <div class="row g-0">
        <div class="col-md-4">
          <img src="../assets/images/<?= htmlspecialchars($about['image']) ?>" class="img-fluid rounded-start h-100 object-fit-cover" alt="Dr. Matilda Banga">
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h4 class="card-title"><?= htmlspecialchars($about['title']) ?></h4>
            <p class="card-text"><?= nl2br(htmlspecialchars($about['vision'])) ?></p>
            <button class="btn btn-primary mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#aboutDetails" aria-expanded="false" aria-controls="aboutDetails">
              Learn More
            </button>
            <div class="collapse mt-3" id="aboutDetails">
              <div class="card card-body">
                <h5>🌍 Vision & Mission</h5>
                <p><?= nl2br(htmlspecialchars($about['vision'])) ?></p>

                <h5>👩🏽‍⚕️ Meet the Host</h5>
                <p><?= nl2br(htmlspecialchars($about['bio'])) ?></p>

                <h5>⚠️ Why This Show Matters Now</h5>
                <p><?= nl2br(htmlspecialchars($about['importance'])) ?></p>

                <h5>💬 Quote to Inspire</h5>
                <blockquote class="blockquote">
                  <p><?= htmlspecialchars($about['quote']) ?></p>
                  <footer class="blockquote-footer"><?= htmlspecialchars($about['quote_author']) ?></footer>
                </blockquote>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php else: ?>
      <div class="alert alert-info text-center">About content not available yet.</div>
    <?php endif; ?>
  </div>
</section>

<?php require_once '../includes/footer.php'; ?>
