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

.upload-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  overflow: hidden;
  margin-bottom: 2rem;
}

.upload-card-header {
  background: linear-gradient(135deg, rgba(47, 98, 50, 0.05) 0%, rgba(139, 174, 102, 0.05) 100%);
  padding: 1.25rem 1.5rem;
  border-bottom: 2px solid #8BAE66;
}

.upload-card-header h3 {
  margin: 0;
  color: #2F6232;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.upload-card-body {
  padding: 2rem;
}

.upload-zone {
  border: 3px dashed rgba(139, 174, 102, 0.4);
  border-radius: 12px;
  padding: 3rem 2rem;
  text-align: center;
  background: rgba(139, 174, 102, 0.05);
  transition: all 0.3s ease;
  cursor: pointer;
}

.upload-zone:hover {
  border-color: #8BAE66;
  background: rgba(139, 174, 102, 0.1);
}

.upload-zone.dragover {
  border-color: #2F6232;
  background: rgba(139, 174, 102, 0.15);
}

.upload-icon {
  font-size: 4rem;
  color: #8BAE66;
  margin-bottom: 1rem;
}

.upload-text {
  color: #2F6232;
  font-weight: 600;
  font-size: 1.1rem;
  margin-bottom: 0.5rem;
}

.upload-hint {
  color: #6c757d;
  font-size: 0.9rem;
}

.preview-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 1rem;
  margin-top: 1rem;
}

.preview-item {
  position: relative;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.preview-item img {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.preview-item .remove-preview {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
  background: rgba(220, 53, 69, 0.9);
  color: white;
  border: none;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
}

.preview-item .remove-preview:hover {
  background: #dc3545;
  transform: scale(1.1);
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

.gallery-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  overflow: hidden;
}

.gallery-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
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
  font-size: 1.8rem;
  font-weight: 700;
  color: #2F6232;
}

.stat-mini-label {
  font-size: 0.8rem;
  color: #6c757d;
  text-transform: uppercase;
}

.gallery-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 1.5rem;
}

.gallery-item {
  position: relative;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: all 0.3s ease;
  background: white;
}

.gallery-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0,0,0,0.15);
}

.gallery-item img {
  width: 100%;
  height: 250px;
  object-fit: cover;
  display: block;
}

.gallery-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 50%);
  opacity: 0;
  transition: all 0.3s ease;
  display: flex;
  align-items: flex-end;
  padding: 1rem;
}

.gallery-item:hover .gallery-overlay {
  opacity: 1;
}

.gallery-actions {
  display: flex;
  gap: 0.5rem;
  width: 100%;
}

.gallery-actions button,
.gallery-actions a {
  flex: 1;
  padding: 0.5rem;
  border: none;
  border-radius: 6px;
  color: white;
  font-weight: 600;
  transition: all 0.3s ease;
  text-decoration: none;
  text-align: center;
}

.btn-view {
  background: #17a2b8;
}

.btn-view:hover {
  background: #138496;
  color: white;
}

.btn-delete {
  background: #dc3545;
}

.btn-delete:hover {
  background: #c82333;
}

.gallery-id {
  position: absolute;
  top: 0.5rem;
  left: 0.5rem;
  background: rgba(47, 98, 50, 0.9);
  color: white;
  padding: 0.3rem 0.6rem;
  border-radius: 6px;
  font-size: 0.75rem;
  font-weight: 600;
}

.empty-gallery {
  text-align: center;
  padding: 4rem 2rem;
  color: #6c757d;
}

.empty-gallery i {
  font-size: 5rem;
  color: rgba(139, 174, 102, 0.3);
  margin-bottom: 1.5rem;
}

/* Lightbox Modal */
.lightbox {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.95);
  z-index: 9999;
  align-items: center;
  justify-content: center;
}

.lightbox.active {
  display: flex;
}

.lightbox img {
  max-width: 90%;
  max-height: 90%;
  border-radius: 8px;
}

