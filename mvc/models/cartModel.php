<?php
class cartModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new cartModel();
        }

        return self::$instance;
    }

    public function getByUserId($userId)
    {
        $db = DB::getInstance();
        $sql = "SELECT c.id, c.productId, c.productName, c.productPrice, c.quantity, p.image, c.size, c.color FROM cart c JOIN products p ON c.productId = p.id WHERE userId='$userId'";
        $result = mysqli_query($db->con, $sql);
        if ($result) {
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $cartArray = [];
            foreach ($data as $key => $value) {
                $cartArray[$value['id']] = array(
                    "cartId" => $value['id'],
                    "productId" => $value['productId'],
                    "size" => $value['size'],
                    "color" => $value['color'],
                    "productName" => $value['productName'],
                    "image" => $value['image'],
                    "quantity" => $value['quantity'],
                    "productPrice" => $value['productPrice']
                );
            }
        }
        return $cartArray;
    }

    public function check($userId, $productId, $color, $size)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM cart WHERE color = '$color' AND size = '$size' AND productId='$productId' AND userId = $userId";
        $result = mysqli_query($db->con, $sql);
        if (mysqli_num_rows($result) > 0) {
            return true;
        }
        return false;
    }

    public function getTotalQuantitycart($userId)
    {
        $db = DB::getInstance();
        $sql = "SELECT SUM(quantity) as total FROM cart WHERE userId='$userId'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getTotalPrice($userId)
    {
        $db = DB::getInstance();
        $sql = "SELECT SUM(quantity * productPrice) as total FROM cart WHERE userId='$userId'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function add($userId, $item, $color, $size)
    {
        $db = DB::getInstance();
        $sql = "INSERT INTO `cart`(`id`, `productId`, `productName`, `productPrice`, `quantity`, `userId`,`color`,`size`) VALUES (NULL,'" . $item['id'] . "','" . $item['name'] . "','" . $item['promotionPrice'] . "',1,'" . $userId . "','" . $color . "','" . $size . "')";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function updateQuanity($userId, $item, $productId)
    {
        $db = DB::getInstance();
        $sql = "UPDATE `cart` SET `quantity`= quantity + 1 WHERE productId='" . $item[$productId]['productId'] . "' AND userId=$userId";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function editQuanity($cartId, $qty)
    {
        $db = DB::getInstance();
        $sql = "UPDATE `cart` SET `quantity`= '" . $qty . "' WHERE id='" . $cartId . "'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function remove($cartId)
    {
        $db = DB::getInstance();
        $sql = "DELETE FROM `cart` WHERE id=$cartId";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function deleteCart()
    {
        $db = DB::getInstance();
        $sqlDeleteCart = "DELETE FROM `cart` WHERE userId='" . $_SESSION['user_id'] . "'";
        mysqli_query($db->con, $sqlDeleteCart);
    }
}
