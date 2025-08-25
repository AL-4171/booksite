<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$err = $_SESSION['flash_error'] ?? '';
$msg = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_error'], $_SESSION['flash_success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $file  = $_FILES['file'] ?? null;

    if ($title === '') {
        $_SESSION['flash_error'] = "Please enter a book title.";
        header("Location: create.php");
        exit;
    }

    if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
        $_SESSION['flash_error'] = "Please select a file to upload.";
        header("Location: create.php");
        exit;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['flash_error'] = "File upload error (code: {$file['error']}).";
        header("Location: create.php");
        exit;
    }

    if ($file['size'] > $MAX_FILE_SIZE) {
        $_SESSION['flash_error'] = "File is too large. Max 50 MB.";
        header("Location: create.php");
        exit;
    }

    if (!is_dir($UPLOAD_DIR)) {
        mkdir($UPLOAD_DIR, 0775, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $stored = bin2hex(random_bytes(8)) . ($ext ? ".$ext" : "");
    $dest = $UPLOAD_DIR . '/' . $stored;

    if (move_uploaded_file($file['tmp_name'], $dest)) {
        $stmt = $pdo->prepare("INSERT INTO books_tables (user_id, title, filename, original_name) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user']['id'], $title, $stored, $file['name']]);

        $_SESSION['flash_success'] = "Book \"$title\" uploaded successfully.";
        header("Location: mybooks.php");
        exit;
    } else {
        $_SESSION['flash_error'] = "Failed to save uploaded file.";
        header("Location: create.php");
        exit;
    }
}

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>
<div class="container py-5" style="max-width:720px;">
  <div class="card rounded-4 shadow-sm">
    <div class="card-body p-4">
      <h1 class="h4 mb-3">Upload a Book</h1>
      <?php if ($err): ?><div class="alert alert-danger"><?= $err ?></div><?php endif; ?>
      <?php if ($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>
      <form method="post" enctype="multipart/form-data" action="">
        <div class="mb-3">
          <label class="form-label">Title</label>
          <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">File (any extension)</label>
          <input type="file" name="file" class="form-control" required>
          <div class="form-text">Max 50 MB. All file types allowed.</div>
        </div>
        <div class="d-grid">
          <button class="btn btn-primary rounded-4" type="submit">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
