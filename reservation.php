<?php require_once __DIR__ . '/config.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reservation - GreenLeaf</title>
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
          <li class="nav-item"><a class="nav-link active" href="reservation.php">Reservation</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <section class="page-hero" style="background-image:url('assets/images/backgrounds (5).jpg')">
    <div class="container">
      <h1 class="title">Reservation</h1>
      <div class="crumbs"><a href="index.php">Home</a> — Reservation</div>
    </div>
  </section>

  <section class="section-alt py-5">
    <div class="container form-styled">
      <h1 class="mb-4">Reserve a Table</h1>
      <?php
      $message = '';
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_validate();
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $people = (int)($_POST['number_of_people'] ?? 0);
        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '';
        $notes = trim($_POST['notes'] ?? '');

        if ($name === '' || $phone === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $people < 1 || $date === '' || $time === '') {
          $message = '<div class="alert alert-danger">Please fill all required fields correctly.</div>';
        } else {
          $stmt = mysqli_prepare($conn, 'INSERT INTO reservations(name, phone, email, number_of_people, date, time, notes, status) VALUES (?,?,?,?,?,?,?,"pending")');
          mysqli_stmt_bind_param($stmt, 'sssssss', $name, $phone, $email, $people, $date, $time, $notes);
          if (mysqli_stmt_execute($stmt)) {
            $message = '<div class="alert alert-success">Reservation submitted! We will confirm shortly.</div>';
          } else {
            $message = '<div class="alert alert-danger">Error submitting reservation.</div>';
          }
          mysqli_stmt_close($stmt);
        }
      }
      echo $message;
      ?>

      <div class="row g-4 align-items-start">
        <div class="col-lg-8">
          <div class="card"><div class="card-body">
      <form method="post" class="row g-3">
        <?php csrf_input(); ?>
        <div class="col-md-6">
          <label class="form-label">Full Name</label>
          <div class="input-group"><span class="input-group-text"><i class="bi bi-person"></i></span><input type="text" name="name" class="form-control" required></div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Phone</label>
          <div class="input-group"><span class="input-group-text"><i class="bi bi-telephone"></i></span><input type="text" name="phone" class="form-control" required></div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <div class="input-group"><span class="input-group-text"><i class="bi bi-envelope"></i></span><input type="email" name="email" class="form-control" required></div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Number of People</label>
          <div class="input-group"><span class="input-group-text"><i class="bi bi-people"></i></span><input type="number" name="number_of_people" min="1" class="form-control" required></div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Date</label>
          <div class="input-group"><span class="input-group-text"><i class="bi bi-calendar"></i></span><input type="date" name="date" class="form-control" required></div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Time</label>
          <div class="input-group"><span class="input-group-text"><i class="bi bi-clock"></i></span><input type="time" name="time" class="form-control" required></div>
        </div>
        <div class="col-12">
          <label class="form-label">Special Notes</label>
          <textarea name="notes" class="form-control" rows="3" placeholder="Allergies, preferences, etc."></textarea>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-warning">Reserve Table</button>
        </div>
      </form>
          </div></div>
        </div>
        <div class="col-lg-4">
          <div class="card"><div class="card-body">
            <h5 class="mb-3">Dining Hours</h5>
            <ul class="list-unstyled mb-3">
              <li class="mb-2"><i class="bi bi-sun text-success"></i> Mon–Fri: 10:00 – 22:00</li>
              <li class="mb-2"><i class="bi bi-sun text-success"></i> Sat–Sun: 10:00 – 23:00</li>
            </ul>
            <h5 class="mb-3">Location</h5>
            <ul class="list-unstyled mb-0">
              <li class="mb-2"><i class="bi bi-geo-alt text-success"></i> 123 Garden Ave, City</li>
              <li class="mb-2"><i class="bi bi-telephone text-success"></i> (555) 123-4567</li>
              <li class="mb-2"><i class="bi bi-envelope text-success"></i> hello@greenleaf.example</li>
            </ul>
          </div></div>
        </div>
      </div>
    </div>
  </section>

 <?php include'includes/footer.php'; ?>
