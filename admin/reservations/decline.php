<?php require_once __DIR__ . '/../includes/auth.php'; ?>
<?php
$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
  $stmt = mysqli_prepare($conn, "UPDATE reservations SET status='declined' WHERE id=?");
  mysqli_stmt_bind_param($stmt, 'i', $id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
}
header('Location: ../reservations/view.php');
exit;
?>