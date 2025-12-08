
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Đăng ký với lá rơi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= dirname($_SERVER['SCRIPT_NAME']) ?>/assets/auth.css">
<style>
  /* Small overrides to match the provided screenshots without removing existing styles */
  .form-box { max-width: 540px; padding: 48px; border-radius: 12px; }
  .form-box h2 { text-align: center; font-size: 28px; font-weight: 600; margin-bottom: 22px; }
  .form-control { background: #eaf3ff; border: 1px solid #dfeefc; border-radius: 8px; padding: 12px 14px; }
  .row .form-control, .col .form-select { height: calc(2.25rem + 12px); }
  .btn-warning { background: #ffc107 !important; border: none; color: #111; font-weight: 600; border-radius: 8px; padding: 10px 14px; }
  .form-box .text-center a { color: #0d6efd; text-decoration: underline; }
</style>
</head>
<body>

<div class="form-box">
  <h2>Đăng ký</h2>
  <form id="registerForm" method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?action=register">
    <div class="mb-4">
      <input type="text" class="form-control" placeholder="Tên đăng nhập" id="username" name="register-username" required>
    </div>
    <div class="mb-4">
      <input type="email" class="form-control" placeholder="Email" id="email" name="register-email" required>
    </div>
    <div class="row mb-4 g-2">
      <div class="col">
        <input type="tel" class="form-control" placeholder="Số điện thoại" id="phone" name="register-phone" required>
      </div>
      <div class="col">
        <select class="form-select" id="genre" name="register-genre" required>
          <option value="">Giới tính</option>
          <option value="Nam">Nam</option>
          <option value="Nữ">Nữ</option>
          <option value="Ẩn danh">Ẩn danh</option>
        </select>
      </div>
    </div>
    <div class="mb-4">
      <input type="password" class="form-control" placeholder="Mật khẩu" id="password" name="register-password" required>
    </div>
    <button type="submit" class="btn w-100 btn-warning">Đăng ký</button>
    <div class="text-center mt-3">
      <a href="?action=showLogin">Đã có tài khoản? Đăng nhập</a>
    </div>
  </form>
</div>

<script>
// danh sách nhiều lá cục bộ
const leafImages = [
  '/assets/image/la1.png',
  '/assets/image/la2.png',
  '/assets/image/la3.png',
  '/assets/image/la4.png'
];

const numLeaves = 30;
for (let i = 0; i < numLeaves; i++) {
    const leaf = document.createElement('div');
    leaf.className = 'leaf';
    const randomLeaf = leafImages[Math.floor(Math.random() * leafImages.length)];
    leaf.style.backgroundImage = `url(${randomLeaf})`;
    leaf.style.left = Math.random() * 100 + 'vw';
    leaf.style.animationDuration = 5 + Math.random() * 5 + 's';
    leaf.style.animationDelay = Math.random() * 5 + 's';
    leaf.style.setProperty('--x-move', (Math.random() * 700 - 150) + 'px');
    document.body.appendChild(leaf);
}

// Basic client-side validation before submit
(function(){
  const form = document.getElementById('registerForm');
  form.addEventListener('submit', function(e){
    const pwd = document.getElementById('password');
    const email = document.getElementById('email');
    if (!email.value.trim()) { alert('Vui lòng nhập email'); email.focus(); e.preventDefault(); return; }
    if (!pwd.value.trim()) { alert('Vui lòng nhập mật khẩu'); pwd.focus(); e.preventDefault(); return; }
    // submit normally; server will hash password with bcrypt
  });
})();
</script>
<script src="/controllers/auth/register.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
