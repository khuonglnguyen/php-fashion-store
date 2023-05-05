<?php
class sizeModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new sizeModel();
        }

        return self::$instance;
    }

    public function getAllClient()
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM size WHERE status=1";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getAllAdmin()
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM size ORDER BY name";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getById($Id)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM size WHERE Id='$Id'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function insert($name)
    {
        $db = DB::getInstance();
        $sql = "INSERT INTO size VALUES (NULL, '$name')";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function update($id, $name)
    {
        $db = DB::getInstance();
        $sql = "UPDATE size SET name = '" . $name . "' WHERE id=" . $id;
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function delete($id)
    {
        $db = DB::getInstance();
        $sql = "DELETE FROM size WHERE id=" . $id;
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getCountPaging($row = 8)
    {
        $db = DB::getInstance();
        $sql = "SELECT COUNT(*) FROM size";
        $result = mysqli_query($db->con, $sql);
        if ($result) {
            $totalrow = intval((mysqli_fetch_all($result, MYSQLI_ASSOC)[0])['COUNT(*)']);
            return ceil($totalrow / $row);
        }
        return false;
    }
}
