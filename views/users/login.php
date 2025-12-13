<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng nhập</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= dirname($_SERVER['SCRIPT_NAME']) ?>/assets/auth.css">
  <style>
    /* Visuals aligned with register page */
    body { background:#fef8e6; min-height:100vh; }
    .form-box{max-width:540px;margin:40px auto;padding:48px;background:#fff;border-radius:12px;box-shadow:0 6px 24px rgba(0,0,0,.12);position:relative;z-index:10}
    .form-box h2{ text-align:center; font-size:28px; margin-bottom:22px; }
    .form-control { background: #eaf3ff; border: 1px solid #dfeefc; border-radius:8px; padding:12px 14px; }
    .btn-warning { background:#ffc107 !important; color:#111; font-weight:600; border-radius:8px; }
    .leaf{position:absolute;top:-50px;width:35px;height:35px;background-size:cover;opacity:.8;animation-name:fall;animation-iteration-count:infinite;animation-timing-function:linear;z-index:1}
    @keyframes fall { 0%{ transform: translateX(0px) translateY(0px) rotate(0deg); opacity:0 } 10%{opacity:1} 100%{ transform: translateX(var(--x-move)) translateY(100vh) rotate(360deg); opacity:0.5 } }
  </style>
</head>
<body>

<div class="form-box">
  <h2>Đăng nhập</h2>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>
  <form id="loginForm" method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?action=login">
    <div class="mb-3">
      <input type="email" class="form-control" placeholder="Email" id="email" name="login-email" required>
    </div>
    <div class="mb-3">
      <input type="password" class="form-control" placeholder="Mật khẩu" id="password" name="login-password" required>
    </div>
    <button type="submit" class="btn w-100 btn-warning">Đăng nhập</button>
    <div class="text-center mt-3"><a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?action=showRegister">Chưa có tài khoản? Đăng kí</a></div>
  </form>
</div>

<!-- Leaves animation (decorative) -->
<script>
// danh sách nhiều lá cục bộ
const leafImages = [
  '<?= dirname($_SERVER['SCRIPT_NAME']) ?>/assets/image/la1.png',
  '<?= dirname($_SERVER['SCRIPT_NAME']) ?>/assets/image/la2.png',
  '<?= dirname($_SERVER['SCRIPT_NAME']) ?>/assets/image/la3.png',
  '<?= dirname($_SERVER['SCRIPT_NAME']) ?>/assets/image/la4.png'
];
const numLeaves = 20;
for (let i = 0; i < numLeaves; i++) {
    const leaf = document.createElement('div');
    leaf.className = 'leaf';
    const randomLeaf = leafImages[Math.floor(Math.random() * leafImages.length)];
    leaf.style.backgroundImage = `url(${randomLeaf})`;
    leaf.style.left = Math.random() * 100 + 'vw';
    leaf.style.animationDuration = 5 + Math.random() * 7 + 's';
    leaf.style.animationDelay = Math.random() * 5 + 's';
    leaf.style.setProperty('--x-move', (Math.random() * 1000 - 150) + 'px');
    document.body.appendChild(leaf);
}
</script>

<script>
// Basic client-side validation before submit
document.getElementById('loginForm').addEventListener('submit', function(e){
  const passEl = document.getElementById('password');
  if (!passEl.value.trim()) { alert('Vui lòng nhập mật khẩu'); passEl.focus(); e.preventDefault(); return; }
  // submit normally; server will verify with bcrypt
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>