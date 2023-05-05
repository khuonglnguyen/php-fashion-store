<?php

class product extends ControllerBase
{
    public function search()
    {
        $product = $this->model("productModel");
        $result = $product->search($_GET["keyword"]);
        $productList=[];
        if ($result) {
            $productList = $result->fetch_all(MYSQLI_ASSOC);
        }
        $this->view("client/products", [
            "headTitle" => "Tìm kiếm",
            "title" => "Tìm kiếm với từ khóa: " . $_GET['keyword'],
            "productList" => $productList
        ]);
    }

    function bubble_sort($arr)
    {
        $size = count($arr) - 1;
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size - $i; $j++) {
                $k = $j + 1;
                if ($arr[$k]['date'] > $arr[$j]['date']) {
                    list($arr[$j], $arr[$k]) = array($arr[$k], $arr[$j]);
                }
            }
        }
        return $arr;
    }

    public function removeViewed()
    {
        unset($_SESSION['viewed']);
        $this->redirect('product', 'viewed');
    }

    public function single($Id)
    {
        $question = $this->model("questionModel");
        $product = $this->model("productModel");
        $blog = $this->model("blogModel");
        $result = $product->getById($Id);
        // Fetch
        $p = $result->fetch_assoc();
        $list = $product->getProductSuggest($p['name'], $p['id']);

        if (!isset($_SESSION['viewed'])) {
            $_SESSION['viewed'] = [];
        }
        $index = 0;
        $s = false;
        foreach ($_SESSION['viewed'] as $key => $value) {
            if ($value['id'] == $Id) {
                $s = true;
                $_SESSION['viewed'][$index] = [
                    'id' => $Id,
                    'date' => date("d/m/Y h:i:sa")
                ];
                break;
            }
            $index++;
        }
        if (!$s) {
            $_SESSION['viewed'][count($_SESSION['viewed'])] = [
                'id' => $Id,
                'date' => date("d/m/Y h:i:sa")
            ];
        }
        $_SESSION['viewed'] = $this->bubble_sort($_SESSION['viewed']);

        //productfavorite
        $loved = false;
        if (isset($_SESSION['user_id'])) {
            $productFavorite = $this->model('productFavoriteModel');
            $checkByUserId = $productFavorite->checkByUserId($Id);
            if ($checkByUserId) {
                $loved = true;
            }
        }

        // Rating
        $productRating = $this->model("productRatingModel");
        $productRatingResult = $productRating->getStarByProductId($Id);
        $productRatingContent = $productRating->getByProductId($Id);

        // Question
        $questionContent = $question->getByProductId($Id);

        // Blog
        $blogList = $blog->search($p['name'])->fetch_all(MYSQLI_ASSOC);

        // Size
        if (isset($_GET['color'])) {
            $sizeList = $product->getAllSizeByProductId($Id, $_GET['color'])->fetch_all(MYSQLI_ASSOC);
        } else {
            $sizeList = $product->getAllSizeByProductId($Id)->fetch_all(MYSQLI_ASSOC);
        }
        // Color
        $colorList = $product->getAllColorByProductId($Id)->fetch_all(MYSQLI_ASSOC);
        $this->view("client/single", [
            "headTitle" => $p['name'],
            "product" => $p,
            "productSuggest" => $list,
            "loved" => $loved,
            "star" => $productRatingResult,
            "productRatingContent" => $productRatingContent,
            "questionContent" => $questionContent,
            "blogList" => $blogList,
            "sizeList" => $sizeList,
            "colorList" => $colorList,
            "color" => isset($_GET['color']) ? $_GET['color'] : false,
            "size" => isset($_GET['size']) ? $_GET['size'] : false
        ]);
    }

    public function viewed()
    {
        $arr = [];
        if (isset($_SESSION['viewed'])) {
            $product = $this->model('productModel');
            foreach ($_SESSION['viewed'] as $key => $value) {
                $result = ($product->getById($value['id']))->fetch_assoc();
                array_push($arr, $result);
            }
        }

        $this->view('client/viewed', [
            "headTitle" => "Sản phẩm đã xem",
            "productList" => $arr
        ]);
    }

    public function favorite()
    {
        $productFavorite = $this->model('productFavoriteModel');
        $result = $productFavorite->getByUserId($_SESSION['user_id']);
        $list = $result->fetch_all(MYSQLI_ASSOC);

        $this->view('client/favorite', [
            "headTitle" => "Sản phẩm yêu thích",
            "productList" => $list
        ]);
    }

    public function category($CateId, $page)
    {
        $product = $this->model('productModel');
        $result = $product->getByCateId(isset($page['page']) ? $page['page'] : 1, 8, $CateId);

        $category = $this->model('categoryModel');
        $cate = ($category->getById($CateId))->fetch_assoc();
        $countPaging = $product->getCountPagingByClient($CateId, 8);

        // Fetch
        $productList = $result->fetch_all(MYSQLI_ASSOC);
        $this->view('client/category', [
            "headTitle" => "Danh mục " . $cate['name'],
            "title" => "Danh mục " . $cate['name'],
            "productList" => $productList,
            'countPaging' => $countPaging,
            'CateId' => $CateId
        ]);
    }

    public function addFavorite($productId)
    {
        if (isset($_SESSION['user_id'])) {
            $productFavorite = $this->model('productFavoriteModel');
            $result = $productFavorite->add($productId);
            if ($result) {
                $this->redirect("product", "single", [
                    "Id" => $productId
                ]);
            } else {
                $this->redirect("product", "single", [
                    "Id" => $productId
                ]);
            }
        } else {
            $this->redirect("user", "login");
        }
    }

    public function removeFavorite($productId)
    {
        if (isset($_SESSION['user_id'])) {
            $productFavorite = $this->model('productFavoriteModel');
            $result = $productFavorite->remove($_SESSION['user_id']);
            if ($result) {
                $this->redirect("product", "favorite");
            } else {
                $this->redirect("product", "favorite");
            }
        } else {
            $this->redirect("user", "login");
        }
    }

    public function rating($id)
    {
        $product = $this->model("productModel");
        $productRating = $this->model("productRatingModel");
        $check = $productRating->getByProductIdUserId($id, $_SESSION['user_id']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $productRating->add($_POST['productId'], $_POST['content'], $_POST['star'], $_SESSION['user_id']);
            if ($result) {
                $this->redirect("product", "rating", [
                    "id" => $_POST['productId']
                ]);
            } else {
                $result = $product->getById($_POST['productId'])->fetch_assoc();
                $this->view("client/rating", [
                    "headTitle" => "Đánh giá", "message" => "Lỗi khi thực hiện đánh giá, vui lòng thử lại sau!",
                    "product" => $result
                ]);
            }
        } else {
            $status = false;
            if (mysqli_num_rows($check) > 0) {
                $p = $productRating->getByProductIdUserId($id, $_SESSION['user_id']);
                $status = true;
            }
            $result = $product->getById($id)->fetch_assoc();
            $this->view("client/rating", [
                "headTitle" => "Đánh giá",
                "product" => $result,
                "status" => $status,
                "productRating" => (isset($p) > 0 ? $p : [])
            ]);
        }
    }

    public function addQuestion()
    {
        $question = $this->model("questionModel");
        $result = $question->add($_POST['productId'], $_POST['content'], $_SESSION['user_id']);
        if ($result) {
            echo '<script>window.history.back();</script>';
        } else {
            echo '<script>alert("Lỗi!");window.history.back();</script>';
        }
    }
}
