<?php require_once __DIR__ . '/../config.php';
$info = '';
$hasUsers = false;
$res = mysqli_query($conn, 'SELECT COUNT(*) AS c FROM admin_users');
if ($res) { $row = mysqli_fetch_assoc($res); $hasUsers = (int)$row['c'] > 0; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_validate();
  if (!$hasUsers && isset($_POST['setup'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($password) >= 6) {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = mysqli_prepare($conn, 'INSERT INTO admin_users(name,email,password) VALUES (?,?,?)');
      mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $hash);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      $hasUsers = true;
      $info = '<div class="alert alert-success"><i class="bi bi-check-circle"></i> Admin account created successfully! Please log in.</div>';
    } else {
      $info = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> Invalid setup data. Please check your entries.</div>';
    }
  } else {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = mysqli_prepare($conn, 'SELECT id, password, name FROM admin_users WHERE email = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $r = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($r);
    mysqli_stmt_close($stmt);
    if ($user && password_verify($password, $user['password'])) {
      $_SESSION['admin_id'] = $user['id'];
      $_SESSION['admin_name'] = $user['name'];
      header('Location: ../admin/main/dashboard.php');
      exit;
    } else {
      $info = '<div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> Invalid credentials. Please try again.</div>';
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login - GreenLeaf Admin</title>
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
    
    .login-wrapper {
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
    
    .shield-icon {
      font-size: 4rem;
      background: linear-gradient(135deg, var(--bs-primary) 0%, var(--accent-green) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      animation: pulse 2s ease-in-out infinite;
      filter: drop-shadow(0 4px 8px rgba(47, 98, 50, 0.2));
    }
    
    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
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
    }
    
    .setup-badge {
      display: inline-block;
      background: linear-gradient(135deg, var(--bs-secondary) 0%, var(--bs-success) 100%);
      color: white;
      padding: 0.4rem 1rem;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      margin-bottom: 1rem;
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
    
    .password-toggle {
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #999;
      cursor: pointer;
      z-index: 5;
      padding: 0.5rem;
      transition: color 0.3s ease;
    }
    
    .password-toggle:hover {
      color: var(--bs-primary);
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
    
    .forgot-password {
      color: var(--bs-primary);
      font-size: 0.9rem;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
    }
    
    .forgot-password:hover {
      color: var(--accent-green);
      gap: 0.5rem;
    }
    
    .divider {
      display: flex;
      align-items: center;
      text-align: center;
      margin: 1.5rem 0;
      color: #999;
      font-size: 0.85rem;
    }
    
    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      border-bottom: 1px solid #e0e0e0;
    }
    
    .divider span {
      padding: 0 1rem;
    }
    
    .alert {
      border: none;
      border-radius: 12px;
      padding: 1rem 1.25rem;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
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
      background: rgba(139, 174, 102, 0.1);
      color: var(--bs-success);
      border-left: 4px solid var(--bs-success);
    }
    
    .alert-danger {
      background: rgba(230, 126, 34, 0.1);
      color: var(--bs-warning);
      border-left: 4px solid var(--bs-warning);
    }
    
    .alert i {
      font-size: 1.25rem;
    }
    
    .remember-me {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.9rem;
      color: #666;
    }
    
    .form-check-input {
      width: 1.2rem;
      height: 1.2rem;
      border: 2px solid #ddd;
      cursor: pointer;
    }
    
    .form-check-input:checked {
      background-color: var(--bs-primary);
      border-color: var(--bs-primary);
    }
    
    .form-check-input:focus {
      box-shadow: 0 0 0 0.25rem rgba(47, 98, 50, 0.15);
    }
    
    .security-info {
      background: rgba(47, 98, 50, 0.05);
      border-radius: 12px;
      padding: 1rem;
      margin-top: 1.5rem;
      text-align: center;
    }
    
    .security-info i {
      color: var(--bs-primary);
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
    }
    
    .security-info p {
      margin: 0;
      font-size: 0.85rem;
      color: #666;
    }
    
    @media (max-width: 768px) {
      .admin-card .card-body {
        padding: 2rem 1.5rem;
      }
      
      .admin-header h1 {
        font-size: 1.5rem;
      }
      
      .shield-icon {
        font-size: 3rem;
      }
    }
  </style>
</head>
<body class="section-alt admin-ui">
  <div class="topbar py-2">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="d-flex gap-3 flex-wrap">
        <span><i class="bi bi-shield-lock"></i> Secure Admin Access</span>
      </div>
      <div><a href="/restaurant" class="text-white"><i class="bi bi-arrow-left"></i> Back to Site</a></div>
    </div>
  </div>
  
  <div class="login-wrapper">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="admin-card">
            <div class="card-body">
              <div class="admin-header">
                <div class="logo-container">
                  <i class="bi bi-shield-check shield-icon"></i>
                </div>
                <?php if (!$hasUsers): ?>
                  <span class="setup-badge"><i class="bi bi-stars"></i> Initial Setup</span>
                <?php endif; ?>
                <h1><?php echo !$hasUsers ? 'Welcome to GreenLeaf' : 'Welcome Back'; ?></h1>
                <p><?php echo !$hasUsers ? 'Set up your administrator account' : 'Sign in to access your dashboard'; ?></p>
              </div>
              
              <?php if ($info) echo $info; ?>

              <?php if (!$hasUsers): ?>
              <form method="post" id="setupForm">
                <?php csrf_input(); ?>
                <input type="hidden" name="setup" value="1">
                
                <div class="form-floating position-relative">
                  <input type="text" name="name" class="form-control" id="floatingName" placeholder="Full Name" required>
                  <label for="floatingName">Full Name</label>
                  <i class="bi bi-person input-icon"></i>
                </div>
                
                <div class="form-floating position-relative">
                  <input type="email" name="email" class="form-control" id="floatingEmail" placeholder="Email Address" required>
                  <label for="floatingEmail">Email Address</label>
                  <i class="bi bi-envelope input-icon"></i>
                </div>
                
                <div class="form-floating position-relative">
                  <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" minlength="6" required>
                  <label for="floatingPassword">Password (min. 6 characters)</label>
                  <button type="button" class="password-toggle" onclick="togglePassword('floatingPassword', this)">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
                
                <button class="btn btn-primary w-100" type="submit">
                  <i class="bi bi-rocket-takeoff"></i> Create Admin Account
                </button>
                
                <div class="security-info">
                  <i class="bi bi-shield-check"></i>
                  <p>Your password will be securely encrypted</p>
                </div>
              </form>
              
              <?php else: ?>
              <form method="post" id="loginForm">
                <?php csrf_input(); ?>
                
                <div class="form-floating position-relative">
                  <input type="email" name="email" class="form-control" id="loginEmail" placeholder="Email Address" required>
                  <label for="loginEmail">Email Address</label>
                  <i class="bi bi-envelope input-icon"></i>
                </div>
                
                <div class="form-floating position-relative">
                  <input type="password" name="password" class="form-control" id="loginPassword" placeholder="Password" required>
                  <label for="loginPassword">Password</label>
                  <button type="button" class="password-toggle" onclick="togglePassword('loginPassword', this)">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <div class="remember-me">
                    <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                  </div>
                  <a href="forgot-password.php" class="forgot-password">
                    Forgot Password? <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
                
                <button class="btn btn-primary w-100" type="submit">
                  <i class="bi bi-box-arrow-in-right"></i> Sign In Securely
                </button>
                
                <div class="security-info">
                  <i class="bi bi-shield-lock"></i>
                  <p>Protected by enterprise-grade encryption</p>
                </div>
              </form>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function togglePassword(inputId, button) {
      const input = document.getElementById(inputId);
      const icon = button.querySelector('i');
      
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
      }
    }
    
    // Add form submission animation
    document.querySelectorAll('form').forEach(form => {
      form.addEventListener('submit', function(e) {
        const btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
        btn.disabled = true;
      });
    });
  </script>
</body>
</html>