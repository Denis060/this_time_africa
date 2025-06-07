<!-- includes/admin_sidebar.php -->
<style>
  .sidebar-wrapper {
    width: 240px;
    min-height: 100vh;
    background-color: #002b5c; /* Rich Deep Blue */
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1030;
  }

  .sidebar-wrapper .nav-link {
    color: #ffffff;
    transition: background 0.2s;
    font-size: 15px;
  }

  .sidebar-wrapper .nav-link:hover,
  .sidebar-wrapper .nav-link.active {
    background-color: #004080;
    color: #ffffff;
  }

  @media (max-width: 991.98px) {
    .sidebar-wrapper {
      left: -240px;
      transition: left 0.3s ease;
    }

    .sidebar-wrapper.show {
      left: 0;
    }

    .content-shift {
      margin-left: 0;
    }
  }

  @media (min-width: 992px) {
    .content-shift {
      margin-left: 240px;
    }
  }

  .sidebar-header {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 1.5rem;
  }

  .sidebar-logo {
    width: 40px;
    height: 40px;
    object-fit: cover;
    margin-right: 10px;
    border-radius: 50%;
  }
</style>

<!-- Toggle for mobile -->
<nav class="navbar bg-dark d-lg-none px-3">
  <button class="btn btn-outline-light" id="toggleSidebar">
    ☰ Menu
  </button>
</nav>

<!-- Sidebar -->
<div class="sidebar-wrapper d-flex flex-column p-3 text-white">
  <a href="dashboard.php" class="d-flex align-items-center mb-4 text-white text-decoration-none">
    <img src="/assets/images/logo.png" alt="Logo" class="sidebar-logo">
    <span class="sidebar-header">This Time Africa</span>
  </a>
  <?php
$contactCount = 0;
try {
  $stmt = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0");
  $contactCount = $stmt->fetchColumn();
} catch (Exception $e) {
  $contactCount = 0;
}
?>

  <ul class="nav nav-pills flex-column mb-auto">
    <li><a href="dashboard.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">📊 Dashboard</a></li>
    <li><a href="manage_about.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_about.php' ? 'active' : '' ?>">📖 About</a></li>
    <li><a href="manage_segments.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_segments.php' ? 'active' : '' ?>">📚 Segments</a></li>
    <li><a href="manage_get_involved.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_get_involved.php' ? 'active' : '' ?>">🤝 Get Involved</a></li>
    <li><a href="manage_episodes.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_episodes.php' ? 'active' : '' ?>">🎞 Episodes</a></li>
    <li><a href="manage_categories.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_categories.php' ? 'active' : '' ?>">📁 Categories</a></li>
    <li><a href="manage_blog.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_blog.php' ? 'active' : '' ?>">📝 Blog</a></li>
    <li>
  <a href="manage_contacts.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_contacts.php' ? 'active' : '' ?>">
    📩 Contacts
    <?php if ($contactCount > 0): ?>
      <span class="badge bg-danger ms-2"><?= $contactCount ?></span>
    <?php endif; ?>
  </a>
</li>

    <li><a href="manage_users.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : '' ?>">👤 Users</a></li>
    <li><a href="logout.php" class="nav-link">🔓 Logout</a></li>
  </ul>
</div>

<script>
  const toggleBtn = document.getElementById('toggleSidebar');
  const sidebar = document.querySelector('.sidebar-wrapper');

  toggleBtn?.addEventListener('click', () => {
    sidebar.classList.toggle('show');
  });
</script>
