<?php require_once __DIR__ . '/../includes/auth.php'; ?>
<?php
$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
  $stmt = mysqli_prepare($conn, 'SELECT image_path FROM gallery WHERE id=?');
  mysqli_stmt_bind_param($stmt, 'i', $id);
  mysqli_stmt_execute($stmt);
  $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);
  if ($row) {
    if ($row['image_path'] && file_exists(__DIR__ . '/../../' . ltrim($row['image_path'],'/'))) {
      @unlink(__DIR__ . '/../../' . ltrim($row['image_path'],'/'));
    }
    $stmt = mysqli_prepare($conn, 'DELETE FROM gallery WHERE id=?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
}
header('Location: /admin/gallery/upload.php');
exit;
?>