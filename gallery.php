<?php require_once __DIR__ . '/config.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gallery - GreenLeaf</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="assets/css/theme.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">
</head>
<body>
  <div class="topbar py-2">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="d-flex gap-3 flex-wrap">
        <span><i class="bi bi-clock"></i> Mon–Sun: 10:00 – 22:00</span>
        <span><i class="bi bi-geo-alt"></i> 123 Garden Ave, City</span>
        <span><i class="bi bi-telephone"></i> (555) 123-4567</span>
        <span><i class="bi bi-envelope"></i> hello@greenleaf.example</span>
      </div>
      <div class="d-flex gap-2">
        <a href="#"><i class="bi bi-facebook"></i></a>
        <a href="#"><i class="bi bi-twitter-x"></i></a>
        <a href="#"><i class="bi bi-instagram"></i></a>
      </div>
    </div>
  </div>
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">GreenLeaf</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation"><i class="bi bi-list fs-2"></i></button>
      <div id="nav" class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="menu.php">Menu</a></li>
          <li class="nav-item"><a class="nav-link active" href="gallery.php">Gallery</a></li>
          <li class="nav-item"><a class="nav-link" href="reservation.php">Reservation</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <section class="page-hero" style="background-image:url('assets/images/backgrounds (4).jpg')">
    <div class="container">
      <h1 class="title">Gallery</h1>
      <div class="crumbs"><a href="index.php">Home</a> — Gallery</div>
    </div>
  </section>

  <section class="section-alt py-5">
    <div class="container">
      <h1 class="mb-4">Gallery</h1>
      <div class="input-group input-group-sm w-auto mb-3">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input type="text" class="form-control gallery-search-public" placeholder="Search images">
      </div>
      <div class="row g-3">
        <?php
        $images = [];
        $stmt = mysqli_prepare($conn, 'SELECT id, image_path FROM gallery ORDER BY id DESC');
        if ($stmt && mysqli_stmt_execute($stmt)) {
          $res = mysqli_stmt_get_result($stmt);
          while ($row = mysqli_fetch_assoc($res)) { $images[] = $row; }
          mysqli_stmt_close($stmt);
        }
        if (!$images) { echo '<p>No gallery images yet.</p>'; }
        foreach ($images as $img): ?>
          <div class="col-6 col-md-3">
            <a href="<?php echo e(ltrim($img['image_path'],'/')); ?>" class="glightbox" data-gallery="gallery">
              <img src="<?php echo e(ltrim($img['image_path'],'/')); ?>" class="img-fluid rounded" alt="Gallery image">
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

<?php include'includes/footer.php'; ?>
