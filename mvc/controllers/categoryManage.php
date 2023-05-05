<?php

class categoryManage extends ControllerBase
{
    public function index($page = 1)
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }
        // Khởi tạo model
        $category = $this->model("categoryModel");
        $categoryList = ($category->getAllAdmin($page['page']))->fetch_all(MYSQLI_ASSOC);
        $countPaging = $category->getCountPaging(8);

        $this->view("admin/category", [
            "headTitle" => "Quản lý danh mục",
            "categoryList" => $categoryList,
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
            $category = $this->model("categoryModel");
            // Gọi hàm insert để thêm mới vào csdl
            $result = $category->insert($_POST['name']);
            if ($result) {
                $this->view("admin/addNewCategory", [
                    "headTitle" => "Quản lý danh mục",
                    "cssClass" => "success",
                    "message" => "Thêm mới thành công!",
                    "name" => $_POST['name']
                ]);
            } else {
                $this->view("admin/addNewCategory", [
                    "headTitle" => "Quản lý danh mục",
                    "cssClass" => "error",
                    "message" => "Lỗi, vui lòng thử lại sau!",
                    "name" => $_POST['name']
                ]);
            }
        } else {
            $this->view("admin/addNewCategory", [
                "headTitle" => "Thêm mới danh mục",
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
        $category = $this->model("categoryModel");
        // Gọi hàm getByIdAdmin
        $c = $category->getByIdAdmin($id);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Gọi hàm update
            $r = $category->update($_POST['id'], $_POST['name']);

            // Gọi hàm getByIdAdmin
            $new = $category->getByIdAdmin($_POST['id']);
            if ($r) {
                $this->view("admin/editCategory", [
                    "headTitle" => "Xem/Cập nhật danh mục",
                    "cssClass" => "success",
                    "message" => "Cập nhật thành công!",
                    "category" => $new->fetch_assoc()
                ]);
            } else {
                $this->view("admin/editCategory", [
                    "headTitle" => "Xem/Cập nhật danh mục",
                    "cssClass" => "error",
                    "message" => "Lỗi, vui lòng thử lại sau!",
                    "category" => $new->fetch_assoc()
                ]);
            }
        } else {
            $this->view("admin/editCategory", [
                "headTitle" => "Xem/Cập nhật danh mục",
                "cssClass" => "none",
                "category" => $c->fetch_assoc()
            ]);
        }
    }

    public function changeStatus($id)
    {
        $category = $this->model("categoryModel");
        $result = $category->changeStatus($id);
        if ($result) {
            $this->redirect("categoryManage");
        }
    }
}
