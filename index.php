<?php
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['user'])) {
    header('Location: auth/login.php');
    exit;
}


$stmt = $pdo->query("SELECT b.*, u.username FROM books_tables b JOIN users_books u ON b.user_id = u.id ORDER BY b.uploaded_at DESC");
$books = $stmt->fetchAll();

include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/navbar.php';
?>
<div class="container py-4">
  <div class="p-5 mb-4 hero">
    <div class="container py-5">
      <h1 class="display-5 fw-bold">Welcome to BookSite</h1>
      <p class="col-md-8 fs-5">Upload, manage, and browse books of any file type. Simple, elegant, secure.</p>
      <a href="books/create.php" class="btn btn-primary btn-lg rounded-4">Upload a Book</a>
    </div>
  </div>

  <h2 class="h4 mb-3">All Uploaded Books</h2>
  <div class="row g-3">
    <?php if (!$books): ?>
      <div class="col-12"><div class="alert alert-info">No books yet. Be the first to upload!</div></div>
    <?php endif; ?>
    <?php foreach ($books as $b): ?>
      <div class="col-12 col-md-6 col-lg-4">
        <div class="card shadow-sm rounded-4 card-hover h-100">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($b['title']) ?></h5>
            <p class="card-text small text-muted mb-4">by <?= htmlspecialchars($b['username']) ?> • <?= htmlspecialchars(date('M j, Y', strtotime($b['uploaded_at']))) ?></p>
            <a class="btn btn-outline-primary mt-auto rounded-3" href="books/view.php?id=<?= $b['id'] ?>">View / Download</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
