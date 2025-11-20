<?php require_once __DIR__ . '/config.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Menu - GreenLeaf</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="assets/css/theme.css" rel="stylesheet">
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
          <li class="nav-item"><a class="nav-link active" href="menu.php">Menu</a></li>
          <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
          <li class="nav-item"><a class="nav-link" href="reservation.php">Reservation</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <section class="page-hero" style="background-image:url('assets/images/backgrounds (4).jpg')">
    <div class="container">
      <h1 class="title">Menu</h1>
      <div class="crumbs"><a href="index.php">Home</a> — Menu</div>
    </div>
  </section>

  <section class="section-alt py-5">
    <div class="container">
      <h1 class="mb-4">Our Menu</h1>
      <?php
        $itemsByCat = [];
        $stmt = mysqli_prepare($conn, 'SELECT id, name, description, price, category, image FROM menu_items ORDER BY category, name');
        if ($stmt && mysqli_stmt_execute($stmt)) {
          $res = mysqli_stmt_get_result($stmt);
          while ($row = mysqli_fetch_assoc($res)) {
            $itemsByCat[$row['category']][] = $row;
          }
          mysqli_stmt_close($stmt);
        }
        $categories = ['Starters','Mains','Drinks','Desserts'];
        foreach ($categories as $cat):
          $list = $itemsByCat[$cat] ?? [];
      ?>
        <h3 class="mt-4"><?php echo e($cat); ?></h3>
        <div class="row g-4">
          <?php if (!$list): ?>
            <p>No items in this category yet.</p>
          <?php endif; ?>
          <?php foreach ($list as $m): ?>
            <div class="col-md-3">
              <div class="card h-100">
                <?php if (!empty($m['image'])): ?>
                  <img src="<?php echo e(ltrim($m['image'], '/')); ?>" class="card-img-top" alt="<?php echo e($m['name']); ?>">
                <?php endif; ?>
                <div class="card-body">
                  <h5 class="card-title mb-1"><?php echo e($m['name']); ?></h5>
                  <p class="card-text"><?php echo e($m['description']); ?></p>
                </div>
                <div class="card-footer d-flex justify-content-between">
                  <span class="fw-bold">$<?php echo e(number_format((float)$m['price'], 2)); ?></span>
                  <a href="reservation.php" class="btn btn-sm btn-warning">Reserve</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

<?php include'includes/footer.php'; ?>
