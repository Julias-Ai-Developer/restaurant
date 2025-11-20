<?php require_once __DIR__ . '/../includes/auth.php'; ?>
<?php include __DIR__ . '/../includes/header.php'; ?>


<style>
.dashboard-header {
  background: linear-gradient(135deg, #2F6232 0%, #8BAE66 100%);
  color: white;
  padding: 2rem;
  border-radius: 12px;
  margin-bottom: 2rem;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.dashboard-header h1 {
  font-size: 2.5rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.dashboard-header p {
  opacity: 0.9;
  font-size: 1.1rem;
}

.stat-card {
  background: white;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
  border-left: 4px solid #8BAE66;
  height: 100%;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.stat-card.primary {
  border-left-color: #8BAE66;
}

.stat-card.warning {
  border-left-color: #ffc107;
}

.stat-card.success {
  border-left-color: #28a745;
}

.stat-card.info {
  border-left-color: #17a2b8;
}

.stat-icon {
  width: 60px;
  height: 60px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.8rem;
  margin-bottom: 1rem;
}

.stat-card.primary .stat-icon {
  background: rgba(139, 174, 102, 0.15);
  color: #2F6232;
}

.stat-card.warning .stat-icon {
  background: rgba(255, 193, 7, 0.15);
  color: #ff8800;
}

.stat-card.success .stat-icon {
  background: rgba(40, 167, 69, 0.15);
  color: #28a745;
}

.stat-card.info .stat-icon {
  background: rgba(23, 162, 184, 0.15);
  color: #17a2b8;
}

.stat-label {
  color: #6c757d;
  font-size: 0.9rem;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 0.5rem;
}

.stat-value {
  font-size: 2.5rem;
  font-weight: 700;
  color: #2F6232;
  line-height: 1;
}

.stat-change {
  font-size: 0.85rem;
  margin-top: 0.5rem;
  display: flex;
  align-items: center;
  gap: 0.3rem;
}

.stat-change.positive {
  color: #28a745;
}

.stat-change.negative {
  color: #dc3545;
}

.section-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  overflow: hidden;
  margin-bottom: 2rem;
}

.section-header {
  background: linear-gradient(135deg, rgba(47, 98, 50, 0.05) 0%, rgba(139, 174, 102, 0.05) 100%);
  padding: 1.25rem 1.5rem;
  border-bottom: 2px solid #8BAE66;
  display: flex;
  justify-content: between;
  align-items: center;
}

.section-header h3 {
  margin: 0;
  color: #2F6232;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.section-body {
  padding: 1.5rem;
}

.quick-action-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.quick-action-card {
  background: linear-gradient(135deg, rgba(47, 98, 50, 0.05) 0%, rgba(139, 174, 102, 0.05) 100%);
  border: 2px solid rgba(139, 174, 102, 0.2);
  border-radius: 10px;
  padding: 1.25rem;
  text-align: center;
  text-decoration: none;
  color: #2F6232;
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
}

.quick-action-card:hover {
  background: #8BAE66;
  color: white;
  transform: translateY(-3px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.quick-action-card i {
  font-size: 2rem;
}

.quick-action-card span {
  font-weight: 600;
}

.status-badge {
  padding: 0.4rem 0.8rem;
  border-radius: 20px;
  font-weight: 500;
  font-size: 0.85rem;
}

.table-hover tbody tr {
  transition: all 0.2s ease;
}

.table-hover tbody tr:hover {
  background-color: rgba(139, 174, 102, 0.1);
  transform: scale(1.01);
}

.empty-state {
  text-align: center;
  padding: 3rem 1rem;
  color: #6c757d;
}

.empty-state i {
  font-size: 4rem;
  color: rgba(139, 174, 102, 0.3);
  margin-bottom: 1rem;
}

.view-all-btn {
  background: #2F6232;
  color: white;
  border: none;
  padding: 0.5rem 1.5rem;
  border-radius: 6px;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
}

.view-all-btn:hover {
  background: #8BAE66;
  color: white;
  transform: translateX(5px);
}

@media (max-width: 768px) {
  .dashboard-header h1 {
    font-size: 1.8rem;
  }
  
  .stat-value {
    font-size: 2rem;
  }
}
</style> 

<?php
// Initialize variables with error handling
$total_res = 0;
$pending_res = 0;
$approved_res = 0;
$total_menu = 0;
$total_gallery = 0;
$today_res = 0;
$recent = [];

try {
  $result = @mysqli_query($conn, 'SELECT COUNT(*) AS c FROM reservations');
  if ($result) {
    $total_res = (int)mysqli_fetch_assoc($result)['c'];
  }
} catch (Exception $e) {}

try {
  $result = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM reservations WHERE status='pending'");
  if ($result) {
    $pending_res = (int)mysqli_fetch_assoc($result)['c'];
  }
} catch (Exception $e) {}

try {
  $result = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM reservations WHERE status='approved'");
  if ($result) {
    $approved_res = (int)mysqli_fetch_assoc($result)['c'];
  }
} catch (Exception $e) {}

try {
  $result = @mysqli_query($conn, 'SELECT COUNT(*) AS c FROM menu_items');
  if ($result) {
    $total_menu = (int)mysqli_fetch_assoc($result)['c'];
  }
} catch (Exception $e) {}

try {
  $result = @mysqli_query($conn, 'SELECT COUNT(*) AS c FROM gallery');
  if ($result) {
    $total_gallery = (int)mysqli_fetch_assoc($result)['c'];
  }
} catch (Exception $e) {}

try {
  $result = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM reservations WHERE date=CURDATE()");
  if ($result) {
    $today_res = (int)mysqli_fetch_assoc($result)['c'];
  }
} catch (Exception $e) {}

try {
  $res = @mysqli_query($conn, 'SELECT id, name, email, phone, number_of_people, date, time, status FROM reservations ORDER BY id DESC LIMIT 5');
  if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
      $recent[] = $row;
    }
  }
} catch (Exception $e) {}
?>

<!-- Dashboard Header -->
<div class="dashboard-header">
  <h1><i class="bi bi-speedometer2"></i> Dashboard</h1>
  <p>Welcome back, <strong><?php echo e($_SESSION['admin_name'] ?? 'Admin'); ?></strong>! Here's what's happening today.</p>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
  <div class="col-md-3">
    <div class="stat-card primary">
      <div class="stat-icon">
        <i class="bi bi-calendar-check"></i>
      </div>
      <div class="stat-label">Total Reservations</div>
      <div class="stat-value"><?php echo e($total_res); ?></div>
      <div class="stat-change">
        <i class="bi bi-graph-up"></i>
        <span>All time bookings</span>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="stat-card warning">
      <div class="stat-icon">
        <i class="bi bi-clock-history"></i>
      </div>
      <div class="stat-label">Pending</div>
      <div class="stat-value"><?php echo e($pending_res); ?></div>
      <div class="stat-change">
        <i class="bi bi-exclamation-circle"></i>
        <span>Needs attention</span>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="stat-card success">
      <div class="stat-icon">
        <i class="bi bi-check-circle"></i>
      </div>
      <div class="stat-label">Approved</div>
      <div class="stat-value"><?php echo e($approved_res); ?></div>
      <div class="stat-change positive">
        <i class="bi bi-arrow-up"></i>
        <span>Confirmed bookings</span>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="stat-card info">
      <div class="stat-icon">
        <i class="bi bi-calendar-day"></i>
      </div>
      <div class="stat-label">Today</div>
      <div class="stat-value"><?php echo e($today_res); ?></div>
      <div class="stat-change">
        <i class="bi bi-calendar3"></i>
        <span>Today's bookings</span>
      </div>
    </div>
  </div>
</div>

<!-- Quick Actions -->
<div class="section-card">
  <div class="section-header">
    <h3><i class="bi bi-lightning-fill"></i> Quick Actions</h3>
  </div>
  <div class="section-body">
    <div class="quick-action-grid">
      <a href="/admin/menu/add.php" class="quick-action-card">
        <i class="bi bi-plus-circle"></i>
        <span>Add Menu Item</span>
      </a>
      <a href="/admin/reservations/" class="quick-action-card">
        <i class="bi bi-calendar-check"></i>
        <span>View Reservations</span>
      </a>
      <a href="/admin/gallery/upload.php" class="quick-action-card">
        <i class="bi bi-upload"></i>
        <span>Upload Photo</span>
      </a>
      <a href="/admin/orders/" class="quick-action-card">
        <i class="bi bi-basket"></i>
        <span>Manage Orders</span>
      </a>
    </div>
  </div>
</div>

<!-- Recent Bookings -->
<div class="section-card">
  <div class="section-header">
    <h3><i class="bi bi-clock-history"></i> Recent Bookings</h3>
    <a href="/admin/reservations/" class="view-all-btn">
      View All <i class="bi bi-arrow-right"></i>
    </a>
  </div>
  <div class="section-body p-0">
    <?php if (empty($recent)): ?>
      <div class="empty-state">
        <i class="bi bi-calendar-x"></i>
        <h4>No Bookings Yet</h4>
        <p>New reservations will appear here</p>
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Guest Name</th>
              <th>Contact</th>
              <th>Party Size</th>
              <th>Date & Time</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recent as $r): 
              $statusClass = $r['status'] === 'approved' ? 'success' : ($r['status'] === 'declined' ? 'danger' : 'warning');
              $date = date('M d, Y', strtotime($r['date']));
              $time = date('g:i A', strtotime($r['time']));
            ?>
              <tr>
                <td><strong>#<?php echo e($r['id']); ?></strong></td>
                <td>
                  <i class="bi bi-person-fill text-primary"></i>
                  <?php echo e($r['name']); ?>
                </td>
                <td>
                  <small class="text-muted">
                    <i class="bi bi-telephone"></i> <?php echo e($r['phone'] ?? 'N/A'); ?><br>
                    <i class="bi bi-envelope"></i> <?php echo e($r['email'] ?? 'N/A'); ?>
                  </small>
                </td>
                <td>
                  <i class="bi bi-people-fill text-info"></i>
                  <?php echo e($r['number_of_people']); ?> guests
                </td>
                <td>
                  <div><i class="bi bi-calendar3"></i> <?php echo $date; ?></div>
                  <small class="text-muted"><i class="bi bi-clock"></i> <?php echo $time; ?></small>
                </td>
                <td>
                  <span class="status-badge bg-<?php echo $statusClass; ?>">
                    <?php echo ucfirst(e($r['status'])); ?>
                  </span>
                </td>
                <td>
                  <a href="../reservations/view.php?id=<?php echo e($r['id']); ?>" 
                     class="btn btn-sm btn-outline-primary" 
                     title="View Details">
                    <i class="bi bi-eye"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- System Overview -->
<div class="row g-4">
  <div class="col-md-6">
    <div class="section-card">
      <div class="section-header">
        <h3><i class="bi bi-card-list"></i> Menu Overview</h3>
      </div>
      <div class="section-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-muted">Total Menu Items</span>
          <span class="stat-value" style="font-size: 2rem;"><?php echo e($total_menu); ?></span>
        </div>
        <a href="/admin/menu/" class="btn btn-outline-success w-100">
          <i class="bi bi-card-list"></i> Manage Menu
        </a>
      </div>
    </div>
  </div>
  
  <div class="col-md-6">
    <div class="section-card">
      <div class="section-header">
        <h3><i class="bi bi-images"></i> Gallery Overview</h3>
      </div>
      <div class="section-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-muted">Total Photos</span>
          <span class="stat-value" style="font-size: 2rem;"><?php echo e($total_gallery); ?></span>
        </div>
        <a href="/admin/gallery/" class="btn btn-outline-success w-100">
          <i class="bi bi-images"></i> Manage Gallery
        </a>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>