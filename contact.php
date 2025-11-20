<?php require_once __DIR__ . '/config.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contact - GreenLeaf</title>
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
          <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <section class="page-hero" style="background-image:url('assets/images/backgrounds (6).jpg')">
    <div class="container">
      <h1 class="title">Contact Us</h1>
      <div class="crumbs"><a href="index.php">Home</a> — Contact Us</div>
    </div>
  </section>

  <section class="section-alt py-5">
    <div class="container form-styled">
      <h1 class="mb-4">Contact Us</h1>
      <?php
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_validate();
        $cname = trim($_POST['cname'] ?? '');
        $cemail = trim($_POST['cemail'] ?? '');
        $cmsg = trim($_POST['cmsg'] ?? '');
        if ($cname && filter_var($cemail, FILTER_VALIDATE_EMAIL) && $cmsg) {
          echo '<div class="alert alert-success">Thanks for reaching out! We will respond soon.</div>';
        } else {
          echo '<div class="alert alert-danger">Please complete all fields correctly.</div>';
        }
      }
      ?>
      <div class="row g-4">
        <div class="col-md-6">
          <div class="card"><div class="card-body">
          <form method="post">
            <?php csrf_input(); ?>
            <div class="mb-3">
              <label class="form-label">Name</label>
              <div class="input-group"><span class="input-group-text"><i class="bi bi-person"></i></span><input type="text" name="cname" class="form-control" required></div>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <div class="input-group"><span class="input-group-text"><i class="bi bi-envelope"></i></span><input type="email" name="cemail" class="form-control" required></div>
            </div>
            <div class="mb-3">
              <label class="form-label">Message</label>
              <textarea name="cmsg" class="form-control" rows="4" required></textarea>
            </div>
            <button class="btn btn-primary" type="submit">Send</button>
          </form>
          </div></div>
        </div>
        <div class="col-md-6">
          <div class="ratio ratio-4x3">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434508451!2d144.96305771531637!3d-37.81627974202171!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zTWFwIFBsYWNlaG9sZGVy!5e0!3m2!1sen!2s!4v0000000000" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
          <div class="mt-3">
            <p><strong>Address:</strong> 123 Garden Ave, City</p>
            <p><strong>Phone:</strong> (555) 123-4567</p>
            <p><strong>Email:</strong> hello@greenleaf.example</p>
          </div>
        </div>
      </div>
    </div>
  </section>

 <?php include'includes/footer.php'; ?>
