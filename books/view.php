<?php
require_once __DIR__ . '/../config.php';
if (!isset($_GET['id'])) { http_response_code(400); die('Missing id'); }
$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT b.*, u.username 
                       FROM books_tables b 
                       JOIN users_books u ON b.user_id = u.id 
                       WHERE b.id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();
if (!$book) { http_response_code(404); die('Not found'); }

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>
<div class="container py-5" style="max-width:800px;">
  <div class="card shadow-sm rounded-4">
    <div class="card-body p-4">
      <h1 class="h4"><?= htmlspecialchars($book['title']) ?></h1>
      <p class="text-muted mb-4">
        Uploaded by <?= htmlspecialchars($book['username']) ?> 
        on <?= htmlspecialchars(date('M j, Y', strtotime($book['uploaded_at']))) ?>
      </p>

      <a class="btn btn-success rounded-4 me-2" 
         href="../uploads/<?= rawurlencode($book['filename']) ?>" 
         target="_blank">
         View file
      </a>

  
      <a class="btn btn-primary rounded-4" 
         href="/uploads/<?= rawurlencode($book['filename']) ?>" 
         download="<?= htmlspecialchars($book['original_name']) ?>">
         Download file
      </a>

      <div class="mt-3">
        <span class="badge bg-secondary">
          Original: <?= htmlspecialchars($book['original_name']) ?>
        </span>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>

