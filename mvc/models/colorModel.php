<?php
class colorModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new colorModel();
        }

        return self::$instance;
    }

    public function getAllClient()
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM color WHERE status=1";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getAllAdmin()
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM color ORDER BY name";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getById($Id)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM color WHERE Id='$Id'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function insert($name, $rgb)
    {
        $db = DB::getInstance();
        $sql = "INSERT INTO color VALUES (NULL, '$name','$rgb')";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function update($id, $name, $rgb)
    {
        $db = DB::getInstance();
        $sql = "UPDATE color SET name = '" . $name . "', rgb = '" . $rgb . "' WHERE id=" . $id;
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function delete($id)
    {
        $db = DB::getInstance();
        $sql = "DELETE FROM color WHERE id=" . $id;
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getCountPaging($row = 8)
    {
        $db = DB::getInstance();
        $sql = "SELECT COUNT(*) FROM color";
        $result = mysqli_query($db->con, $sql);
        if ($result) {
            $totalrow = intval((mysqli_fetch_all($result, MYSQLI_ASSOC)[0])['COUNT(*)']);
            return ceil($totalrow / $row);
        }
        return false;
    }
}
