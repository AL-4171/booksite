<?php
require_once __DIR__ . '/../config.php';
if (!isset($_SESSION['user'])) { header('Location: /auth/login.php'); exit; }

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM books_tables WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user']['id']]);
$book = $stmt->fetch();
if (!$book) { http_response_code(404); die('Not found or not authorized'); }

$err='';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $file = $_FILES['file'] ?? null;

    if (!$title) {
        $err = "Title is required.";
    } else {
        // Optional file replace
        $stored = $book['filename'];
        $original = $book['original_name'];
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            if ($file['size'] > $MAX_FILE_SIZE) {
                $err = "File is too large. Max 50 MB.";
            } else {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $storedNew = bin2hex(random_bytes(8)) . ($ext ? ".$ext" : "");
                $dest = $UPLOAD_DIR . '/' . $storedNew;
                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    // Remove old file
                    $old = $UPLOAD_DIR . '/' . $stored;
                    if (is_file($old)) { @unlink($old); }
                    $stored = $storedNew;
                    $original = $file['name'];
                } else {
                    $err = "Failed to replace file.";
                }
            }
        }
        if (!$err) {
            $stmt = $pdo->prepare("UPDATE books_tables SET title = ?, filename = ?, original_name = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$title, $stored, $original, $id, $_SESSION['user']['id']]);
            header('Location: mybooks.php');
            exit;
        }
    }
}

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>
<div class="container py-5" style="max-width:720px;">
  <div class="card shadow-sm rounded-4">
    <div class="card-body p-4">
      <h1 class="h4 mb-3">Edit Book</h1>
      <?php if ($err): ?><div class="alert alert-danger"><?= htmlspecialchars($err) ?></div><?php endif; ?>
      <form method="post" enctype="multipart/form-data" action="">
        
        <div class="mb-3">
          <label class="form-label">Title</label>
          <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($book['title']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Replace file (optional)</label>
          <input type="file" name="file" class="form-control">
          <div class="form-text">Current: <?= htmlspecialchars($book['original_name']) ?></div>
        </div>
        <div class="d-grid">
          <button class="btn btn-primary rounded-4" type="submit">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
