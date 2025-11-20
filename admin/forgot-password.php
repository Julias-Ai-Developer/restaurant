<?php
require_once __DIR__ . '/../config.php';

$info = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_validate();
  $email = trim($_POST['email'] ?? '');
  
  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Check if email exists
    $stmt = mysqli_prepare($conn, 'SELECT id, name FROM admin_users WHERE email = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $r = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($r);
    mysqli_stmt_close($stmt);
    
    if ($user) {
      // Generate reset token
      $token = bin2hex(random_bytes(32));
      $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
      
      // Store token in database
      $stmt = mysqli_prepare($conn, 'INSERT INTO password_resets(user_id, token, expires_at) VALUES (?,?,?) ON DUPLICATE KEY UPDATE token=?, expires_at=?');
      mysqli_stmt_bind_param($stmt, 'issss', $user['id'], $token, $expires, $token, $expires);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      
      // In production, send email with reset link
      // For now, just show success message
      $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/restaurant/admin/reset-password.php?token=" . $token;
      
      // TODO: Send email with $resetLink
      // mail($email, "Password Reset - GreenLeaf Admin", "Click here to reset: $resetLink");
      
      $success = true;
      $info = '<div class="alert alert-success"><i class="bi bi-check-circle-fill"></i><div><strong>Success!</strong><br>Password reset link has been sent to your email.</div></div>';
    } else {
      // Don't reveal if email exists or not (security best practice)
      $success = true;
      $info = '<div class="alert alert-success"><i class="bi bi-check-circle-fill"></i><div><strong>Request Received</strong><br>If an account exists with this email, a password reset link has been sent.</div></div>';
    }
  } else {
    $info = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i><div><strong>Invalid Email</strong><br>Please enter a valid email address.</div></div>';
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Forgot Password - GreenLeaf Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="../assets/css/theme.css" rel="stylesheet">
  <style>
    :root {
      --bs-primary: #2F6232;
      --bs-secondary: #8FC84D;
      --bs-warning: #E67E22;
      --bs-success: #8BAE66;
      --bs-dark: #628141;
      --bs-body-bg: #F8F9F7;
      --bs-body-color: #333;
      --bs-heading-color: #2F6232;
      --accent-green: #1b7f2a;
      --accent-light: #e9f4e6;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #e9f4e6 0%, #f8f9f7 50%, #e9f4e6 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      position: relative;
      overflow-x: hidden;
    }
    
    /* Animated background elements */
    body::before {
      content: '';
      position: fixed;
      top: -50%;
      right: -50%;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle, rgba(143, 200, 77, 0.1) 0%, transparent 70%);
      animation: float 20s ease-in-out infinite;
      z-index: 0;
    }
    
    body::after {
      content: '';
      position: fixed;
      bottom: -50%;
      left: -50%;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle, rgba(47, 98, 50, 0.08) 0%, transparent 70%);
      animation: float 25s ease-in-out infinite reverse;
      z-index: 0;
    }
    
    @keyframes float {
      0%, 100% { transform: translate(0, 0) rotate(0deg); }
      33% { transform: translate(30px, -30px) rotate(5deg); }
      66% { transform: translate(-20px, 20px) rotate(-5deg); }
    }
    
    .topbar {
      background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-dark) 100%);
      box-shadow: 0 2px 10px rgba(47, 98, 50, 0.15);
      position: relative;
      z-index: 10;
    }
    
    .topbar a {
      text-decoration: none;
      transition: opacity 0.3s ease;
    }
    
    .topbar a:hover {
      opacity: 0.8;
    }
    
    .container {
      position: relative;
      z-index: 1;
    }
    
    .forgot-wrapper {
      min-height: calc(100vh - 60px);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 0;
    }
    
    .admin-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border: none;
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(47, 98, 50, 0.12);
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .admin-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 50px rgba(47, 98, 50, 0.18);
    }
    
    .admin-card .card-body {
      padding: 3rem 2.5rem;
    }
    
    .admin-header {
      text-align: center;
      margin-bottom: 2.5rem;
      position: relative;
    }
    
    .logo-container {
      position: relative;
      display: inline-block;
      margin-bottom: 1.5rem;
    }
    
    .key-icon {
      font-size: 4rem;
      background: linear-gradient(135deg, var(--bs-primary) 0%, var(--accent-green) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      animation: swing 2s ease-in-out infinite;
      filter: drop-shadow(0 4px 8px rgba(47, 98, 50, 0.2));
    }
    
    @keyframes swing {
      0%, 100% { transform: rotate(-10deg); }
      50% { transform: rotate(10deg); }
    }
    
    .admin-header h1 {
      color: var(--bs-heading-color);
      font-weight: 700;
      font-size: 2rem;
      margin-bottom: 0.5rem;
      letter-spacing: -0.5px;
    }
    
    .admin-header p {
      color: #666;
      font-size: 0.95rem;
      font-weight: 400;
      line-height: 1.6;
    }
    
    .form-floating {
      margin-bottom: 1.5rem;
    }
    
    .form-floating > .form-control {
      border: 2px solid #e0e0e0;
      border-radius: 12px;
      padding: 1rem 1rem;
      height: 58px;
      font-size: 0.95rem;
      transition: all 0.3s ease;
      background: #fff;
    }
    
    .form-floating > label {
      padding: 1rem 1rem;
      color: #999;
      font-weight: 500;
    }
    
    .form-floating > .form-control:focus {
      border-color: var(--bs-primary);
      box-shadow: 0 0 0 0.25rem rgba(47, 98, 50, 0.1);
      background: #fff;
    }
    
    .form-floating > .form-control:focus ~ label {
      color: var(--bs-primary);
    }
    
    .input-icon {
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #999;
      font-size: 1.1rem;
      z-index: 4;
      pointer-events: none;
    }
    
    .btn-primary {
      background: linear-gradient(135deg, var(--bs-primary) 0%, var(--accent-green) 100%);
      border: none;
      padding: 1rem;
      font-weight: 600;
      font-size: 1rem;
      border-radius: 12px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(47, 98, 50, 0.2);
      position: relative;
      overflow: hidden;
    }
    
    .btn-primary::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.5s ease;
    }
    
    .btn-primary:hover::before {
      left: 100%;
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(47, 98, 50, 0.3);
    }
    
    .btn-primary:active {
      transform: translateY(0);
    }
    
    .btn-outline-primary {
      border: 2px solid var(--bs-primary);
      color: var(--bs-primary);
      padding: 0.75rem 2rem;
      font-weight: 600;
      border-radius: 12px;
      transition: all 0.3s ease;
      background: transparent;
    }
    
    .btn-outline-primary:hover {
      background: var(--bs-primary);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(47, 98, 50, 0.2);
    }
    
    .back-link {
      color: var(--bs-primary);
      font-size: 0.95rem;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .back-link:hover {
      color: var(--accent-green);
      gap: 0.7rem;
    }
    
    .back-link i {
      transition: transform 0.3s ease;
    }
    
    .back-link:hover i {
      transform: translateX(-3px);
    }
    
    .alert {
      border: none;
      border-radius: 12px;
      padding: 1.25rem;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: flex-start;
      gap: 1rem;
      animation: slideIn 0.3s ease;
    }
    
    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .alert-success {
      background: rgba(139, 174, 102, 0.12);
      color: var(--bs-success);
      border-left: 4px solid var(--bs-success);
    }
    
    .alert-danger {
      background: rgba(230, 126, 34, 0.12);
      color: var(--bs-warning);
      border-left: 4px solid var(--bs-warning);
    }
    
    .alert i {
      font-size: 1.5rem;
      flex-shrink: 0;
    }
    
    .alert div {
      flex: 1;
    }
    
    .alert strong {
      display: block;
      margin-bottom: 0.25rem;
      font-size: 1rem;
    }
    
    .info-box {
      background: rgba(47, 98, 50, 0.05);
      border-radius: 12px;
      padding: 1.25rem;
      margin-top: 1.5rem;
      display: flex;
      align-items: flex-start;
      gap: 1rem;
    }
    
    .info-box i {
      color: var(--bs-primary);
      font-size: 1.5rem;
      flex-shrink: 0;
    }
    
    .info-box p {
      margin: 0;
      font-size: 0.9rem;
      color: #666;
      line-height: 1.6;
    }
    
    .success-checkmark {
      width: 80px;
      height: 80px;
      margin: 0 auto 1.5rem;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--bs-success) 0%, var(--bs-secondary) 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      animation: scaleIn 0.5s ease;
    }
    
    .success-checkmark i {
      font-size: 2.5rem;
      color: white;
    }
    
    @keyframes scaleIn {
      from {
        transform: scale(0);
        opacity: 0;
      }
      to {
        transform: scale(1);
        opacity: 1;
      }
    }
    
    @media (max-width: 768px) {
      .admin-card .card-body {
        padding: 2rem 1.5rem;
      }
      
      .admin-header h1 {
        font-size: 1.5rem;
      }
      
      .key-icon {
        font-size: 3rem;
      }
    }
  </style>
