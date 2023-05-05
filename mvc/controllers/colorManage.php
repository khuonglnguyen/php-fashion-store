<?php

class colorManage extends ControllerBase
{
    public function index($page = 1)
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }
        // Khởi tạo model
        $color = $this->model("colorModel");
        $colorList = ($color->getAllAdmin($page['page']))->fetch_all(MYSQLI_ASSOC);
        $countPaging = $color->getCountPaging(8);

        $this->view("admin/color", [
            "headTitle" => "Quản lý màu sắc",
            "colorList" => $colorList,
            'countPaging' => $countPaging
        ]);
    }

    public function add()
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Khởi tạo model
            $color = $this->model("colorModel");
            // Gọi hàm insert để thêm mới vào csdl
            $result = $color->insert($_POST['name'], $_POST['rgb']);
            if ($result) {
                $this->view("admin/addNewColor", [
                    "headTitle" => "Thêm mới màu sắc",
                    "cssClass" => "success",
                    "message" => "Thêm mới thành công!",
                    "name" => $_POST['name'],
                    "rgb" => $_POST['rgb']
                ]);
            } else {
                $this->view("admin/addNewColor", [
                    "headTitle" => "Thêm mới màu sắc",
                    "cssClass" => "error",
                    "message" => "Lỗi, vui lòng thử lại sau!",
                    "name" => $_POST['name'],
                    "rgb" => $_POST['rgb']
                ]);
            }
        } else {
            $this->view("admin/addNewColor", [
                "headTitle" => "Thêm mới màu sắc",
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
        $color = $this->model("colorModel");
        // Gọi hàm getByIdAdmin
        $c = $color->getById($id);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Gọi hàm update
            $r = $color->update($_POST['id'], $_POST['name'], $_POST['rgb']);

            // Gọi hàm getByIdAdmin
            $new = $color->getById($_POST['id']);
            if ($r) {
                $this->view("admin/editColor", [
                    "headTitle" => "Xem/Cập nhật Size",
                    "cssClass" => "success",
                    "message" => "Cập nhật thành công!",
                    "color" => $new->fetch_assoc()
                ]);
            } else {
                $this->view("admin/editColor", [
                    "headTitle" => "Xem/Cập nhật Size",
                    "cssClass" => "error",
                    "message" => "Lỗi, vui lòng thử lại sau!",
                    "color" => $new->fetch_assoc()
                ]);
            }
        } else {
            $this->view("admin/editColor", [
                "headTitle" => "Xem/Cập nhật Size",
                "cssClass" => "none",
                "color" => $c->fetch_assoc()
            ]);
        }
    }

    public function delete($id)
    {
        $color = $this->model("colorModel");
        $result = $color->delete($id);
        if ($result) {
            $this->redirect("colorManage");
        }
    }
}
