<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$user = $_SESSION['user'] ?? null;
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/php-booksite/index.php">📚 BookSite</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExample07">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="/php-booksite/index.php">Home</a>
        </li>
        <?php if ($user): ?>
        <li class="nav-item">
          <a class="nav-link" href="/php-booksite/books/mybooks.php">MyBooks</a>
        </li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if (!$user): ?>
          <li class="nav-item"><a class="nav-link" href="/php-booksite/auth/login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="/php-booksite/auth/register.php">Sign Up</a></li>
        <?php else: ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
              <?= htmlspecialchars($user['username']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="/php-booksite/books/create.php">Upload a Book</a></li>
              <li><a class="dropdown-item" href="/php-booksite/auth/logout.php">Logout</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="/php-booksite/auth/delete_account.php">Delete Account</a></li>
            </ul>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>