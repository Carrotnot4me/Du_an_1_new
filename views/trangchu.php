<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/Tavantruog1702/mvc-oop-basic-duanmau/mvc-oop-basic-duanmau/assets/du_an.css">
</head>
<body>
    <div class="banner">
        <img src="/Tavantruog1702/mvc-oop-basic-duanmau/mvc-oop-basic-duanmau/assets/img/banner.png" alt="Banner" class="banner-img">
    </div>
    <div class="search-product">
    <form action="/Tavantruog1702/mvc-oop-basic-duanmau/mvc-oop-basic-duanmau/index.php" method="get">
        <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..." class="search-input">
        <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
    </form>
</div>
<div class="product-list">
        <?php foreach ($data as $value): ?>
    <div class="product-item">
            <p><strong><?= $value->product_id ?></strong></p>
            <p>Giá: <?= number_format($value->price) ?> VNĐ</p>
            <?php if($value->image_url): ?>
                <img src="<?= $value->image_url ?>" width="100" alt="">
            <?php endif; ?>
    </div>
        <?php endforeach; ?>
</div>
</body>
</html>