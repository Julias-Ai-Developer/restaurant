<?php require_once __DIR__ . '/../includes/auth.php'; ?>
<?php
$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
  $stmt = mysqli_prepare($conn, 'SELECT image FROM menu_items WHERE id=?');
  mysqli_stmt_bind_param($stmt, 'i', $id);
  mysqli_stmt_execute($stmt);
  $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
  mysqli_stmt_close($stmt);
  if ($row) {
    if ($row['image'] && file_exists(__DIR__ . '/../../' . ltrim($row['image'],'/'))) {
      @unlink(__DIR__ . '/../../' . ltrim($row['image'],'/'));
    }
    $stmt = mysqli_prepare($conn, 'DELETE FROM menu_items WHERE id=?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
}
header('Location: /admin/menu/add.php');
exit;
?>