<?php
require_once __DIR__ . '/../config.php';

$info = '';
$validToken = false;
$token = $_GET['token'] ?? '';

// Verify token
if ($token) {
  $stmt = mysqli_prepare($conn, 'SELECT pr.user_id, pr.expires_at, au.email FROM password_resets pr JOIN admin_users au ON pr.user_id = au.id WHERE pr.token = ? AND pr.expires_at > NOW() LIMIT 1');
  mysqli_stmt_bind_param($stmt, 's', $token);
  mysqli_stmt_execute($stmt);
  $r = mysqli_stmt_get_result($stmt);
  $resetData = mysqli_fetch_assoc($r);
  mysqli_stmt_close($stmt);
  
  if ($resetData) {
    $validToken = true;
  } else {
    $info = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i><div><strong>Invalid Link</strong><br>This reset link is invalid or has expired. Please request a new one.</div></div>';
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $validToken) {
  csrf_validate();
  $password = $_POST['password'] ?? '';
  $confirm = $_POST['confirm_password'] ?? '';
  
  if (strlen($password) >= 6 && $password === $confirm) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Update password
    $stmt = mysqli_prepare($conn, 'UPDATE admin_users SET password = ? WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'si', $hash, $resetData['user_id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    // Delete reset token
    $stmt = mysqli_prepare($conn, 'DELETE FROM password_resets WHERE user_id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $resetData['user_id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    $info = '<div class="alert alert-success"><i class="bi bi-check-circle-fill"></i><div><strong>Success!</strong><br>Your password has been updated successfully.</div></div>';
    $validToken = false;
  } else {
    $info = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i><div><strong>Error</strong><br>Passwords must be at least 6 characters and match.</div></div>';
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reset Password - GreenLeaf Admin</title>
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
    
    .reset-wrapper {
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
    
    .lock-icon {
      font-size: 4rem;
      background: linear-gradient(135deg, var(--bs-primary) 0%, var(--accent-green) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      animation: shake 3s ease-in-out infinite;
      filter: drop-shadow(0 4px 8px rgba(47, 98, 50, 0.2));
    }
    
    @keyframes shake {
      0%, 100% { transform: rotate(0deg); }
      10%, 30% { transform: rotate(-5deg); }
      20%, 40% { transform: rotate(5deg); }
      50% { transform: rotate(0deg); }
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
    
    .password-strength {
      margin-top: 0.5rem;
      height: 4px;
      background: #e0e0e0;
      border-radius: 2px;
      overflow: hidden;
    }
    
    .password-strength-bar {
      height: 100%;
      width: 0%;
      transition: all 0.3s ease;
      background: linear-gradient(90deg, #E67E22, #8FC84D, #2F6232);
    }
    
    .password-requirements {
      margin-top: 1rem;
      padding: 1rem;
      background: rgba(47, 98, 50, 0.05);
      border-radius: 10px;
      font-size: 0.85rem;
    }
    
    .password-requirements ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    
    .password-requirements li {
      padding: 0.3rem 0;
      color: #666;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .password-requirements li i {
      font-size: 0.9rem;
    }
    
    .password-requirements li.valid {
      color: var(--bs-success);
    }
    
    .password-requirements li.valid i {
      color: var(--bs-success);
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
    
    .btn-primary:disabled {
      opacity: 0.6;
      cursor: not-allowed;
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
      
      .lock-icon {
        font-size: 3rem;
      }
    }
  </style>
</head>
<body class="section-alt admin-ui">
  <div class="topbar py-2">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="d-flex gap-3 flex-wrap">
        <span><i class="bi bi-shield-lock-fill"></i> Reset Password</span>
      </div>
      <div><a href="/restaurant" class="text-white"><i class="bi bi-arrow-left"></i> Back to Site</a></div>
    </div>
  </div>
  
  <div class="reset-wrapper">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="admin-card">
            <div class="card-body">
              <?php if ($validToken): ?>
              <div class="admin-header">
                <div class="logo-container">
                  <i class="bi bi-shield-lock-fill lock-icon"></i>
                </div>
                <h1>Create New Password</h1>
                <p>Choose a strong password to secure your account. Make sure it's at least 6 characters long.</p>
              </div>
              
              <?php if ($info) echo $info; ?>
              
              <form method="post" id="resetForm">
                <?php csrf_input(); ?>
                
                <div class="form-floating position-relative">
                  <input type="password" name="password" class="form-control" id="newPassword" placeholder="New Password" minlength="6" required>
                  <label for="newPassword">New Password</label>
                  <button type="button" class="password-toggle" onclick="togglePassword('newPassword', this)">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
                
                <div class="password-strength">
                  <div class="password-strength-bar" id="strengthBar"></div>
                </div>
                
                <div class="password-requirements">
                  <ul id="requirements">
                    <li id="req-length"><i class="bi bi-circle"></i> At least 6 characters</li>
                    <li id="req-match"><i class="bi bi-circle"></i> Passwords match</li>
                  </ul>
                </div>
                
                <div class="form-floating position-relative">
                  <input type="password" name="confirm_password" class="form-control" id="confirmPassword" placeholder="Confirm Password" minlength="6" required>
                  <label for="confirmPassword">Confirm New Password</label>
                  <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword', this)">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
                
                <button class="btn btn-primary w-100" type="submit" id="submitBtn">
                  <i class="bi bi-shield-check"></i> Update Password
                </button>
              </form>
              
              <?php else: ?>
              <div class="admin-header">
                <?php if (strpos($info, 'success') !== false): ?>
                <div class="success-checkmark">
                  <i class="bi bi-check-lg"></i>
                </div>
                <h1>Password Updated!</h1>
                <p>Your password has been successfully updated. You can now log in with your new password.</p>
                <?php else: ?>
                <div class="logo-container">
                  <i class="bi bi-exclamation-triangle-fill" style="font-size: 4rem; color: var(--bs-warning);"></i>
                </div>
                <h1>Link Expired</h1>
                <p>This password reset link has expired or is invalid. Please request a new one.</p>
                <?php endif; ?>
              </div>
              
              <?php echo $info; ?>
              
              <div class="text-center">
                <a href="<?php echo strpos($info, 'success') !== false ? 'index.php' : 'forgot-password.php'; ?>" class="btn btn-outline-primary">
                  <i class="bi bi-<?php echo strpos($info, 'success') !== false ? 'box-arrow-in-right' : 'arrow-clockwise'; ?>"></i> 
                  <?php echo strpos($info, 'success') !== false ? 'Go to Login' : 'Request New Link'; ?>
                </a>
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
    
    const newPassword = document.getElementById('newPassword');
    const confirmPassword = document.getElementById('confirmPassword');
    const strengthBar = document.getElementById('strengthBar');
    const submitBtn = document.getElementById('submitBtn');
    const reqLength = document.getElementById('req-length');
    const reqMatch = document.getElementById('req-match');
    
    function checkPasswordStrength() {
      const password = newPassword.value;
      const confirm = confirmPassword.value;
      let strength = 0;
      
      // Check length
      if (password.length >= 6) {
        strength += 33;
        reqLength.classList.add('valid');
        reqLength.querySelector('i').className = 'bi bi-check-circle-fill';
      } else {
        reqLength.classList.remove('valid');
        reqLength.querySelector('i').className = 'bi bi-circle';
      }
      
      // Add more strength for longer passwords
      if (password.length >= 8) strength += 17;
      if (password.length >= 12) strength += 17;
      
      // Check for numbers
      if (/\d/.test(password)) strength += 17;
      
      // Check for special characters
      if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength += 16;
      
      strengthBar.style.width = strength + '%';
      
      // Check match
      if (confirm && password === confirm) {
        reqMatch.classList.add('valid');
        reqMatch.querySelector('i').className = 'bi bi-check-circle-fill';
      } else {
        reqMatch.classList.remove('valid');
        reqMatch.querySelector('i').className = 'bi bi-circle';
      }
      
      // Enable/disable submit button
      submitBtn.disabled = !(password.length >= 6 && confirm && password === confirm);
    }
    
    if (newPassword && confirmPassword) {
      newPassword.addEventListener('input', checkPasswordStrength);
      confirmPassword.addEventListener('input', checkPasswordStrength);
      
      // Initial check
      submitBtn.disabled = true;
    }
    
    // Add form submission animation
    const form = document.getElementById('resetForm');
    if (form) {
      form.addEventListener('submit', function(e) {
        const btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';
        btn.disabled = true;
      });
    }
  </script>
</body>
</html>