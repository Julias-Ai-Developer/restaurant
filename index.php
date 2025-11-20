<?php require_once __DIR__ . '/config.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GreenLeaf Restaurant</title>
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
          <li class="nav-item"><a class="nav-link" href="menu.php">Menu</a></li>
          <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
          <li class="nav-item"><a class="nav-link" href="reservation.php">Reservation</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <header class="hero-alt">
    <div class="hero-bg visible" style="background-image:url('assets/images/backgrounds (1).jpg')"></div>
    <div class="hero-bg" style="background-image:url('assets/images/backgrounds (2).jpg')"></div>
    <div class="container hero-content">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <h1 class="hero-title">Welcome to GreenLeaf</h1>
          <p class="hero-desc">Our seasonal dishes celebrate local farms and fresh flavors. It’s designed to nourish and delight with plant-forward cooking.</p>
          <a href="menu.php" class="btn btn-success btn-lg">View menu</a>
        </div>
        <div class="col-lg-6 position-relative d-none d-lg-block">
          <img src="assets/images/foods/foods (3).jpg" class="hero-art" alt="Featured dish">
        </div>
      </div>
    </div>
    <svg class="hero-wave" viewBox="0 0 1440 80" preserveAspectRatio="none"><path fill="#fff" d="M0,32 C240,80 480,0 720,32 C960,64 1200,48 1440,16 L1440,80 L0,80 Z"></path></svg>
  </header>

  <section class="section-alt py-5">
    <div class="container">
      <div class="row align-items-center g-4">
        <div class="col-md-6">
          <div class="about-subtitle">Grown with nature</div>
          <h2 class="mb-3">About GreenLeaf</h2>
          <p class="mb-2">GreenLeaf was founded to celebrate fresh, seasonal produce and the craft of plant-forward cooking. With a love for local farms and simple, honest flavors, we create dishes that nourish the body and bring people together.</p>
          <p class="mb-3">From small plates to hearty mains, our menu evolves with the harvest. Guided by care and creativity, we blend technique with sustainability to serve food that feels good.</p>
          <a href="menu.php" class="btn btn-success">Read More <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="col-md-6">
          <div class="d-flex gap-3">
            <div class="about-card flex-fill"><img src="assets/images/foods/foods (7).jpg" alt="Promo"></div>
            <div class="about-card flex-fill"><img src="assets/images/foods/foods (8).jpg" alt="Promo"></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section-muted py-5">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Featured Dishes</h2>
        <a href="menu.php" class="btn btn-primary">View Full Menu</a>
      </div>
      <div class="row g-4">
        <?php
        $items = [];
        $stmt = mysqli_prepare($conn, 'SELECT id, name, description, price, image, category FROM menu_items ORDER BY id DESC LIMIT 4');
        if ($stmt && mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($res)) { $items[] = $row; }
            mysqli_stmt_close($stmt);
        }
        function unit_from_desc($d){ $u='—'; if (preg_match('/(\d+\s*(?:g|ml|kg|L))/i', $d, $m)) { $u = strtoupper($m[1]); } return $u; }
        if (!$items) {
            echo '<p>No featured items yet. Check back soon.</p>';
        }
        foreach ($items as $m): ?>
          <div class="col-md-3">
            <div class="menu-tile h-100">
              <div class="price-label">UGX <?php echo e(number_format((float)$m['price'], 0)); ?></div>
              <div class="menu-image">
                <?php if (!empty($m['image'])): ?>
                  <img src="<?php echo e(ltrim($m['image'], '/')); ?>" alt="<?php echo e($m['name']); ?>">
                <?php endif; ?>
                <div class="pack-bar d-flex justify-content-between align-items-center">
                  <span>Pack: <?php echo e($m['category']); ?> | Unit: <?php echo e(unit_from_desc($m['description'])); ?></span>
                  <a href="menu.php" class="btn btn-sm btn-light">View</a>
                </div>
              </div>
              <div class="card-body">
                <h5 class="menu-title mb-1"><?php echo e($m['name']); ?></h5>
                <p class="card-text mt-1 text-muted"><?php echo e($m['description']); ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="feature-strip py-4">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-3">
          <div class="feature-item"><i class="bi bi-heart-fill"></i><div><div class="fw-semibold">Rooted in Passion</div><div>Founded to celebrate vibrant, wholesome food.</div></div></div>
        </div>
        <div class="col-md-3">
          <div class="feature-item"><i class="bi bi-recycle"></i><div><div class="fw-semibold">Sustainable Dining</div><div>Seasonal menus with ethical sourcing.</div></div></div>
        </div>
        <div class="col-md-3">
          <div class="feature-item"><i class="bi bi-shield-check"></i><div><div class="fw-semibold">Quality & Hygiene</div><div>Safe, fresh ingredients prepared carefully.</div></div></div>
        </div>
        <div class="col-md-3">
          <div class="feature-item"><i class="bi bi-people"></i><div><div class="fw-semibold">Community-Focused</div><div>Welcoming space for family and friends.</div></div></div>
        </div>
      </div>
    </div>
  </section>

  <!-- <section class="section-alt py-5">
    <div class="container">
      <h2 class="mb-3">Opening Hours</h2>
      <ul class="list-unstyled hours-list">
        <li><span>Mon–Fri</span><span>11:00 – 22:00</span></li>
        <li><span>Saturday</span><span>10:00 – 23:00</span></li>
        <li><span>Sunday</span><span>10:00 – 21:00</span></li>
      </ul>
    </div>
  </section> -->

  <section class="section-alt py-5">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Chef’s Picks</h2>
        <a href="menu.php" class="btn btn-outline-success">See all dishes</a>
      </div>
      <div class="row g-4">
        <?php
        $picks = [];
        $stmt2 = mysqli_prepare($conn, "SELECT id, name, description, price, image FROM menu_items WHERE category='Mains' ORDER BY id DESC LIMIT 4");
        if ($stmt2 && mysqli_stmt_execute($stmt2)) {
          $res2 = mysqli_stmt_get_result($stmt2);
          while ($row = mysqli_fetch_assoc($res2)) { $picks[] = $row; }
          mysqli_stmt_close($stmt2);
        }
        if (!$picks) { echo '<p>No chef picks yet. Explore our full menu.</p>'; }
        foreach ($picks as $m): ?>
          <div class="col-md-3">
            <div class="card h-100">
              <?php if (!empty($m['image'])): ?>
                <img src="<?php echo e(ltrim($m['image'], '/')); ?>" class="card-img-top" alt="<?php echo e($m['name']); ?>">
              <?php endif; ?>
              <div class="card-body">
                <h5 class="card-title mb-1"><?php echo e($m['name']); ?></h5>
                <p class="card-text"><?php echo e($m['description']); ?></p>
              </div>
              <div class="card-footer d-flex justify-content-between align-items-center">
                <span class="fw-bold">$<?php echo e(number_format((float)$m['price'], 2)); ?></span>
                <a href="reservation.php" class="btn btn-sm btn-success">Order at table</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>


  <section class="section-muted py-5">
    <div class="container">
      <h2 class="mb-4">What Guests Say</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="p-4 bg-white rounded shadow-sm">
            <div class="mb-2 text-success"><i class="bi bi-chat-quote-fill"></i></div>
            <p class="mb-2">Fresh flavors and warm service. The salads taste like the garden.</p>
            <div class="small text-secondary">— Amina</div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-4 bg-white rounded shadow-sm">
            <div class="mb-2 text-success"><i class="bi bi-chat-quote-fill"></i></div>
            <p class="mb-2">Loved the seasonal menu. Every visit feels different and special.</p>
            <div class="small text-secondary">— Daniel</div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-4 bg-white rounded shadow-sm">
            <div class="mb-2 text-success"><i class="bi bi-chat-quote-fill"></i></div>
            <p class="mb-2">Cozy atmosphere and great vegetarian options. Highly recommend.</p>
            <div class="small text-secondary">— Grace</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section-alt py-5">
    <div class="container">
      <div class="row g-4 align-items-center">
        <div class="col-md-6">
          <h2 class="mb-3">Visit Us</h2>
          <p>Find us in the heart of the city. We welcome walk-ins and reservations. Catering for events is available on request.</p>
          <ul class="list-unstyled">
            <li class="mb-2"><i class="bi bi-geo-alt text-success"></i> 123 Garden Ave, City</li>
            <li class="mb-2"><i class="bi bi-telephone text-success"></i> (555) 123-4567</li>
            <li class="mb-2"><i class="bi bi-envelope text-success"></i> hello@greenleaf.example</li>
          </ul>
          <a href="reservation.php" class="btn btn-success">Reserve now</a>
        </div>
        <div class="col-md-6">
          <div class="row g-3">
            <div class="col-6"><div class="border rounded p-3 h-100 text-center"><i class="bi bi-bag-check fs-2 text-success"></i><div class="mt-2">Catering</div></div></div>
            <div class="col-6"><div class="border rounded p-3 h-100 text-center"><i class="bi bi-cup-straw fs-2 text-success"></i><div class="mt-2">Drinks bar</div></div></div>
            <div class="col-6"><div class="border rounded p-3 h-100 text-center"><i class="bi bi-wifi fs-2 text-success"></i><div class="mt-2">Free Wi‑Fi</div></div></div>
            <div class="col-6"><div class="border rounded p-3 h-100 text-center"><i class="bi bi-people fs-2 text-success"></i><div class="mt-2">Family friendly</div></div></div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="feature-strip py-4">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-3">
          <div class="feature-item"><i class="bi bi-heart-fill"></i><div><div class="fw-semibold">Rooted in Passion</div><div>Founded to celebrate vibrant, wholesome food.</div></div></div>
        </div>
        <div class="col-md-3">
          <div class="feature-item"><i class="bi bi-recycle"></i><div><div class="fw-semibold">Sustainable Dining</div><div>Seasonal menus with ethical sourcing.</div></div></div>
        </div>
        <div class="col-md-3">
          <div class="feature-item"><i class="bi bi-shield-check"></i><div><div class="fw-semibold">Quality & Hygiene</div><div>Safe, fresh ingredients prepared carefully.</div></div></div>
        </div>
        <div class="col-md-3">
          <div class="feature-item"><i class="bi bi-people"></i><div><div class="fw-semibold">Community-Focused</div><div>Welcoming space for family and friends.</div></div></div>
        </div>
      </div>
    </div>
  </section>
<?php include'includes/footer.php'; ?>
