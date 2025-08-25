<?php
require_once __DIR__ . '/../config.php';
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }


$user = $_SESSION['user'];
$err='';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $pdo->beginTransaction();
    try {
      
        $stmt = $pdo->prepare("SELECT filename FROM books_tables WHERE user_id = ?");
        $stmt->execute([$user['id']]);
        $files = $stmt->fetchAll();
        
        $stmt = $pdo->prepare("DELETE FROM users_books WHERE id = ?");
        $stmt->execute([$user['id']]);
        $pdo->commit();
    
        foreach ($files as $f) {
            $path = $UPLOAD_DIR . '/' . $f['filename'];
            if (is_file($path)) { @unlink($path); }
        }
        session_unset();
        session_destroy();
        header('Location: register.php');
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $err = "Could not delete account.";
    }
}

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>
<div class="container py-5" style="max-width:720px;">
  <div class="card border-danger rounded-4 shadow-sm">
    <div class="card-body p-4">
      <h1 class="h4 text-danger">Delete your account</h1>
      <?php if ($err): ?><div class="alert alert-danger"><?= htmlspecialchars($err) ?></div><?php endif; ?>
      <p>Deleting your account will remove all your uploaded books and cannot be undone.</p>
      <form method="post" action="">
        
        <a href="../index.php" class="btn btn-outline-secondary rounded-4">Cancel</a>
        <button type="submit" class="btn btn-danger rounded-4">Delete my account</button>
      </form>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
