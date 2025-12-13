<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hồ sơ người dùng</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body { background: #fef8e6; min-height: 100vh; padding: 20px 0; }
    .profile-card { max-width: 500px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.12); padding: 40px; }
    .profile-card h2 { text-align: center; font-size: 28px; margin-bottom: 30px; color: #3b2a0a; }
    .avatar-preview { width: 120px; height: 120px; border-radius: 50%; margin: 0 auto 20px; object-fit: cover; border: 3px solid #000000ff; }
    .form-control { background: #eaf3ff; border: 1px solid #dfeefc; border-radius: 8px; padding: 12px 14px; }
    .btn-primary { background: #ffc107 !important; color: #111; border: none; font-weight: 600; border-radius: 8px; }
    .btn-back { color: #0d6efd; text-decoration: none; }
    .alert { border-radius: 8px; }
  </style>
</head>
<body>

<div class="profile-card">
  <a href="?action=tour-list" class="btn-back"><i class="bi bi-arrow-left"></i> Quay lại</a>
  <h2>Hồ sơ người dùng</h2>

  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if ($user): ?>
    <div style="text-align: center; margin-bottom: 30px;">
      <?php $avatarUrl = $user['avatar'] ?? 'https://ui-avatars.com/api/?name=User&background=random'; ?>
      <img src="<?= htmlspecialchars($avatarUrl) ?>"
           alt="Avatar"
           class="avatar-preview"
           role="button"
           data-bs-toggle="modal"
           data-bs-target="#avatarModal"
           title="Click để xem ảnh lớn hơn"
           onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name=User&background=random'">
    </div>

    <div style="margin-bottom: 20px;">
      <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? '') ?></p>
      <p><strong>Tên:</strong> <?= htmlspecialchars($user['username'] ?? '') ?></p>
      <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($user['phone'] ?? '') ?></p>
      <p><strong>Giới tính:</strong> <?= htmlspecialchars($user['genre'] ?? '') ?></p>
      <p><strong>Vai trò:</strong> <?= htmlspecialchars($user['role'] ?? '') ?></p>
    </div>

    <form method="post" action="?action=profile">
      <div class="mb-3">
        <label class="form-label">Thay đổi Avatar (nhập URL ảnh)</label>
        <input type="url" name="avatar_url" class="form-control" placeholder="https://example.com/avatar.jpg" value="<?= htmlspecialchars($user['avatar'] ?? '') ?>">
      </div>
      <button type="submit" class="btn btn-primary w-100">💾 Cập nhật Avatar</button>
    </form>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Avatar Modal -->
<div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="avatarModalLabel">Ảnh đại diện</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img src="<?= htmlspecialchars($avatarUrl) ?>" alt="Avatar large" class="img-fluid rounded">
      </div>
    </div>
  </div>
</div>
</body>
</html>
