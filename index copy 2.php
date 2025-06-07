<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Fetch recent blog posts
$recentPosts = $pdo->query("SELECT * FROM blog_posts ORDER BY created_at DESC LIMIT 3")->fetchAll();

// Fetch recent episodes (we'll set up episodes table shortly)
$recentEpisodes = $pdo->query("SELECT * FROM episodes ORDER BY created_at DESC LIMIT 2")->fetchAll();
?>

<!-- Hero Banner -->
<section class="py-5 text-center bg-dark text-white">
  <div class="container">
    <h1 class="display-4 fw-bold">This Time Africa</h1>
    <p class="lead mb-4">Breaking Continental Barriers – Uniting Voices Across Africa</p>
    <a href="pages/episodes.php" class="btn btn-warning btn-lg">Watch Episodes</a>
  </div>
</section>

<!-- About Section -->
<section class="py-5 bg-light text-center">
  <div class="container">
    <h2 class="mb-3">Our Vision</h2>
    <p class="lead mx-auto" style="max-width: 800px;">
      This Time Africa is more than a show — it’s a movement. We're spotlighting Africa's boldest thinkers, creators, and changemakers to reshape the global narrative.
    </p>
  </div>
</section>

<!-- Latest Episodes -->
<section class="py-5">
  <div class="container">
    <h2 class="mb-4 text-center">Latest Episodes</h2>
    <div class="row g-4">
      <?php foreach ($recentEpisodes as $ep): ?>
        <div class="col-md-6">
          <div class="card h-100 shadow-sm">
            <div class="ratio ratio-16x9">
              <iframe src="<?= htmlspecialchars($ep['video_link']) ?>" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($ep['title']) ?></h5>
              <p class="card-text"><?= substr(strip_tags($ep['description']), 0, 100) ?>...</p>
              <a href="pages/episodes.php" class="btn btn-outline-dark btn-sm">View All Episodes</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Blog Preview -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="mb-4 text-center">Latest From the Blog</h2>
    <div class="row g-4">
      <?php foreach ($recentPosts as $post): ?>
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <?php if (!empty($post['image'])): ?>
              <img src="assets/images/<?= htmlspecialchars($post['image']) ?>" class="card-img-top" alt="Blog Image">
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
              <p class="text-muted mb-2"><?= htmlspecialchars($post['category']) ?> • <?= date('M d, Y', strtotime($post['created_at'])) ?></p>
              <p class="card-text"><?= substr(strip_tags($post['content']), 0, 90) ?>...</p>
              <a href="pages/blog_post.php?slug=<?= urlencode($post['slug']) ?>" class="btn btn-sm btn-outline-primary">Read More</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4">
      <a href="pages/blog.php" class="btn btn-outline-dark">View All Blog Posts</a>
    </div>
  </div>
</section>

<!-- Call To Action -->
<section class="py-5 text-center text-white bg-dark">
  <div class="container">
    <h3 class="mb-3">Ready to Be Part of the Movement?</h3>
    <p>Join us in amplifying African voices. Watch, share, collaborate.</p>
    <a href="pages/get_involved.php" class="btn btn-warning btn-lg">Get Involved</a>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
