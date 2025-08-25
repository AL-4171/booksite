<?php
require_once __DIR__ . '/../config.php';
if (!isset($_SESSION['user'])) { header('Location: /auth/login.php'); exit; }
check_csrf();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); die('Method not allowed'); }
$id = (int)($_POST['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM books_tables WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user']['id']]);
$book = $stmt->fetch();
if (!$book) { http_response_code(404); die('Not found or not authorized'); }

// Delete file
$path = $UPLOAD_DIR . '/' . $book['filename'];
if (is_file($path)) { @unlink($path); }

// Delete row
$stmt = $pdo->prepare("DELETE FROM books_tables WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user']['id']]);

header('Location: /books/mybooks.php');
exit;
