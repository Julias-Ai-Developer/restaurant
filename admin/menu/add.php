<?php require_once __DIR__ . '/../includes/auth.php'; 
$sn = 1;
?>
<?php include __DIR__ . '/../includes/header.php'; ?>

<style>
.page-header {
  background: linear-gradient(135deg, #2F6232 0%, #8BAE66 100%);
  color: white;
  padding: 2rem;
  border-radius: 12px;
  margin-bottom: 2rem;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.page-header h1 {
  font-size: 2rem;
  font-weight: 700;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.form-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  overflow: hidden;
  margin-bottom: 2rem;
}

.form-card-header {
  background: linear-gradient(135deg, rgba(47, 98, 50, 0.05) 0%, rgba(139, 174, 102, 0.05) 100%);
  padding: 1.25rem 1.5rem;
  border-bottom: 2px solid #8BAE66;
}

.form-card-header h3 {
  margin: 0;
  color: #2F6232;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.form-card-body {
  padding: 2rem;
}

.form-label {
  font-weight: 600;
  color: #2F6232;
  margin-bottom: 0.5rem;
}

.input-group-text {
  background: rgba(139, 174, 102, 0.1);
  border-color: rgba(139, 174, 102, 0.3);
  color: #2F6232;
}

.form-control:focus, .form-select:focus {
  border-color: #8BAE66;
  box-shadow: 0 0 0 0.2rem rgba(139, 174, 102, 0.25);
}

.btn-brand-primary {
  background: linear-gradient(135deg, #2F6232 0%, #8BAE66 100%);
  border: none;
  color: white;
  padding: 0.6rem 2rem;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-brand-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
  color: white;
}

.category-badge {
  padding: 0.4rem 0.8rem;
  border-radius: 20px;
  font-weight: 500;
  font-size: 0.85rem;
}

.category-starters {
  background: rgba(255, 193, 7, 0.2);
  color: #ff8800;
}

.category-mains {
  background: rgba(220, 53, 69, 0.2);
  color: #dc3545;
}

.category-drinks {
  background: rgba(23, 162, 184, 0.2);
  color: #17a2b8;
}

.category-desserts {
  background: rgba(139, 174, 102, 0.2);
  color: #2F6232;
}

.menu-image {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.price-tag {
  font-weight: 700;
  color: #2F6232;
  font-size: 1.1rem;
}

.action-buttons {
  display: flex;
  gap: 0.3rem;
}

.table-search {
  min-width: 250px;
}

.empty-image {
  width: 80px;
  height: 80px;
  background: rgba(139, 174, 102, 0.1);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #8BAE66;
  font-size: 2rem;
}

.menu-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.stat-mini {
  background: rgba(139, 174, 102, 0.1);
  padding: 1rem;
  border-radius: 8px;
  text-align: center;
}

.stat-mini-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: #2F6232;
}

.stat-mini-label {
  font-size: 0.75rem;
  color: #6c757d;
  text-transform: uppercase;
}

.image-preview {
  margin-top: 0.5rem;
  display: none;
}

.image-preview img {
  max-width: 200px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>

<?php
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_validate();
  $name = trim($_POST['name'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $price = (float)($_POST['price'] ?? 0);
  $category = trim($_POST['category'] ?? '');
  $imagePath = null;
  $categories = ['Starters','Mains','Drinks','Desserts'];
  
  if ($name && $price > 0 && in_array($category, $categories, true)) {
    if (!empty($_FILES['image']['name'])) {
      $v = validate_image_upload($_FILES['image']);
      if ($v !== true) { 
        $msg = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> ' . e($v) . '</div>'; 
      } else {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $dir = __DIR__ . '/../../assets/images/menu/';
        if (!is_dir($dir)) { mkdir($dir, 0775, true); }
        $fname = 'menu_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest = $dir . $fname;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
          $imagePath = 'assets/images/menu/' . $fname;
        }
      }
    }
    
    if ($msg === '') {
      $stmt = mysqli_prepare($conn, 'INSERT INTO menu_items(name, description, price, category, image) VALUES (?,?,?,?,?)');
      mysqli_stmt_bind_param($stmt, 'ssdss', $name, $description, $price, $category, $imagePath);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      $msg = '<div class="alert alert-success"><i class="bi bi-check-circle"></i> Menu item added successfully!</div>';
    }
  } else {
    $msg = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> Please fill all required fields correctly.</div>';
  }
}

// Get category counts
$categoryStats = [];
try {
  $result = @mysqli_query($conn, "SELECT category, COUNT(*) as count FROM menu_items GROUP BY category");
  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $categoryStats[$row['category']] = $row['count'];
    }
  }
} catch (Exception $e) {}
?>

<div class="page-header">
  <h1>
    <i class="bi bi-card-list"></i>
    Menu Management
  </h1>
  <p class="mb-0 mt-2" style="opacity: 0.9;">Add, edit, and manage your restaurant menu items</p>
</div>

<?php echo $msg; ?>

<!-- Add New Item Form -->
<div class="form-card">
  <div class="form-card-header">
    <h3><i class="bi bi-plus-circle"></i> Add New Menu Item</h3>
  </div>
  <div class="form-card-body">
    <form method="post" enctype="multipart/form-data" class="row g-3">
      <?php csrf_input(); ?>
      
      <div class="col-md-6">
        <label class="form-label">
          <i class="bi bi-tag-fill"></i> Item Name *
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-tag"></i></span>
          <input class="form-control" name="name" placeholder="e.g., Grilled Salmon" required>
        </div>
      </div>
      
      <div class="col-md-6">
        <label class="form-label">
          <i class="bi bi-list-ul"></i> Category *
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-list"></i></span>
          <select name="category" class="form-select" required>
            <option value="">Select Category</option>
            <option value="Starters">🥗 Starters</option>
            <option value="Mains">🍽️ Mains</option>
            <option value="Drinks">🥤 Drinks</option>
            <option value="Desserts">🍰 Desserts</option>
          </select>
        </div>
      </div>
      
      <div class="col-12">
        <label class="form-label">
          <i class="bi bi-text-paragraph"></i> Description
        </label>
        <textarea name="description" class="form-control" rows="3" placeholder="Describe the dish, ingredients, and any special features..."></textarea>
      </div>
      
      <div class="col-md-6">
        <label class="form-label">
          <i class="bi bi-currency-dollar"></i> Price (USD) *
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
          <input type="number" step="0.01" min="0" name="price" class="form-control" placeholder="0.00" required>
        </div>
      </div>
      
      <div class="col-md-6">
        <label class="form-label">
          <i class="bi bi-image"></i> Item Image
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-upload"></i></span>
          <input type="file" name="image" class="form-control" accept="image/*" id="imageInput">
        </div>
        <div class="image-preview" id="imagePreview">
          <img src="" alt="Preview" id="previewImg">
        </div>
      </div>
      
      <div class="col-12">
        <button class="btn btn-brand-primary" type="submit">
          <i class="bi bi-plus-circle"></i> Add Menu Item
        </button>
        <a href="/admin/dashboard.php" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
      </div>
    </form>
  </div>
</div>

<!-- Existing Items -->
<div class="form-card">
  <div class="form-card-header d-flex justify-content-between align-items-center">
    <h3><i class="bi bi-list-check"></i> Existing Menu Items</h3>
    <div class="input-group input-group-sm w-auto">
      <span class="input-group-text"><i class="bi bi-search"></i></span>
      <input type="text" class="form-control table-search" id="searchInput" placeholder="Search items...">
    </div>
  </div>
  
  <!-- Category Statistics -->
  <div class="form-card-body border-bottom">
    <div class="menu-stats">
      <div class="stat-mini">
        <div class="stat-mini-value"><?php echo $categoryStats['Starters'] ?? 0; ?></div>
        <div class="stat-mini-label">Starters</div>
      </div>
      <div class="stat-mini">
        <div class="stat-mini-value"><?php echo $categoryStats['Mains'] ?? 0; ?></div>
        <div class="stat-mini-label">Mains</div>
      </div>
      <div class="stat-mini">
        <div class="stat-mini-value"><?php echo $categoryStats['Drinks'] ?? 0; ?></div>
        <div class="stat-mini-label">Drinks</div>
      </div>
      <div class="stat-mini">
        <div class="stat-mini-value"><?php echo $categoryStats['Desserts'] ?? 0; ?></div>
        <div class="stat-mini-label">Desserts</div>
      </div>
    </div>
  </div>
  
  <div class="form-card-body p-0">
    <div class="table-responsive">
      <table id="menuTable" class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Category</th>
            <th>Description</th>
            <th>Price</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $res = @mysqli_query($conn, 'SELECT id, name, description, category, price, image FROM menu_items ORDER BY category, name');
          if ($res && mysqli_num_rows($res) > 0):
            while ($row = mysqli_fetch_assoc($res)): 
              $categoryClass = 'category-' . strtolower($row['category']);
          ?>
            <tr>
              <td><strong>#<?php echo ($sn++); ?></strong></td>
              <td>
                <?php if ($row['image']): ?>
                  <img src="<?php echo e('../../' . ltrim($row['image'], '/')); ?>" alt="<?php echo e($row['name']); ?>" class="menu-image">
                <?php else: ?>
                  <div class="empty-image">
                    <i class="bi bi-image"></i>
                  </div>
                <?php endif; ?>
              </td>
              <td>
                <strong><?php echo e($row['name']); ?></strong>
              </td>
              <td>
                <span class="category-badge <?php echo $categoryClass; ?>">
                  <?php echo e($row['category']); ?>
                </span>
              </td>
              <td>
                <small class="text-muted">
                  <?php echo e(substr($row['description'] ?? 'No description', 0, 60)); ?>
                  <?php if (strlen($row['description'] ?? '') > 60) echo '...'; ?>
                </small>
              </td>
              <td>
                <span class="price-tag">$<?php echo e(number_format((float)$row['price'], 2)); ?></span>
              </td>
              <td>
                <div class="action-buttons justify-content-end">
                  <a class="btn btn-sm btn-outline-primary" 
                     href="/admin/menu/edit.php?id=<?php echo e($row['id']); ?>"
                     title="Edit">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a class="btn btn-sm btn-outline-danger" 
                     href="/admin/menu/delete.php?id=<?php echo e($row['id']); ?>" 
                     onclick="return confirm('Are you sure you want to delete this item?')"
                     title="Delete">
                    <i class="bi bi-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php 
            endwhile;
          else: 
          ?>
            <tr>
              <td colspan="7" class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: rgba(139, 174, 102, 0.3);"></i>
                <p class="mt-3 text-muted">No menu items yet. Add your first item above!</p>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
// Image preview
document.getElementById('imageInput').addEventListener('change', function(e) {
  const preview = document.getElementById('imagePreview');
  const previewImg = document.getElementById('previewImg');
  const file = e.target.files[0];
  
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      previewImg.src = e.target.result;
      preview.style.display = 'block';
    };
    reader.readAsDataURL(file);
  } else {
    preview.style.display = 'none';
  }
});

// Real-time table search
document.getElementById('searchInput').addEventListener('keyup', function() {
  const searchTerm = this.value.toLowerCase();
  const rows = document.querySelectorAll('#menuTable tbody tr');
  
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(searchTerm) ? '' : 'none';
  });
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>