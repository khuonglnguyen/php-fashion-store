<?php
class productModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new productModel();
        }

        return self::$instance;
    }

    public function search($keyword)
    {
        $db = DB::getInstance();
        $sql = "";
        if (count(explode(" ", $keyword)) > 1) {
            $sql = "SELECT p.id, p.name, p.image, p.originalPrice, p.promotionPrice, p.soldCount as soldCount FROM products p WHERE MATCH(p.name) AGAINST ('$keyword') AND p.status=1";
        } else {
            $sql = "SELECT p.id, p.name, p.image, p.originalPrice, p.promotionPrice, p.soldCount as soldCount FROM products p WHERE p.name LIKE '" . $keyword . "%' AND p.status=1";
        }
        $result = mysqli_query($db->con, $sql);
        if (mysqli_num_rows($result) > 0) {
            return $result;
        }
        return false;
    }

    public function getProductSuggest($keyword, $id)
    {
        $db = DB::getInstance();
        $sql = "";
        if (count(explode(" ", $keyword)) > 1) {
            $sql = "SELECT p.id, p.name, p.image, p.originalPrice, p.promotionPrice, p.soldCount as soldCount FROM products p WHERE MATCH(p.name) AGAINST ('$keyword') AND p.status=1 AND p.id != $id LIMIT 4";
        } else {
            $sql = "SELECT p.id, p.name, p.image, p.originalPrice, p.promotionPrice, p.soldCount as soldCount FROM products p WHERE p.name LIKE '" . $keyword . "%' AND p.status=1 AND p.id != $id LIMIT 4";
        }
        $result = mysqli_query($db->con, $sql);
        if (mysqli_num_rows($result) > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return false;
    }

    public function getById($Id)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM products WHERE Id='$Id' AND status=1";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getAllSizeByProductId($Id, $color = "")
    {
        $db = DB::getInstance();
        if ($color) {
            $sql = "SELECT DISTINCT s.id, s.name FROM product_detail p JOIN size s ON p.sizeId = s.id JOIN color c ON p.colorId = c.id WHERE p.productId ='$Id' AND c.name = '$color' AND p.qty > 0";
        } else {
            $sql = "SELECT DISTINCT s.id, s.name FROM product_detail p JOIN size s ON p.sizeId = s.id WHERE p.productId ='$Id' AND p.qty > 0";
        }
        // var_dump($sql); die();
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getAllColorByProductId($Id)
    {
        $db = DB::getInstance();
        $sql = "SELECT DISTINCT c.id, c.name, c.rgb FROM product_detail p JOIN color c ON p.colorId = c.id WHERE p.productId ='$Id'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getByIdAdmin($Id)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM products WHERE Id='$Id'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getDetailById($Id)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM product_detail WHERE Id='$Id'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getByCateId($page = 1, $total = 8, $CateId)
    {
        if ($page <= 0) {
            $page = 1;
        }
        $tmp = ($page - 1) * $total;
        $db = DB::getInstance();
        $sql = "SELECT p.id, p.name, p.image, p.originalPrice, p.promotionPrice, p.soldCount as soldCount, SUM(d.qty) as qty FROM products p JOIN categories c ON p.cateId = c.id JOIN product_detail d ON p.id = d.productId WHERE p.cateId = $CateId AND p.status=1 AND c.status = 1 GROUP BY p.id order BY p.soldCount DESC LIMIT 4";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getByCateIdSinglePage($CateId, $Id)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM products WHERE cateId='$CateId' AND status=1 AND id != $Id ORDER BY soldCount DESC LIMIT 4";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getFeaturedproducts()
    {
        $db = DB::getInstance();
        $sql = "SELECT p.id, p.name, p.image, p.originalPrice, p.promotionPrice, p.soldCount as soldCount, SUM(d.qty) as qty FROM products p JOIN categories c ON p.cateId = c.id JOIN product_detail d ON p.id = d.productId WHERE p.status=1 AND c.status = 1 AND soldCount > 0 GROUP BY p.id order BY p.soldCount DESC LIMIT 4";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getNewproducts()
    {
        $db = DB::getInstance();
        $sql = "SELECT p.id, p.name, p.image, p.originalPrice, p.promotionPrice, p.soldCount as soldCount, SUM(d.qty) as qty FROM products p JOIN categories c ON p.cateId = c.id JOIN product_detail d ON p.id = d.productId WHERE p.status=1 AND c.status = 1 GROUP BY p.id order BY p.id DESC LIMIT 4";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getDiscountproducts()
    {
        $db = DB::getInstance();
        $sql = "SELECT p.id, p.name, p.image, p.originalPrice, p.promotionPrice, p.soldCount as soldCount, SUM(d.qty) as qty FROM products p JOIN categories c ON p.cateId = c.id JOIN product_detail d ON p.id = d.productId WHERE p.status=1 AND c.status = 1 AND p.promotionPrice < p.originalPrice GROUP BY p.id order BY soldCount DESC LIMIT 4";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getAllAdmin($page = 1, $total = 8)
    {
        if ($page <= 0) {
            $page = 1;
        }
        $tmp = ($page - 1) * $total;
        $db = DB::getInstance();
        $sql = "SELECT * FROM products LIMIT $tmp,$total";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getDetailByProductId($id)
    {
        $db = DB::getInstance();
        $sql = "SELECT p.id, c.name as colorName, c.rgb, s.name sizeName, p.qty FROM product_detail p JOIN color c ON p.colorId = c.id JOIN size s ON p.sizeId = s.id WHERE productId = $id ORDER BY s.name";
        $result = mysqli_query($db->con, $sql);
        if (mysqli_num_rows($result) > 0) {
            return $result;
        }
        return false;
    }

    public function checkQuantity($Id, $qty, $color, $size)
    {
        $db = DB::getInstance();
        $sql = "SELECT d.qty FROM products p JOIN product_detail d ON p.id = d.productId JOIN color c ON d.colorId = c.id JOIN size s ON d.sizeId = s.id WHERE c.name = '$color' AND s.name = '$size' AND status=1 AND p.Id='$Id'";
        $result = mysqli_query($db->con, $sql);
        $product = $result->fetch_assoc();
        if (intval($qty) > intval($product['qty'])) {
            return false;
        }
        return true;
    }

    public function updateQuantity($Id, $qty)
    {
        $db = DB::getInstance();
        $sql = "UPDATE products SET qty = qty - $qty WHERE id = $Id";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function changeStatus($Id)
    {
        $db = DB::getInstance();
        $sql = "UPDATE products SET status = !status WHERE Id='$Id'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function insert($product)
    {
        $db = DB::getInstance();
        // Check image and move to upload folder
        $file_name = $_FILES['image']['name'];
        $file_temp = $_FILES['image']['tmp_name'];

        $div = explode('.', $file_name);
        $file_ext = strtolower(end($div));
        $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
        $uploaded_image = APP_ROOT . "../../public/images/" . $unique_image;
        move_uploaded_file($file_temp, $uploaded_image);


        // Check image and move to upload folder
        if (!empty($_FILES['image2']['name'])) {
            $file_name = $_FILES['image2']['name'];
            $file_temp = $_FILES['image2']['tmp_name'];

            $div = explode('.', $file_name);
            $file_ext = strtolower(end($div));
            $unique_image2 = substr(md5(time() . '2'), 0, 10) . '.' . $file_ext;
            $uploaded_image2 = APP_ROOT . "../../public/images/" . $unique_image2;

            move_uploaded_file($file_temp, $uploaded_image2);
        }

        // Check image and move to upload folder
        if (!empty($_FILES['image3']['name'])) {
            $file_name = $_FILES['image3']['name'];
            $file_temp = $_FILES['image3']['tmp_name'];

            $div = explode('.', $file_name);
            $file_ext = strtolower(end($div));
            $unique_image3 = substr(md5(time() . '3'), 0, 10) . '.' . $file_ext;
            $uploaded_image3 = APP_ROOT . "../../public/images/" . $unique_image3;

            move_uploaded_file($file_temp, $uploaded_image3);
        }

        $sql = "INSERT INTO `products` (`id`, `name`, `originalPrice`, `promotionPrice`, `image`, `image2`, `image3`, `createdBy`, `createdDate`, `cateId`, `des`, `status`, `soldCount`) VALUES (NULL, '" . $product['name'] . "', " . $product['originalPrice'] . ", " . $product['promotionPrice'] . ", '" . $unique_image . "', '" . $unique_image2 . "', '" . $unique_image3 . "', " . $_SESSION['user_id'] . ", '" . date("y-m-d H:i:s") . "', " . $product['cateId'] . ", '" . $product['des'] . "', 1, 0)";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function update($product)
    {
        // Check image and move to upload folder
        if (!empty($_FILES['image']['name'])) {
            $file_name = $_FILES['image']['name'];
            $file_temp = $_FILES['image']['tmp_name'];

            $div = explode('.', $file_name);
            $file_ext = strtolower(end($div));
            $unique_image = substr(md5(time() . '1'), 0, 10) . '.' . $file_ext;
            $uploaded_image = APP_ROOT . "../../public/images/" . $unique_image;

            move_uploaded_file($file_temp, $uploaded_image);
        }


        // Check image and move to upload folder
        if (!empty($_FILES['image2']['name'])) {
            $file_name = $_FILES['image2']['name'];
            $file_temp = $_FILES['image2']['tmp_name'];

            $div = explode('.', $file_name);
            $file_ext = strtolower(end($div));
            $unique_image2 = substr(md5(time() . '2'), 0, 10) . '.' . $file_ext;
            $uploaded_image2 = APP_ROOT . "../../public/images/" . $unique_image2;

            move_uploaded_file($file_temp, $uploaded_image2);
        }

        // Check image and move to upload folder
        if (!empty($_FILES['image3']['name'])) {
            $file_name = $_FILES['image3']['name'];
            $file_temp = $_FILES['image3']['tmp_name'];

            $div = explode('.', $file_name);
            $file_ext = strtolower(end($div));
            $unique_image3 = substr(md5(time() . '3'), 0, 10) . '.' . $file_ext;
            $uploaded_image3 = APP_ROOT . "../../public/images/" . $unique_image3;

            move_uploaded_file($file_temp, $uploaded_image3);
        }

        $db = DB::getInstance();
        $sql = "UPDATE `products` SET name = '" . $_POST['name'] . "', `originalPrice` = " . $_POST['originalPrice'] . ", `promotionPrice` = " . $_POST['promotionPrice'];
        if (!empty($_FILES['image']['name'])) {
            $sql .=  ", `image` = '" . $unique_image . "'";
        }
        if (!empty($_FILES['image2']['name'])) {
            $sql .=  ", `image2` = '" . $unique_image2 . "'";
        }
        if (!empty($_FILES['image3']['name'])) {
            $sql .=  ", `image3` = '" . $unique_image3 . "'";
        }
        $sql .= ", `cateId` = " . $_POST['cateId'] . ", `des` = '" . $_POST['des'] . "' WHERE id = " . $_POST['id'] . "";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getCountPaging($row = 8)
    {
        $db = DB::getInstance();
        $sql = "SELECT COUNT(*) FROM products";
        $result = mysqli_query($db->con, $sql);
        if ($result) {
            $totalrow = intval((mysqli_fetch_all($result, MYSQLI_ASSOC)[0])['COUNT(*)']);
            return ceil($totalrow / $row);
        }
        return false;
    }

    public function getCountPagingByClient($cateId, $row = 8)
    {
        $db = DB::getInstance();
        $sql = "SELECT COUNT(*) FROM products WHERE cateId = $cateId AND status=1";
        $result = mysqli_query($db->con, $sql);
        if ($result) {
            $totalrow = intval((mysqli_fetch_all($result, MYSQLI_ASSOC)[0])['COUNT(*)']);
            return ceil($totalrow / $row);
        }
        return false;
    }

    public function insertColorSize()
    {
        $db = DB::getInstance();
        $sql = "INSERT INTO `product_detail`(`id`, `productId`, `sizeId`, `colorId`, `qty`) VALUES (NULL,'" . $_POST['productId'] . "','" . $_POST['sizeId'] . "','" . $_POST['colorId'] . "'," . $_POST['qty'] . ")";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function updateDetail()
    {
        // Check image and move to upload folder
        if (!empty($_FILES['image']['name'])) {
            $file_name = $_FILES['image']['name'];
            $file_temp = $_FILES['image']['tmp_name'];

            $div = explode('.', $file_name);
            $file_ext = strtolower(end($div));
            $unique_image = substr(md5(time() . '1'), 0, 10) . '.' . $file_ext;
            $uploaded_image = APP_ROOT . "../../public/images/" . $unique_image;

            move_uploaded_file($file_temp, $uploaded_image);
        }

        $db = DB::getInstance();
        $sql = "UPDATE `product_detail` SET sizeId = '" . $_POST['sizeId'] . "', `colorId` = " . $_POST['colorId'] . ", `qty` = " . $_POST['qty'];
        if (!empty($_FILES['image']['name'])) {
            $sql .=  ", `image` = '" . $unique_image . "'";
        }
        $sql .= " WHERE id = " . $_POST['id'] . "";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function deleteDetail($id)
    {
        $db = DB::getInstance();
        $sql = "DELETE FROM `product_detail` WHERE id = $id";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getSoldCountMonth()
    {
        $db = DB::getInstance();
        $sql = "SELECT SUM(p.soldCount) AS total, p.name FROM `orders` o JOIN order_details od ON o.id  JOIN products p ON od.productId = p.id WHERE MONTH(o.createdDate) = MONTH(NOW()) AND o.paymentStatus=1 GROUP BY p.id, MONTH(o.createdDate), YEAR(o.createdDate)";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }
}
