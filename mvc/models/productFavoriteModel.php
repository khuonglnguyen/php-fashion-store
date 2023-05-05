<?php
class productFavoriteModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new productFavoriteModel();
        }

        return self::$instance;
    }

    public function getByUserId($userId)
    {
        $db = DB::getInstance();
        $sql = "SELECT p.promotionPrice, p.originalPrice, p.image, p.soldCount, p.id, p.name FROM productfavorite a JOIN products p ON a.productId=p.id WHERE userId='$userId'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function add($productId)
    {
        $db = DB::getInstance();
        //Check exstis
        $sqlCheck = "SELECT * FROM productfavorite WHERE userId='" . $_SESSION['user_id'] . "' AND productId=" . $productId;
        $resultCheck = mysqli_query($db->con, $sqlCheck);
        if (mysqli_num_rows($resultCheck) > 0) {
            return false;
        } else {
            $sql = "INSERT INTO `productfavorite`(`id`, `productId`, `userId`) VALUES (NULL," . $productId . "," . $_SESSION['user_id'] . ")";
            $result = mysqli_query($db->con, $sql);
            return $result;
        }
    }

    public function checkByUserId($productId)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM productfavorite WHERE userId='" . $_SESSION['user_id'] . "' AND productId=" . $productId;
        $result = mysqli_query($db->con, $sql);
        if (mysqli_num_rows($result) > 0) {
            return true;
        }
        return false;
    }

    public function remove($userId)
    {
        $db = DB::getInstance();
        $sql = "DELETE FROM productfavorite WHERE userId=" . $userId;
        $result = mysqli_query($db->con, $sql);
        if (mysqli_num_rows($result) > 0) {
            return true;
        }
        return false;
    }
}
