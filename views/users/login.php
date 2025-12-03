<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/auth.css"> 
</head>
<body>

<div class="form-box">
    <h2>Đăng nhập Admin</h2>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="index.php?action=login_submit" method="POST">
        <div class="mb-4">
            <input type="email" class="form-control" placeholder="Email" id="email" name="email" required>
        </div>
        <div class="mb-5">
            <input type="password" class="form-control" placeholder="Mật khẩu" id="password" name="password" required>
        </div>
        <button type="submit" class="btn w-100 btn-warning">Đăng nhập</button>
        <div class="text-center mt-3">
            <a href="index.php?action=register_admin">Chưa có tài khoản? Đăng kí</a>
        </div>
    </form>
</div>

<script>
const leafImages = [

    'assets/img/la1.png',
    'assets/img/la2.png',
    'assets/img/la3.png',
    'assets/img/la4.png'
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
    leaf.style.setProperty('--x-move', (Math.random() * 1000 - 150) + 'px');
    document.body.appendChild(leaf);
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>