.lightbox-close {
  position: absolute;
  top: 2rem;
  right: 2rem;
  background: white;
  color: #2F6232;
  border: none;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  font-size: 1.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.lightbox-close:hover {
  background: #8BAE66;
  color: white;
  transform: rotate(90deg);
}
</style>

<?php
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_validate();
  if (!empty($_FILES['image']['name'])) {
    $v = validate_image_upload($_FILES['image']);
    if ($v !== true) { 
      $msg = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> ' . e($v) . '</div>'; 
    } else {
      $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
      $dir = __DIR__ . '/../../assets/images/gallery/';
      if (!is_dir($dir)) { mkdir($dir, 0775, true); }
      $fname = 'gallery_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
      $dest = $dir . $fname;
      if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
        $path = 'assets/images/gallery/' . $fname;
        $stmt = mysqli_prepare($conn, 'INSERT INTO gallery(image_path) VALUES (?)');
        mysqli_stmt_bind_param($stmt, 's', $path);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $msg = '<div class="alert alert-success"><i class="bi bi-check-circle"></i> Image uploaded successfully!</div>';
      } else { 
        $msg = '<div class="alert alert-danger"><i class="bi bi-x-circle"></i> Failed to save image.</div>'; 
      }
    }
  } else { 
    $msg = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> Please choose an image.</div>'; 
  }
}

// Get gallery stats
$totalImages = 0;
try {
  $result = @mysqli_query($conn, 'SELECT COUNT(*) as count FROM gallery');
  if ($result) {
    $totalImages = mysqli_fetch_assoc($result)['count'];
  }
} catch (Exception $e) {}
?>

<div class="page-header">
  <h1>
    <i class="bi bi-images"></i>
    Gallery Management
  </h1>
  <p class="mb-0 mt-2" style="opacity: 0.9;">Upload and manage your restaurant's photo gallery</p>
</div>

<?php echo $msg; ?>

<!-- Upload Section -->
<div class="upload-card">
  <div class="upload-card-header">
    <h3><i class="bi bi-cloud-upload"></i> Upload New Image</h3>
  </div>
  <div class="upload-card-body">
    <form method="post" enctype="multipart/form-data" id="uploadForm">
      <?php csrf_input(); ?>
      
      <div class="upload-zone" id="uploadZone">
        <input type="file" name="image" id="imageInput" accept="image/*" style="display: none;" required>
        <div class="upload-icon">
          <i class="bi bi-cloud-arrow-up"></i>
        </div>
        <div class="upload-text">Click to browse or drag and drop</div>
        <div class="upload-hint">Supports: JPG, PNG, GIF (Max 5MB)</div>
      </div>
      
      <div class="preview-grid" id="previewGrid"></div>
      
      <div class="d-flex gap-2 mt-3">
        <button class="btn btn-brand-primary" type="submit" id="uploadBtn" disabled>
          <i class="bi bi-upload"></i> Upload Image
        </button>
        <a class="btn btn-secondary" href="/admin/dashboard.php">
          <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
      </div>
    </form>
  </div>
</div>

