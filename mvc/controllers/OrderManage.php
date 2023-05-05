<?php

class orderManage extends ControllerBase
{
    public function Index()
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        $order = $this->model("orderModel");
        $result = $order->getAll();
        $orderList = $result->fetch_all(MYSQLI_ASSOC);

        $this->view("admin/order", [
            "headTitle" => "Quản lý đơn đặt hàng",
            "orderList" => $orderList
        ]);
    }

    public function detail($orderId)
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        $orderDetail = $this->model("orderDetailModel");
        $result = $orderDetail->getByorderId($orderId);
        // Fetch
        $orderDetailList = $result->fetch_all(MYSQLI_ASSOC);

        $order = $this->model("orderModel");
        $result = $order->getById($orderId);

        $this->view("admin/orderDetail", [
            "headTitle" => "Chi tiết đơn hàng: " . $orderId,
            "orderId" => $orderId,
            "orderDetailList" => $orderDetailList,
            "order" => $result->fetch_assoc()
        ]);
    }

    public function processed($orderId)
    {
        $order = $this->model("orderModel");
        $result = $order->processed($orderId);
        if ($result) {
            $this->redirect("orderManage");
        }
    }

    public function delivery($orderId)
    {
        $order = $this->model("orderModel");
        $result = $order->delivery($orderId);
        if ($result) {
            $this->redirect("orderManage");
        }
    }
}