<?php
session_start();
require 'config.php';

$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['identifier'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>HR Portal — Login</title>
<style>
  :root{--accent:#0d9488;--muted:#666}
  *{box-sizing:border-box}
  body{font-family:system-ui,-apple-system,Segoe UI,Roboto,"Helvetica Neue",Arial;margin:0;background:#f4f6f8;color:#222;display:flex;align-items:center;justify-content:center;min-height:100vh;padding:20px}
  .card{width:100%;max-width:420px;background:#fff;border-radius:12px;box-shadow:0 8px 30px rgba(13,20,30,0.08);padding:28px}
  h1{margin:0 0 8px;font-size:1.35rem;color:var(--accent)}
  p.lead{margin:0 0 18px;color:var(--muted);font-size:0.95rem}
  label{display:block;font-size:0.88rem;margin-bottom:6px}
  input[type="text"],input[type="password"]{width:100%;padding:10px 12px;border:1px solid #e6e9ee;border-radius:8px;margin-bottom:12px;font-size:0.95rem}
  .row{display:flex;gap:10px;align-items:center}
  .btn{display:inline-block;padding:10px 14px;border-radius:8px;border:0;background:var(--accent);color:#fff;font-weight:600;cursor:pointer}
  .link{font-size:0.9rem;color:var(--accent);text-decoration:none}
  .muted{color:#80858a;font-size:0.9rem}
  .error{background:#fff1f0;border:1px solid #ffc1b3;color:#9b2c2c;padding:10px;border-radius:8px;margin-bottom:12px}
  .small{font-size:0.85rem}
  @media (max-width:420px){ .card{padding:18px} }
</style>
</head>
<body>
  <div class="card" role="main" aria-labelledby="loginHeading">
<h1 id="loginHeading">HR Portal Login</h1>
<p class="lead">Authorized HR staff only. Please sign in with your admin account.</p>


    <?php if ($error): ?>
      <div class="error" role="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form id="loginForm" method="POST" novalidate>
      <label for="identifier">Username</label>
      <input id="identifier" name="identifier" type="text" autocomplete="username" required>

      <label for="password">Password</label>
      <input id="password" name="password" type="password" autocomplete="current-password" required>

      <div style="display:flex;justify-content:space-between;align-items:center;margin:12px 0 18px;">
        <label class="row small"><input type="checkbox" name="remember" value="1"> Remember me</label>
        <a class="link" href="#">Forgot password?</a>
      </div>

      <button class="btn" type="submit">Sign in</button>
    </form>

    <p class="muted small" style="margin-top:16px">
  This portal is restricted to HR administrators. Contact system support if you need access.
</p>

  </div>

<script>
  document.getElementById('loginForm').addEventListener('submit', function(e){
    const id = document.getElementById('identifier').value.trim();
    const pw = document.getElementById('password').value.trim();
    if (!id || !pw) {
      e.preventDefault();
      alert('Please enter both your username and password.');
    }
  });
</script>
</body>
</html>
