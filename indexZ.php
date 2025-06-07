<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

$recentPosts = $pdo->query("SELECT * FROM blog_posts WHERE status = 'published' ORDER BY created_at DESC LIMIT 3")->fetchAll();
$recentEpisodes = $pdo->query("SELECT * FROM episodes WHERE status = 'published' ORDER BY created_at DESC LIMIT 2")->fetchAll();
$featuredSegment = $pdo->query("SELECT * FROM segments WHERE is_featured = 1 LIMIT 1")->fetch(PDO::FETCH_ASSOC);
?>

<!-- Hero Banner -->
<!-- Hero Banner with dark overlay -->
<header class="hero-section text-white position-relative">
  <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0,0,0,0.6); z-index:1;"></div>
  <div class="container position-relative py-5" style="z-index:2;">
    <h1 class="display-4 fw-bold">This Time Africa</h1>
    <p class="lead mb-4">Breaking Continental Barriers – Uniting Voices Across Africa</p>
    <div class="d-flex justify-content-center gap-3">
      <a href="pages/episodes.php" class="btn btn-warning btn-lg"><i class="bi bi-play-circle"></i> Watch Episodes</a>
      <a href="pages/get_involved.php" class="btn btn-outline-light btn-lg">Get Involved</a>
    </div>
  </div>
</header>


<!-- Vision Section -->
<section class="py-5 bg-light text-center">
  <div class="container">
    <h2 class="mb-3">Our Vision</h2>
    <p class="lead mx-auto" style="max-width: 800px;">
      This Time Africa is more than a show — it's a movement. We're spotlighting Africa's boldest thinkers, creators, and changemakers to reshape the global narrative.
    </p>
  </div>
</section>

<!-- Featured Segment -->
<?php if ($featuredSegment): ?>
<section class="py-5">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-md-5 text-center">
        <?php if (!empty($featuredSegment['image'])): ?>
          <img src="assets/images/<?= htmlspecialchars($featuredSegment['image']) ?>" alt="Segment Image" class="img-fluid rounded shadow">
        <?php endif; ?>
      </div>
      <div class="col-md-7">
        <h2 class="mb-3"><?= htmlspecialchars($featuredSegment['title']) ?></h2>
        <p class="lead"><?= nl2br(htmlspecialchars($featuredSegment['description'])) ?></p>
        <a href="pages/segment_detail.php?id=<?= $featuredSegment['id'] ?>" class="btn btn-primary mt-3">Explore Segment</a>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Latest Episodes -->
<section class="py-5">
  <div class="container">
    <h2 class="mb-4 text-center">📺 Latest Episodes</h2>
    <div class="row g-4">
      <?php foreach ($recentEpisodes as $ep): ?>
        <div class="col-md-6">
          <div class="card h-100 shadow-sm">
            <div class="ratio ratio-16x9">
              <iframe 
                src="<?= htmlspecialchars($ep['video_link']) ?>" 
                title="<?= htmlspecialchars($ep['title']) ?>" 
                allowfullscreen 
                loading="lazy">
              </iframe>
            </div>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($ep['title']) ?></h5>
              <p class="card-text"><?= mb_strimwidth(strip_tags($ep['description']), 0, 100, '...') ?></p>
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
    <h2 class="mb-4 text-center">📰 Latest From the Blog</h2>
    <div class="row g-4">
      <?php foreach ($recentPosts as $post): ?>
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <?php if (!empty($post['image'])): ?>
              <img src="assets/images/<?= htmlspecialchars($post['image']) ?>" class="card-img-top" alt="Blog Image">
            <?php else: ?>
              <img src="assets/images/default_blog.jpg" class="card-img-top" alt="Default Blog Image">
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
              <p class="text-muted small">
                <?= htmlspecialchars($post['category']) ?> • <?= date('M d, Y', strtotime($post['created_at'])) ?>
              </p>
              <p class="card-text"><?= mb_strimwidth(strip_tags($post['content']), 0, 90, '...') ?></p>
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