<!-- Gallery Section -->
<div class="gallery-card">
  <div class="upload-card-header d-flex justify-content-between align-items-center">
    <h3><i class="bi bi-collection"></i> Your Gallery</h3>
    <div class="input-group input-group-sm w-auto">
      <span class="input-group-text"><i class="bi bi-search"></i></span>
      <input type="text" class="form-control" id="searchInput" placeholder="Search images...">
    </div>
  </div>
  
  <div class="upload-card-body">
    <!-- Statistics -->
    <div class="gallery-stats">
      <div class="stat-mini">
        <div class="stat-mini-value"><?php echo $totalImages; ?></div>
        <div class="stat-mini-label">Total Images</div>
      </div>
    </div>
    
    <!-- Gallery Grid -->
    <div class="gallery-grid" id="galleryGrid">
      <?php 
      $res = @mysqli_query($conn, 'SELECT id, image_path FROM gallery ORDER BY id DESC');
      if ($res && mysqli_num_rows($res) > 0):
        while ($row = mysqli_fetch_assoc($res)): 
      ?>
        <div class="gallery-item" data-id="<?php echo e($row['id']); ?>">
          <span class="gallery-id">#<?php echo e($sn++); ?></span>
          <img src="<?php echo e('../../' . ltrim($row['image_path'],'/')); ?>" alt="Gallery Image <?php echo e($row['id']); ?>">
          <div class="gallery-overlay">
            <div class="gallery-actions">
              <button class="btn-view" onclick="viewImage('<?php echo e('../../' . ltrim($row['image_path'],'/')); ?>')">
                <i class="bi bi-eye"></i> View
              </button>
              <a class="btn-delete" 
                 href="/admin/gallery/delete.php?id=<?php echo e($row['id']); ?>" 
                 onclick="return confirm('Are you sure you want to delete this image?')">
                <i class="bi bi-trash"></i> Delete
              </a>
            </div>
          </div>
        </div>
      <?php 
        endwhile;
      else: 
      ?>
        <div class="empty-gallery" style="grid-column: 1/-1;">
          <i class="bi bi-image"></i>
          <h4>No Images Yet</h4>
          <p>Upload your first image to get started!</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Lightbox -->
<div class="lightbox" id="lightbox">
  <button class="lightbox-close" onclick="closeLightbox()">
    <i class="bi bi-x"></i>
  </button>
  <img src="" alt="Gallery Image" id="lightboxImg">
</div>

<script>
const uploadZone = document.getElementById('uploadZone');
const imageInput = document.getElementById('imageInput');
const previewGrid = document.getElementById('previewGrid');
const uploadBtn = document.getElementById('uploadBtn');
const searchInput = document.getElementById('searchInput');

// Click to upload
uploadZone.addEventListener('click', () => imageInput.click());

// File selection
imageInput.addEventListener('change', handleFiles);

// Drag and drop
uploadZone.addEventListener('dragover', (e) => {
  e.preventDefault();
  uploadZone.classList.add('dragover');
});

uploadZone.addEventListener('dragleave', () => {
  uploadZone.classList.remove('dragover');
});

uploadZone.addEventListener('drop', (e) => {
  e.preventDefault();
  uploadZone.classList.remove('dragover');
  const dt = new DataTransfer();
  dt.items.add(e.dataTransfer.files[0]);
  imageInput.files = dt.files;
  handleFiles();
});

function handleFiles() {
  previewGrid.innerHTML = '';
  const files = imageInput.files;
  
  if (files.length > 0) {
    uploadBtn.disabled = false;
    Array.from(files).forEach((file, index) => {
      const reader = new FileReader();
      reader.onload = (e) => {
        const previewItem = document.createElement('div');
        previewItem.className = 'preview-item';
        previewItem.innerHTML = `
          <img src="${e.target.result}" alt="Preview">
          <button class="remove-preview" onclick="clearPreview()">
            <i class="bi bi-x"></i>
          </button>
        `;
        previewGrid.appendChild(previewItem);
      };
      reader.readAsDataURL(file);
    });
  } else {
    uploadBtn.disabled = true;
  }
}

function clearPreview() {
  imageInput.value = '';
  previewGrid.innerHTML = '';
  uploadBtn.disabled = true;
}

// Search functionality
searchInput.addEventListener('keyup', function() {
  const searchTerm = this.value.toLowerCase();
  const items = document.querySelectorAll('.gallery-item');
  
  items.forEach(item => {
    const id = item.dataset.id;
    item.style.display = id.includes(searchTerm) ? '' : 'none';
  });
});

// Lightbox functions
function viewImage(src) {
  document.getElementById('lightboxImg').src = src;
  document.getElementById('lightbox').classList.add('active');
}

function closeLightbox() {
  document.getElementById('lightbox').classList.remove('active');
}

// Close lightbox on background click
document.getElementById('lightbox').addEventListener('click', function(e) {
  if (e.target === this) {
    closeLightbox();
  }
});

// Close lightbox with Escape key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeLightbox();
  }
});
</script>

<?php include'../includes/footer.php'; ?>
