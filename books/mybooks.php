<?php
require_once __DIR__ . '/../config.php';
if (!isset($_SESSION['user'])) { header('Location: ../auth/login.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM books_tables WHERE user_id = ? ORDER BY uploaded_at DESC");
$stmt->execute([$_SESSION['user']['id']]);
$books = $stmt->fetchAll();

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>
<div class="container py-4">
  <div class="d-flex align-items-center mb-3">
    <h1 class="h4 mb-0">My Books</h1>
    <a class="btn btn-primary btn-sm ms-auto rounded-4" href="create.php">Upload</a>
  </div>

  <div class="row g-3">
    <?php if (!$books): ?>
      <div class="col-12"><div class="alert alert-info">You haven't uploaded any books yet.</div></div>
    <?php endif; ?>
    <?php foreach ($books as $b): ?>
      <div class="col-12 col-md-6 col-lg-4">
        <div class="card shadow-sm rounded-4 h-100">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($b['title']) ?></h5>
            <p class="card-text small text-muted mb-4"><?= htmlspecialchars(date('M j, Y', strtotime($b['uploaded_at']))) ?></p>
            <div class="mt-auto d-flex gap-2">
              <a class="btn btn-outline-primary btn-sm rounded-3" href="view.php?id=<?= $b['id'] ?>">View</a>
              <a class="btn btn-outline-secondary btn-sm rounded-3" href="edit.php?id=<?= $b['id'] ?>">Edit</a>
              <form method="post" action="delete.php" onsubmit="return confirm('Delete this book?');">
                <input type="hidden" name="id" value="<?= $b['id'] ?>">
              
                <button class="btn btn-outline-danger btn-sm rounded-3" type="submit">Delete</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
