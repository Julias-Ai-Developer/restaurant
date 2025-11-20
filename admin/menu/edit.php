<?php require_once __DIR__ . '/../includes/auth.php'; ?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<?php
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { echo '<div class="alert alert-danger">Invalid ID.</div>'; include __DIR__ . '/../includes/footer.php'; exit; }
$stmt = mysqli_prepare($conn, 'SELECT id,name,description,price,category,image FROM menu_items WHERE id=?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$item = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);
if (!$item) { echo '<div class="alert alert-danger">Item not found.</div>'; include __DIR__ . '/../includes/footer.php'; exit; }

$msg='';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_validate();
  $name = trim($_POST['name'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $price = (float)($_POST['price'] ?? 0);
  $category = trim($_POST['category'] ?? '');
  $imagePath = $item['image'];
  $categories = ['Starters','Mains','Drinks','Desserts'];
  if ($name && $price > 0 && in_array($category, $categories, true)) {
    if (!empty($_FILES['image']['name'])) {
      $v = validate_image_upload($_FILES['image']);
      if ($v !== true) { $msg = '<div class="alert alert-danger">' . e($v) . '</div>'; }
      else {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $dir = __DIR__ . '/../../assets/images/menu/';
        if (!is_dir($dir)) { mkdir($dir, 0775, true); }
        $fname = 'menu_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest = $dir . $fname;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
          if ($imagePath && file_exists(__DIR__ . '/../../' . ltrim($imagePath,'/'))) { @unlink(__DIR__ . '/../../' . ltrim($imagePath,'/')); }
          $imagePath = 'assets/images/menu/' . $fname;
        }
      }
    }
    if ($msg === '') {
      $stmt = mysqli_prepare($conn, 'UPDATE menu_items SET name=?, description=?, price=?, category=?, image=? WHERE id=?');
      mysqli_stmt_bind_param($stmt, 'ssdssi', $name, $description, $price, $category, $imagePath, $id);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      $msg = '<div class="alert alert-success">Menu item updated.</div>';
      // refresh item
      $stmt = mysqli_prepare($conn, 'SELECT id,name,description,price,category,image FROM menu_items WHERE id=?');
      mysqli_stmt_bind_param($stmt, 'i', $id);
      mysqli_stmt_execute($stmt);
      $item = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
      mysqli_stmt_close($stmt);
    }
  } else {
    $msg = '<div class="alert alert-danger">Please fill the form correctly.</div>';
  }
}
?>

<h1 class="mb-3">Edit Menu Item</h1>
<?php echo $msg; ?>
<form method="post" enctype="multipart/form-data" class="row g-3">
  <?php csrf_input(); ?>
  <div class="col-md-6"><label class="form-label">Name</label><input class="form-control" name="name" value="<?php echo e($item['name']); ?>" required></div>
  <div class="col-md-6"><label class="form-label">Category</label>
    <select name="category" class="form-select" required>
      <?php foreach (['Starters','Mains','Drinks','Desserts'] as $c): ?>
        <option <?php if ($item['category']===$c) echo 'selected'; ?>><?php echo e($c); ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3"><?php echo e($item['description']); ?></textarea></div>
  <div class="col-md-6"><label class="form-label">Price (USD)</label><input type="number" step="0.01" min="0" name="price" class="form-control" value="<?php echo e($item['price']); ?>" required></div>
  <div class="col-md-6"><label class="form-label">Image</label><input type="file" name="image" class="form-control" accept="image/*">
    <?php if ($item['image']): ?><img src="<?php echo e('../../' . ltrim($item['image'],'/')); ?>" alt="" class="mt-2" width="100"><?php endif; ?></div>
  <div class="col-12"><button class="btn btn-primary" type="submit">Save</button> <a href="/admin/menu/add.php" class="btn btn-secondary">Back</a></div>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>