<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
        <script src="/Tavantruog1702/mvc-oop-basic-duanmau/mvc-oop-basic-duanmau/assets/du_an.js"></script>
        <link rel="stylesheet" href="/Tavantruog1702/mvc-oop-basic-duanmau/mvc-oop-basic-duanmau/assets/du_an.css">
</head>
<body>
      <div class="custom-container">
    <div class="custom-form-area" id="formArea">
      <div class="custom-form-box custom-login">
        <h2>Đăng nhập</h2>
        <form method="POST">
          <input type="email" name="login-email" placeholder="Email đăng nhập">
          <input type="password" name="login-password" placeholder="Mật khẩu">
          <button type="submit" onclick="showRegisterNotice()" name="action" value="login">Đăng nhập</button>
        </form>   
      <?php if (!empty($error)): ?>
        <p style="color: red;"><?= $error ?></p>
      <?php endif; ?>
        <div class="custom-switch" onclick="switchToRegister()">Chưa có tài khoản? Đăng ký</div>
      </div>

      <div class="custom-form-box custom-register">
        <h2>Đăng ký</h2>
        <form method="POST">
          <input type="text" name="register-username" placeholder="Tên người dùng">
          <input type="email" name="register-email" placeholder="Email">
          <input type="password" name="register-password" placeholder="Mật khẩu">
          <button type="button" onclick="switchToLogin()">Tạo tài khoản</button>
        </form>
        <div class="custom-switch" onclick="switchToLogin()">Đã có tài khoản? Đăng nhập</div>
      </div>

      <div class="custom-overlay"><span></span></div>
    </div>
  </div>
</body>
</html>