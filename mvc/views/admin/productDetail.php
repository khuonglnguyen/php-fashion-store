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
                        <a class="button right" href="<?= URL_ROOT . '/productManage/addColorSize/' . $data['product']['id'] ?>">Thêm mới chi tiết</a>
                        <h3>Chi tiết sản phẩm: <?= $data['product']['name'] ?></h3>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên màu</th>
                                        <th>Màu sắc</th>
                                        <th>Size</th>
                                        <th>Số lượng</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 0;
                                    foreach ($data['productDetailList'] as $key => $value) {
                                    ?>
                                        <tr>
                                            <td><?= ++$count ?></td>
                                            <td><?= $value['colorName'] ?></td>
                                            <td style="color: <?= $value['rgb'] ?>; font-size: 30px;">█</td>
                                            <td><?= $value['sizeName'] ?></td>
                                            <td><?= $value['qty'] ?></td>
                                            <td>
                                                <a class="button-red" href="<?= URL_ROOT . '/productManage/deleteDetail/' . $value['id'] ?>">Xóa</a>
                                                <a class="button-normal" href="<?= URL_ROOT . '/productManage/editColorSize/' . $value['id'] ?>">Sửa</a>
                                            </td>
                                        </tr>
                                    <?php }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>

</html>