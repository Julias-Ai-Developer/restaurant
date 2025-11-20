<?php require_once __DIR__ . '/../includes/auth.php';
$sn = 1;
?>
<?php include __DIR__ . '/../includes/header.php'; ?>

<style>
.brand-gradient {
  background: linear-gradient(135deg, #2F6232 0%, #8BAE66 100%);
  color: white;
}

.status-badge {
  padding: 0.4rem 0.8rem;
  border-radius: 20px;
  font-weight: 500;
  font-size: 0.85rem;
}

.status-pending {
  background-color: #ffc107;
  color: #000;
}

.status-approved {
  background-color: #8BAE66;
  color: white;
}

.status-declined {
  background-color: #dc3545;
  color: white;
}

.status-completed {
  background-color: #6c757d;
  color: white;
}

.table-search {
  min-width: 250px;
  border: 2px solid rgba(255,255,255,0.3);
}

.table-search:focus {
  border-color: rgba(255,255,255,0.6);
  box-shadow: 0 0 0 0.2rem rgba(255,255,255,0.1);
}

.input-group-text {
  background-color: rgba(255,255,255,0.2);
  border: 2px solid rgba(255,255,255,0.3);
  color: white;
}

.action-buttons {
  display: flex;
  gap: 0.3rem;
  flex-wrap: wrap;
}

.btn-brand {
  background-color: #2F6232;
  border-color: #2F6232;
  color: white;
}

.btn-brand:hover {
  background-color: #8BAE66;
  border-color: #8BAE66;
  color: white;
}

.card {
  border: none;
  box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}

.table-hover tbody tr:hover {
  background-color: rgba(139, 174, 102, 0.1);
}

.reservation-id {
  font-weight: 600;
  color: #2F6232;
}

.stats-card {
  background: linear-gradient(135deg, rgba(47, 98, 50, 0.1) 0%, rgba(139, 174, 102, 0.1) 100%);
  border-left: 4px solid #8BAE66;
  padding: 1rem;
  margin-bottom: 1.5rem;
}

.stat-number {
  font-size: 2rem;
  font-weight: bold;
  color: #2F6232;
}

.stat-label {
  color: #6c757d;
  font-size: 0.9rem;
}
</style>

<h1 class="mb-4">
  <i class="bi bi-calendar-check"></i> Reservations Management
</h1>

<?php
// Get statistics
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM reservations"))['count'];
$pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM reservations WHERE status='pending'"))['count'];
$approved = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM reservations WHERE status='approved'"))['count'];
$today = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM reservations WHERE date=CURDATE()"))['count'];
?>

<div class="row mb-4">
  <div class="col-md-3">
    <div class="stats-card">
      <div class="stat-number"><?php echo $total; ?></div>
      <div class="stat-label">Total Reservations</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stats-card">
      <div class="stat-number"><?php echo $pending; ?></div>
      <div class="stat-label">Pending</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stats-card">
      <div class="stat-number"><?php echo $approved; ?></div>
      <div class="stat-label">Approved</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stats-card">
      <div class="stat-number"><?php echo $today; ?></div>
      <div class="stat-label">Today</div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header brand-gradient d-flex justify-content-between align-items-center py-3">
    <h3 class="mb-0"><i class="bi bi-list-ul"></i> All Reservations</h3>
    <div class="input-group input-group-sm w-auto">
      <span class="input-group-text"><i class="bi bi-search"></i></span>
      <input type="text" class="form-control table-search" id="searchInput" placeholder="Search reservations...">
    </div>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table id="reservationsTable" class="table table-striped table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Contact</th>
            <th>People</th>
            <th>Date & Time</th>
            <th>Status</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $res = mysqli_query($conn, 'SELECT * FROM reservations ORDER BY date DESC, time DESC');
          while ($r = mysqli_fetch_assoc($res)): 
            $statusClass = 'status-' . strtolower($r['status']);
            $date = date('M d, Y', strtotime($r['date']));
            $time = date('g:i A', strtotime($r['time']));
          ?>
            <tr>
              <td><span class="reservation-id">#<?php echo e($sn++); ?></span></td>
              <td>
                <strong><?php echo e($r['name']); ?></strong>
              </td>
              <td>
                <div><i class="bi bi-telephone"></i> <?php echo e($r['phone']); ?></div>
                <div class="text-muted small"><i class="bi bi-envelope"></i> <?php echo e($r['email']); ?></div>
              </td>
              <td>
                <i class="bi bi-people-fill text-primary"></i> <?php echo e($r['number_of_people']); ?>
              </td>
              <td>
                <div><i class="bi bi-calendar3"></i> <?php echo $date; ?></div>
                <div class="text-muted small"><i class="bi bi-clock"></i> <?php echo $time; ?></div>
              </td>
              <td>
                <span class="status-badge <?php echo $statusClass; ?>">
                  <?php echo ucfirst(e($r['status'])); ?>
                </span>
              </td>
              <td>
                <div class="action-buttons justify-content-end">
                  <?php if ($r['status'] === 'pending'): ?>
                    <a class="btn btn-sm btn-success" href="../reservations/approve.php?id=<?php echo e($r['id']); ?>" title="Approve">
                      <i class="bi bi-check-circle"></i>
                    </a>
                    <a class="btn btn-sm btn-warning" href="../reservations/decline.php?id=<?php echo e($r['id']); ?>" title="Decline">
                      <i class="bi bi-x-circle"></i>
                    </a>
                  <?php endif; ?>
                  <a class="btn btn-sm btn-brand" href="../reservations/view.php?id=<?php echo e($r['id']); ?>" title="View Details">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a class="btn btn-sm btn-danger" href="../reservations/delete.php?id=<?php echo e($r['id']); ?>" 
                     onclick="return confirm('Are you sure you want to delete this reservation?')" title="Delete">
                    <i class="bi bi-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
// Real-time table search
document.getElementById('searchInput').addEventListener('keyup', function() {
  const searchTerm = this.value.toLowerCase();
  const rows = document.querySelectorAll('#reservationsTable tbody tr');
  
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(searchTerm) ? '' : 'none';
  });
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>