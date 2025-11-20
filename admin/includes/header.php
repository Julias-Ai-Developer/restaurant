<?php require_once __DIR__ . '/../../config.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - GreenLeaf</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
  
  <style>
    .topbar {
      background: linear-gradient(135deg, #2F6232 0%, #8BAE66 100%);
      color: white;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      font-size: 0.9rem;
    }
    
    .topbar a {
      color: rgba(255, 255, 255, 0.9);
      text-decoration: none;
      transition: all 0.3s ease;
      padding: 0.3rem 0.6rem;
      border-radius: 4px;
    }
    
    .topbar a:hover {
      color: white;
      background: rgba(255, 255, 255, 0.2);
    }
    
    .topbar i {
      margin-right: 0.3rem;
    }
    
    .admin-navbar {
      background: linear-gradient(180deg, #1a3a1d 0%, #2F6232 100%);
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
      padding: 0.8rem 0;
    }
    
    .navbar-brand {
      font-weight: 700;
      font-size: 1.4rem;
      color: white !important;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .brand-icon {
      width: 35px;
      height: 35px;
      background: #8BAE66;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #2F6232;
      font-size: 1.2rem;
    }
    
    .nav-link {
      color: rgba(255, 255, 255, 0.85) !important;
      font-weight: 500;
      padding: 0.6rem 1rem !important;
      margin: 0 0.2rem;
      border-radius: 6px;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 0.4rem;
    }
    
    .nav-link:hover {
      background: rgba(139, 174, 102, 0.2);
      color: #8BAE66 !important;
      transform: translateY(-2px);
    }
    
    .nav-link.active {
      background: #8BAE66;
      color: #2F6232 !important;
    }
    
    .navbar-toggler {
      border-color: rgba(255, 255, 255, 0.3);
    }
    
    .navbar-toggler:focus {
      box-shadow: 0 0 0 0.2rem rgba(139, 174, 102, 0.5);
    }
    .admin-navbar .navbar-toggler i {
      color: #fff;
    }
    
    .admin-badge {
      background: rgba(139, 174, 102, 0.3);
      padding: 0.3rem 0.8rem;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 500;
    }
    
    .notification-badge {
      position: relative;
    }
    
    .notification-badge::after {
      content: attr(data-count);
      position: absolute;
      top: -8px;
      right: -8px;
      background: #dc3545;
      color: white;
      border-radius: 50%;
      width: 18px;
      height: 18px;
      font-size: 0.7rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
    }
    
    .quick-stats {
      display: flex;
      gap: 1.5rem;
      align-items: center;
    }
    
    .quick-stat {
      display: flex;
      align-items: center;
      gap: 0.4rem;
      font-size: 0.85rem;
    }
    
    .quick-stat-number {
      background: rgba(139, 174, 102, 0.3);
      padding: 0.2rem 0.5rem;
      border-radius: 12px;
      font-weight: 600;
    }
    
    @media (max-width: 991px) {
      .navbar-nav {
        padding: 1rem 0;
        gap: 0.3rem;
      }
      
      .quick-stats {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        margin-top: 0.5rem;
      }
    }
  </style>
</head>
<body class="admin-ui">
  <!-- Top Info Bar -->
  <div class="topbar py-2">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3 flex-wrap">
          <div class="admin-badge">
            <i class="bi bi-person-circle"></i>
            <strong><?php echo e($_SESSION['admin_name'] ?? 'Admin'); ?></strong>
          </div>
          
          <?php
          // Get quick stats for the top bar
          $pendingReservations = mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT COUNT(*) as count FROM reservations WHERE status='pending'")
          )['count'] ?? 0;
          
          $todayReservations = mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT COUNT(*) as count FROM reservations WHERE date=CURDATE()")
          )['count'] ?? 0;
          ?>
          
          <div class="quick-stats d-none d-lg-flex">
            <div class="quick-stat">
              <i class="bi bi-clock-history"></i>
              <span>Pending: <span class="quick-stat-number"><?php echo $pendingReservations; ?></span></span>
            </div>
            <div class="quick-stat">
              <i class="bi bi-calendar-event"></i>
              <span>Today: <span class="quick-stat-number"><?php echo $todayReservations; ?></span></span>
            </div>
          </div>
        </div>
        
        <div class="d-flex gap-2 align-items-center">
          <a href="/restaurant" target="_blank">
            <i class="bi bi-box-arrow-up-right"></i> View Site
          </a>
          <a href="logout.php">
            <i class="bi bi-box-arrow-right"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark admin-navbar">
    <div class="container">
      <a class="navbar-brand" href="dashboard.php">
        <div class="brand-icon">
          <i class="bi bi-leaf"></i>
        </div>
        <span>GreenLeaf Admin</span>
      </a>
      
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
        <i class="bi bi-list fs-2"></i>
      </button>
      
      <div id="adminNav" class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === '/main/' ? 'active' : ''; ?>" href="../main/dashboard.php">
              <i class="bi bi-speedometer2"></i>
              <span>Dashboard</span>
            </a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/reservations/view.php') !== false ? 'active' : ''; ?> notification-badge" 
               href="../reservations/view.php" 
               data-count="<?php echo $pendingReservations > 0 ? $pendingReservations : ''; ?>">
              <i class="bi bi-calendar-check"></i>
              <span>Reservations</span>
            </a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/menu/') !== false ? 'active' : ''; ?>" href="../menu/add.php">
              <i class="bi bi-card-list"></i>
              <span>Menu</span>
            </a>
          </li>
          
          <!-- <li class="nav-item">
            <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/orders/') !== false ? 'active' : ''; ?>" href="orders/">
              <i class="bi bi-basket"></i>
              <span>Orders</span>
            </a>
          </li> -->
          
          <li class="nav-item">
            <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/gallery/') !== false ? 'active' : ''; ?>" href="../gallery/upload.php">
              <i class="bi bi-images"></i>
              <span>Gallery</span>
            </a>
          </li>
          
          <li class="nav-item d-lg-none">
            <a class="nav-link" href="logout.php">
              <i class="bi bi-box-arrow-right"></i>
              <span>Logout</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container py-4">