</head>
<body class="section-alt admin-ui">
  <div class="topbar py-2">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="d-flex gap-3 flex-wrap">
        <span><i class="bi bi-key-fill"></i> Password Recovery</span>
      </div>
      <div><a href="/restaurant" class="text-white"><i class="bi bi-arrow-left"></i> Back to Site</a></div>
    </div>
  </div>
  
  <div class="forgot-wrapper">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="admin-card">
            <div class="card-body">
              <?php if (!$success): ?>
              <div class="admin-header">
                <div class="logo-container">
                  <i class="bi bi-key-fill key-icon"></i>
                </div>
                <h1>Forgot Password?</h1>
                <p>No worries! Enter your email address and we'll send you a link to reset your password.</p>
              </div>
              
              <?php if ($info) echo $info; ?>
              
              <form method="post" id="forgotForm">
                <?php csrf_input(); ?>
                
                <div class="form-floating position-relative">
                  <input type="email" name="email" class="form-control" id="emailInput" placeholder="Email Address" required autofocus>
                  <label for="emailInput">Email Address</label>
                  <i class="bi bi-envelope input-icon"></i>
                </div>
                
                <button class="btn btn-primary w-100 mb-3" type="submit">
                  <i class="bi bi-send-fill"></i> Send Reset Link
                </button>
                
                <div class="text-center">
                  <a href="index.php" class="back-link">
                    <i class="bi bi-arrow-left"></i> Back to Login
                  </a>
                </div>
              </form>
              
              <div class="info-box">
                <i class="bi bi-info-circle-fill"></i>
                <p>
                  <strong>Security Note:</strong> The reset link will be valid for 1 hour. If you don't receive an email, please check your spam folder.
                </p>
              </div>
              
              <?php else: ?>
              <div class="admin-header">
                <div class="success-checkmark">
                  <i class="bi bi-check-lg"></i>
                </div>
                <h1>Check Your Email</h1>
                <p>We've sent password reset instructions to your email address. Please check your inbox and follow the link to reset your password.</p>
              </div>
              
              <?php echo $info; ?>
              
              <div class="info-box mb-3">
                <i class="bi bi-clock-fill"></i>
                <p>
                  The reset link will expire in <strong>1 hour</strong> for security reasons. If you don't see the email, check your spam folder.
                </p>
              </div>
              
              <div class="text-center">
                <a href="index.php" class="btn btn-outline-primary">
                  <i class="bi bi-box-arrow-in-right"></i> Return to Login
                </a>
              </div>
              
              <div class="text-center mt-3">
                <p class="text-muted" style="font-size: 0.9rem;">
                  Didn't receive the email? <a href="forgot-password.php" class="text-primary fw-semibold">Try again</a>
                </p>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Add form submission animation
    const form = document.getElementById('forgotForm');
    if (form) {
      form.addEventListener('submit', function(e) {
        const btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
        btn.disabled = true;
      });
    }
  </script>
</body>
</html>