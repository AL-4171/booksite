<?php
require_once __DIR__ . '/../config.php';



$err = $_SESSION['flash_error'] ?? '';
$msg = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_error'], $_SESSION['flash_success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = trim($_POST['password'] ?? '');
    $pass2 = trim($_POST['password2'] ?? '');

    if (!$username || !$email || !$pass) {
        $_SESSION['flash_error'] = "All fields are required.";
        header("Location: register.php");
        exit;
    } elseif ($pass !== $pass2) {
        $_SESSION['flash_error'] = "Passwords do not match.";
        header("Location: register.php");
        exit;
    } else {
        try {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users_books (username, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hash]);
            $id = $pdo->lastInsertId();

            $_SESSION['user'] = [
                'id' => $id,
                'username' => $username,
                'email' => $email
            ];
            $_SESSION['flash_success'] = "Welcome, " . htmlspecialchars($username);
            header("Location: /php-booksite/index.php");
            exit;
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate') !== false) {
                $_SESSION['flash_error'] = "Username or email already exists.";
            } else {
                $_SESSION['flash_error'] = "Registration failed.";
            }
            header("Location: register.php");
            exit;
        }
    }
}

include __DIR__ . '/../partials/header.php';
?>
<div class="container py-5" style="max-width:640px;">
  <div class="card shadow-sm rounded-4">
    <div class="card-body p-4">
      <h1 class="h3 mb-3 text-center">Create an account</h1>
      <?php if ($err): ?><div class="alert alert-danger"><?= $err ?></div><?php endif; ?>
      <?php if ($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>
      <form method="post" action="">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password2" class="form-control" required>
          </div>
        </div>
        <div class="d-grid gap-2">
          <button class="btn btn-primary rounded-4" type="submit">Sign up</button>
          <a class="btn btn-outline-secondary rounded-4" href="login.php">I already have an account</a>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>