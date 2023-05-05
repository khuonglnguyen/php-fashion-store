<?php

class sizeManage extends ControllerBase
{
    public function index($page = 1)
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }
        // Khởi tạo model
        $size = $this->model("sizeModel");
        $sizeList = ($size->getAllAdmin($page['page']))->fetch_all(MYSQLI_ASSOC);
        $countPaging = $size->getCountPaging(8);

        $this->view("admin/size", [
            "headTitle" => "Quản lý Size",
            "sizeList" => $sizeList,
            'countPaging'=>$countPaging
        ]);
    }

    public function add()
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Khởi tạo model
            $size = $this->model("sizeModel");
            // Gọi hàm insert để thêm mới vào csdl
            $result = $size->insert($_POST['name']);
            if ($result) {
                $this->view("admin/addNewSize", [
                    "headTitle" => "Thêm mới Size",
                    "cssClass" => "success",
                    "message" => "Thêm mới thành công!",
                    "name" => $_POST['name']
                ]);
            } else {
                $this->view("admin/addNewSize", [
                    "headTitle" => "Thêm mới Size",
                    "cssClass" => "error",
                    "message" => "Lỗi, vui lòng thử lại sau!",
                    "name" => $_POST['name']
                ]);
            }
        } else {
            $this->view("admin/addNewSize", [
                "headTitle" => "Thêm mới Size",
                "cssClass" => "none",
            ]);
        }
    }

    public function edit($id = "")
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }
        
        // Khởi tạo models
        $size = $this->model("sizeModel");
        // Gọi hàm getByIdAdmin
        $c = $size->getById($id);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Gọi hàm update
            $r = $size->update($_POST['id'], $_POST['name']);

            // Gọi hàm getByIdAdmin
            $new = $size->getById($_POST['id']);
            if ($r) {
                $this->view("admin/editSize", [
                    "headTitle" => "Xem/Cập nhật Size",
                    "cssClass" => "success",
                    "message" => "Cập nhật thành công!",
                    "size" => $new->fetch_assoc()
                ]);
            } else {
                $this->view("admin/editSize", [
                    "headTitle" => "Xem/Cập nhật Size",
                    "cssClass" => "error",
                    "message" => "Lỗi, vui lòng thử lại sau!",
                    "size" => $new->fetch_assoc()
                ]);
            }
        } else {
            $this->view("admin/editSize", [
                "headTitle" => "Xem/Cập nhật Size",
                "cssClass" => "none",
                "size" => $c->fetch_assoc()
            ]);
        }
    }

    public function delete($id)
    {
        $size = $this->model("sizeModel");
        $result = $size->delete($id);
        if ($result) {
            $this->redirect("sizeManage");
        }
    }
}
