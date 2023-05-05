<?php require APP_ROOT . '/views/admin/inc/head.php'; ?>

<body>
    <?php require APP_ROOT . '/views/admin/inc/sidebar.php'; ?>

    <div class="main-content">
        <header>
            <div class="search-wrapper">
                <span class="ti-search"></span>
                <input type="search" placeholder="Search">
            </div>

            <div class="social-icons">
                <span class="ti-bell"></span>
                <span class="ti-comment"></span>
                <div></div>
            </div>
        </header>
        <main>
            <section class="recent">
                <div class="activity-grid">
                    <div class="activity-card">
                        <h3>Thêm mới màu sắc, size</h3>
                        <div class="form">
                            <form action="<?= URL_ROOT . '/productManage/addColorSize' ?>" method="POST" enctype="multipart/form-data">
                                <p class="<?= $data['cssClass'] ?>"><?= isset($data['message']) ? $data['message'] : "" ?></p>
                                <input type="hidden" name="productId" value="<?= $data['product']['id'] ?>">
                                <label for="name">Tên sản phẩm</label>
                                <input type="text" id="name" name="name" required readonly value="<?= isset($data['product']['name']) ? $data['product']['name'] : "" ?>">
                                <label for="color">Màu sắc</label>
                                <select name="colorId" id="color">
                                    <?php
                                    foreach ($data['colorList'] as $key => $value) { ?>
                                        <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                    <?php }
                                    ?>
                                </select>
                                <label for="size">Size</label>
                                <select name="sizeId" id="size">
                                    <?php
                                    foreach ($data['sizeList'] as $key => $value) { ?>
                                        <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                    <?php }
                                    ?>
                                </select>
                                <label for="qty">Số lượng</label>
                                <input type="number" id="nqtyame" name="qty" required min="0">
                                <input type="submit" value="Lưu">
                                <a href="<?= URL_ROOT . '/productManage' ?>" class="back">Trở về</a>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

        </main>

    </div>
</body>

</html>