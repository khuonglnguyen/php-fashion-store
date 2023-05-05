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
                        <h3>Sửa màu sắc, size</h3>
                        <div class="form">
                            <form action="<?= URL_ROOT . '/productManage/editColorSize' ?>" method="POST" enctype="multipart/form-data">
                                <p class="<?= $data['cssClass'] ?>"><?= isset($data['message']) ? $data['message'] : "" ?></p>
                                <input type="hidden" name="id" value="<?= $data['detail']['id'] ?>">
                                <label for="image">Hình ảnh</label>
                                <p>
                                    <img style="height: 300px;" src="<?= URL_ROOT . '/public/images/' . $data['detail']['image'] ?>" alt="">
                                </p>
                                <label for="image">Hình ảnh mới</label>
                                <input type="file" id="image" name="image">
                                <label for="color">Màu sắc</label>
                                <select name="colorId" id="color">
                                    <?php
                                    foreach ($data['colorList'] as $key => $value) {
                                        if ($value['id'] == $data['detail']['colorId']) { ?>
                                            <option selected value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                        <?php  } else { ?>
                                            <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                        <?php } ?>
                                    <?php }
                                    ?>
                                </select>
                                <label for="size">Size</label>
                                <select name="sizeId" id="size">
                                    <?php
                                    foreach ($data['sizeList'] as $key => $value) {
                                        if ($value['id'] == $data['detail']['sizeId']) { ?>
                                            <option selected value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                        <?php  } else { ?>
                                            <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                        <?php } ?>
                                    <?php }
                                    ?>
                                </select>
                                <label for="qty">Số lượng</label>
                                <input type="number" id="nqtyame" name="qty" required min="0" value="<?= $data['detail']['qty'] ?>">
                                <input type="submit" value="Lưu">
                            </form>
                        </div>
                    </div>
                </div>
            </section>

        </main>

    </div>
</body>

</html>