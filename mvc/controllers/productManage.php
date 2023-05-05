<?php

class productManage extends ControllerBase
{
    public function index($page = 1)
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        // khởi tạo model
        $product = $this->model("productModel");
        // Gọi hàm addAllAdmin
        $productList = ($product->getAllAdmin($page['page']))->fetch_all(MYSQLI_ASSOC);
        $countPaging = $product->getCountPaging(8);

        $this->view("admin/product", [
            "headTitle" => "Quản lý sản phẩm",
            "productList" => $productList,
            "countPaging" => $countPaging
        ]);
    }

    public function detail($id)
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }


        $product = $this->model("productModel");
        $result = $product->getDetailByProductId($id);
        $p = $product->getByIdAdmin($id)->fetch_assoc();
        $productDetailList = [];
        if ($result) {
            $productDetailList = ($product->getDetailByProductId($id))->fetch_all(MYSQLI_ASSOC);
        }

        $this->view("admin/productDetail", [
            "headTitle" => "Chi tiết: " . $p['name'],
            "product" => $p,
            "productDetailList" => $productDetailList
        ]);
    }

    public function editColorSize($id = "")
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }
        $color = $this->model("colorModel");
        $resultcolor = $color->getAllAdmin();
        $colorList = $resultcolor->fetch_all(MYSQLI_ASSOC);

        $size = $this->model("sizeModel");
        $resultsize = $size->getAllAdmin();
        $sizeList = $resultsize->fetch_all(MYSQLI_ASSOC);

        $product = $this->model("productModel");
        $detail = $product->getDetailById($id);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $r = $product->updateDetail($_POST);
            $new = $product->getDetailById($_POST['id']);
            if ($r) {
                echo '<script>alert("Sửa thành công!");window.history.go(-2);</script>';
            } else {
                $this->view("admin/editProductColorSize", [
                    "headTitle" => "Xem/Cập nhật sản phẩm",
                    "cssClass" => "error",
                    "message" => "Lỗi, vui lòng thử lại sau!",
                    "detail" => $new->fetch_assoc(),
                    "colorList" => $colorList,
                    "sizeList" => $sizeList
                ]);
            }
        } else {
            $this->view("admin/editProductColorSize", [
                "headTitle" => "Xem/Cập nhật sản phẩm",
                "cssClass" => "none",
                "detail" => $detail->fetch_assoc(),
                "colorList" => $colorList,
                "sizeList" => $sizeList
            ]);
        }
    }

    public function add()
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }


        $category = $this->model("categoryModel");
        $result = $category->getAllAdmin();
        $categoryList = $result->fetch_all(MYSQLI_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product = $this->model("productModel");
            $result = $product->insert($_POST);
            if ($result) {
                $this->view("admin/addNewProduct", [
                    "headTitle" => "Thêm mới sản phẩm",
                    "cssClass" => "success",
                    "message" => "Thêm mới thành công!",
                    "name" => $_POST['name'],
                    "categoryList" => $categoryList
                ]);
            } else {
                $this->view("admin/addNewProduct", [
                    "headTitle" => "Thêm mới sản phẩm",
                    "cssClass" => "error",
                    "message" => "Lỗi, vui lòng thử lại sau!",
                    "name" => $_POST['name']
                ]);
            }
        } else {
            $this->view("admin/addNewProduct", [
                "headTitle" => "Thêm mới sản phẩm",
                "cssClass" => "none",
                "categoryList" => $categoryList
            ]);
        }
    }

    public function addColorSize($id)
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }
        $product = $this->model("productModel");


        $color = $this->model("colorModel");
        $resultcolor = $color->getAllAdmin();
        $colorList = $resultcolor->fetch_all(MYSQLI_ASSOC);

        $size = $this->model("sizeModel");
        $resultsize = $size->getAllAdmin();
        $sizeList = $resultsize->fetch_all(MYSQLI_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $product->insertColorSize();
            if ($result) {
                $this->view("admin/addNewProductColorSize", [
                    "headTitle" => "Thêm mới màu, size",
                    "cssClass" => "success",
                    "message" => "Thêm mới thành công!",
                    "colorList" => $colorList,
                    "sizeList" => $sizeList
                ]);
            } else {
                $this->view("admin/addNewProductColorSize", [
                    "headTitle" => "Thêm mới màu, size",
                    "cssClass" => "error",
                    "message" => "Lỗi, vui lòng thử lại sau!",
                    "colorList" => $colorList,
                    "sizeList" => $sizeList
                ]);
            }
        } else {
            $p = $product->getByIdAdmin($id)->fetch_assoc();
            $this->view("admin/addNewProductColorSize", [
                "headTitle" => "Thêm mới màu, size",
                "cssClass" => "none",
                "colorList" => $colorList,
                "sizeList" => $sizeList,
                "product" => $p
            ]);
        }
    }

    public function edit($id = "")
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        $category = $this->model("categoryModel");
        $result = $category->getAllAdmin();
        $categoryList = $result->fetch_all(MYSQLI_ASSOC);

        $product = $this->model("productModel");
        $p = $product->getByIdAdmin($id);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $r = $product->update($_POST);
            $new = $product->getByIdAdmin($_POST['id']);
            if ($r) {
                $this->view("admin/editProduct", [
                    "headTitle" => "Xem/Cập nhật sản phẩm",
                    "cssClass" => "success",
                    "message" => "Cập nhật thành công!",
                    "categoryList" => $categoryList,
                    "product" => $new->fetch_assoc()
                ]);
            } else {
                $this->view("admin/editProduct", [
                    "headTitle" => "Xem/Cập nhật sản phẩm",
                    "cssClass" => "error",
                    "message" => "Lỗi, vui lòng thử lại sau!",
                    "categoryList" => $categoryList,
                    "product" => $new->fetch_assoc()
                ]);
            }
        } else {
            $this->view("admin/editProduct", [
                "headTitle" => "Xem/Cập nhật sản phẩm",
                "cssClass" => "none",
                "categoryList" => $categoryList,
                "product" => $p->fetch_assoc()
            ]);
        }
    }

    public function changeStatus($id)
    {
        $product = $this->model("productModel");
        $result = $product->changeStatus($id);
        if ($result) {
            $this->redirect("productManage");
        }
    }

    public function deleteDetail($id)
    {
        $product = $this->model("productModel");
        $result = $product->deleteDetail($id);
        if ($result) {
            echo '<script>window.history.back();</script>';
        }
    }
}
