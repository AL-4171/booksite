<?php
require_once __DIR__ . '/../config.php';



$err = $_SESSION['flash_error'] ?? '';
$msg = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_error'], $_SESSION['flash_success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass = trim($_POST['password'] ?? '');

    if (!$email || !$pass) {
        $_SESSION['flash_error'] = "Email and password are required.";
        header("Location: login.php");
        exit;
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users_books WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($pass, $user['password_hash'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email']
            ];
            $_SESSION['flash_success'] = "Welcome back, " . htmlspecialchars($user['username']);
            header("Location: /php-booksite/index.php");
            exit;
        } else {
            $_SESSION['flash_error'] = "Invalid credentials.";
            header("Location: login.php");
            exit;
        }
    }
}

include __DIR__ . '/../partials/header.php';
?>
<div class="container py-5" style="max-width:520px;">
  <div class="card shadow-sm rounded-4">
    <div class="card-body p-4">
      <h1 class="h3 mb-3 text-center">Login</h1>
      <?php if ($err): ?><div class="alert alert-danger"><?= $err ?></div><?php endif; ?>
      <?php if ($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>
      <form method="post" action="">
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="d-grid gap-2">
          <button class="btn btn-primary rounded-4" type="submit">Login</button>
          <a class="btn btn-outline-secondary rounded-4" href="register.php">Create account</a>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>